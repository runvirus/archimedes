Imports System.Configuration
Imports System.Security.Cryptography
Imports System.Text
Imports System.IO
Imports System.Web.Script.Serialization
Imports System.Net
Imports Microsoft.Win32.TaskScheduler
Imports Newtonsoft.Json
Imports Newtonsoft.Json.Linq

Public Class Form1
    Function GetHWID()
        Dim HWID As String = System.Security.Principal.WindowsIdentity.GetCurrent.User.Value
        Return HWID
    End Function

    Function fetchTime()
        Dim server As String = AppConfigReader.Server
        Dim sHost As String = AppConfigReader.Host
        Dim webbrowser1 As New WebBrowser

        webbrowser1.Navigate(server & "?hwid=" & GetHWID() & "&host=" & sHost & "&action=gettime")
        Do While webbrowser1.ReadyState <> WebBrowserReadyState.Complete
            Application.DoEvents()
        Loop
        If webbrowser1.DocumentText.Contains("Invalid HWID") Then
            MessageBox.Show("Invalid HWID!")
            Exit Function
        ElseIf webbrowser1.DocumentText.Contains("Invalid Host") Then
            MessageBox.Show("Invalid Host!")
            Exit Function
        ElseIf webbrowser1.DocumentText.Contains("Host missing.") Then
            MessageBox.Show("Host missing.")
            Exit Function
        ElseIf webbrowser1.DocumentText.Contains("HWID missing.") Then
            MessageBox.Show("HWID missing.")
            Exit Function
        End If

        Return webbrowser1.DocumentText
    End Function
    Function fetchPath()
        Dim server As String = AppConfigReader.Server
        Dim sHost As String = AppConfigReader.Host
        Dim webbrowser1 As New WebBrowser

        webbrowser1.Navigate(server & "?hwid=" & GetHWID() & "&host=" & sHost & "&action=fetchpath")
        Do While webbrowser1.ReadyState <> WebBrowserReadyState.Complete
            Application.DoEvents()
        Loop
        If webbrowser1.DocumentText.Contains("Invalid HWID") Then
            MessageBox.Show("Invalid HWID!")
            Exit Function
        ElseIf webbrowser1.DocumentText.Contains("Invalid Host") Then
            MessageBox.Show("Invalid Host!")
            Exit Function
        ElseIf webbrowser1.DocumentText.Contains("Host missing.") Then
            MessageBox.Show("Host missing.")
            Exit Function
        ElseIf webbrowser1.DocumentText.Contains("HWID missing.") Then
            MessageBox.Show("HWID missing.")
            Exit Function
        End If

        Return webbrowser1.DocumentText
    End Function

    Function fetchHashes()
        Dim server As String = AppConfigReader.Server
        Dim sHost As String = AppConfigReader.Host
        Dim webbrowser1 As New WebBrowser

        webbrowser1.Navigate(server & "?hwid=" & GetHWID() & "&host=" & sHost & "&action=gethashes")
        Do While webbrowser1.ReadyState <> WebBrowserReadyState.Complete
            Application.DoEvents()
        Loop
        If webbrowser1.DocumentText.Contains("Invalid HWID") Then
            MessageBox.Show("Invalid HWID!")
            Exit Function
        ElseIf webbrowser1.DocumentText.Contains("Invalid Host") Then
            MessageBox.Show("Invalid Host!")
            Exit Function
        ElseIf webbrowser1.DocumentText.Contains("Host missing.") Then
            MessageBox.Show("Host missing.")
            Exit Function
        ElseIf webbrowser1.DocumentText.Contains("HWID missing.") Then
            MessageBox.Show("HWID missing.")
            Exit Function
        End If

        Return webbrowser1.DocumentText
    End Function
    Function hashFiles()
        Dim paths As String = fetchPath()
        Dim pathArr() As String = paths.Split(";")
        Dim jArr As New JArray()

        ' Get all files in the given folder paths, hash them and add them to jArr
        For count = 0 To pathArr.Length - 1
            Dim files = IO.Directory.GetFiles(pathArr(count))
            For Each strFile In files

                Dim md5String = MD5FileHash(strFile)
                Dim fileCreatedDate As DateTime = File.GetCreationTime(strFile)


                Dim jObj As New JObject(
                    New JProperty("file", strFile.Replace("\", "/")),
                    New JProperty("hash", md5String),
                    New JProperty("ddate", fileCreatedDate.ToString("yyyy-MM-dd")))

                jArr.Add(jObj)
            Next
        Next
        Return jArr
    End Function
    Function getActive()
        Return SendRequest(AppConfigReader.Server & "?hwid=" & GetHWID() & "&host=" & AppConfigReader.Host & "&action=getactive", Encoding.UTF8.GetBytes("json=adsa"), "application/x-www-form-urlencoded", "POST")
    End Function
    Function doAction()
        Dim arrHashedFiles As New JArray()
        arrHashedFiles = hashFiles()

        Dim active As String = getActive()
        If active = 0 Then
            Dim res As String = SendRequest(AppConfigReader.Server & "?hwid=" & GetHWID() & "&host=" & AppConfigReader.Host & "&action=fileentry", Encoding.UTF8.GetBytes("json=" & arrHashedFiles.ToString()), "application/x-www-form-urlencoded", "POST")

        ElseIf active = 1 Then

            Dim dbFiles As JArray = JArray.Parse(fetchHashes())

            Dim ccount As Integer = 0
            Dim sameCount As Integer = 0
            Dim numFiles As Integer = arrHashedFiles.Count

            Dim arrDiffFiles As New JArray
            Dim arrOldNewFiles As New JArray
            Dim arrNewFiles As New JArray

            ' Loop through the files in the folder
            For Each File As JObject In arrHashedFiles
                ccount = ccount + 1

                ' Check if the database holds an entry for the files hash
                If dbFiles.ToString().Contains(File("hash").ToString()) Then
                    ' It does, so the file is the same.
                    sameCount = sameCount + 1
                Else
                    ' It doesn't, this is either a different or a new file.
                    arrDiffFiles.Add(File)
                End If

            Next

            ' To get the hash of the old file, we need to find its path and name in the database.
            For Each diffFile As JObject In arrDiffFiles
                For Each jItem As JObject In dbFiles
                    If jItem("file").ToString = diffFile("file").ToString.Replace("/", "\") Then
                        ' The files path was found. Now we can get the old hash from the database.
                        Dim oldNewFile As New JObject(
                            New JProperty("file", jItem("file").ToString.Replace("\", "/")),
                            New JProperty("oldhash", jItem("hash").ToString()),
                            New JProperty("newhash", diffFile("hash").ToString()))
                        arrOldNewFiles.Add(oldNewFile)
                    Else
                        ' The file wasnt found in the database, meaning it's a new file.
                        Dim newFile As New JObject(
                            New JProperty("file", jItem("file").ToString.Replace("\", "/")),
                            New JProperty("newhash", diffFile("hash").ToString()))
                        arrNewFiles.Add(newFile)
                    End If
                Next
            Next

            If arrOldNewFiles.Count > 0 Then

                Dim res_ As String = SendRequest(AppConfigReader.Server & "?hwid=" & GetHWID() & "&host=" & AppConfigReader.Host & "&action=difffileentry", Encoding.UTF8.GetBytes("json=" & arrOldNewFiles.ToString), "application/x-www-form-urlencoded", "POST")
            End If
            If arrNewFiles.Count > 0 Then

                Dim _res As String = SendRequest(AppConfigReader.Server & "?hwid=" & GetHWID() & "&host=" & AppConfigReader.Host & "&action=newfileentry", Encoding.UTF8.GetBytes("json=" & arrNewFiles.ToString), "application/x-www-form-urlencoded", "POST")
            End If
            Application.Exit()
        End If
    End Function

    Public Function MD5FileHash(ByVal sFile As String) As String
        Dim MD5 As New MD5CryptoServiceProvider
        Dim Hash As Byte()
        Dim Result As String = ""
        Dim Tmp As String = ""

        Dim FN As New FileStream(sFile, FileMode.Open, FileAccess.Read, FileShare.Read, 8192)
        MD5.ComputeHash(FN)
        FN.Close()

        Hash = MD5.Hash
        For i As Integer = 0 To Hash.Length - 1
            Tmp = Hex(Hash(i))
            If Len(Tmp) = 1 Then Tmp = "0" & Tmp
            Result += Tmp
        Next
        Return Result
    End Function

    Private Function SendRequest(uri As String, jsonDataBytes As Byte(), contentType As String, method As String) As String
        Dim req As WebRequest = WebRequest.Create(uri)
        req.ContentType = contentType
        req.Method = method
        req.ContentLength = jsonDataBytes.Length

        Dim stream = req.GetRequestStream()
        stream.Write(jsonDataBytes, 0, jsonDataBytes.Length)
        stream.Close()

        Dim response = req.GetResponse().GetResponseStream()

        Dim reader As New StreamReader(response)
        Dim res = reader.ReadToEnd()
        reader.Close()
        response.Close()

        Return res
    End Function

    Private Sub Form1_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        TextBox1.Text = GetHWID()
        ' Form should start once to register the task, it can be closed afterwards.
        ' The application will then run itself via the task with the parameter "hashnow"

        ' Start on windows startup
        My.Computer.Registry.LocalMachine.OpenSubKey("SOFTWARE\Microsoft\Windows\CurrentVersion\Run", True).SetValue(Application.ProductName, Application.ExecutablePath)


        ' Check for startup parameter
        Dim parameter() As String = Environment.GetCommandLineArgs().ToArray
        If (parameter.Count - 1) >= 1 Then
            For i = 1 To parameter.Count - 1
                If (parameter(i) = "hashnow") Then
                    doAction()
                    Exit Sub
                End If
            Next
        End If

        Dim serializer As New JavaScriptSerializer()

        Dim time As String = fetchTime()
        Dim eDate = DateTime.Today + " " + time
        Dim result As DateTime
        Dim myPathName As String = Process.GetCurrentProcess().MainModule.FileName
        DateTime.TryParse(eDate, result)

        ' Register taskscheduler task.
        Using tService As New TaskService()
            Dim tDefinition As TaskDefinition = tService.NewTask()
            tDefinition.RegistrationInfo.Description =
               "Rehash the files"
            tDefinition.Principal.RunLevel = TaskRunLevel.Highest

            Dim tTrigger As New DailyTrigger()
            tTrigger.StartBoundary = DateTime.Today + TimeSpan.FromHours(result.Hour) + TimeSpan.FromMinutes(result.Minute)
            tTrigger.DaysInterval = 1

            tDefinition.Triggers.Add(tTrigger)
            tDefinition.Actions.Add(New ExecAction(myPathName, "hashnow"))

            tService.RootFolder.RegisterTaskDefinition("RehashFilesDaily",
               tDefinition)
        End Using

        ' Exit application
        ' Application.Exit()
        ' End

    End Sub
End Class
Public Class AppConfigReader
    ' Lesen der Einstellungen aus XML app.config
    Private Shared aSettingsReader As New System.Configuration.AppSettingsReader

    Private Shared strHost As String = aSettingsReader.GetValue("Host", GetType(String))
    Private Shared strServer As String = aSettingsReader.GetValue("Server", GetType(String))


    Public Shared ReadOnly Property Host() As String
        Get
            Return strHost
        End Get
    End Property
    Public Shared ReadOnly Property Server() As String
        Get
            Return strServer
        End Get
    End Property

End Class

Public Class MyFile
    Public Property id() As Integer
        Get
            Return m_id
        End Get
        Set
            m_id = Value
        End Set
    End Property
    Private m_id As Integer

    Public Property hostname() As String
        Get
            Return m_hostname
        End Get
        Set
            m_hostname = Value
        End Set
    End Property
    Private m_hostname As String

    Public Property file() As String
        Get
            Return m_file
        End Get
        Set
            m_file = Value
        End Set
    End Property
    Private m_file As String

    Public Property hash() As String
        Get
            Return m_hash
        End Get
        Set
            m_hash = Value
        End Set
    End Property
    Private m_hash As String

    Public Property ddate() As String
        Get
            Return m_ddate
        End Get
        Set
            m_ddate = Value
        End Set
    End Property
    Private m_ddate As String

End Class