<?php
require_once "functions.php";
require_once 'DBconnect.php';

list($filelistResults, $notificationResults) = searchAlertFiles($mysqli);
?>


<body>
  <div class="tableHolder">
    <div class="inner">
      <br/>
      <table>
        <tr>
            <td COLSPAN="3">
               <h3><BR>Search results in notification table</h3>
            </td>
         </tr>
         <th>Hostname</th>
         <th>File</th>
         <th>Old hash</th>
         <th>New hash</th>
      <?php foreach ($notificationResults as $key => $value):
        echo '
              <tr class="innerTable">
                <td>'.$value["hostname"].'</td>
                <td>'.$value["file"].'</td>
                <td>'.$value["old_hash"].'</td>
                <td>'.$value["new_hash"].'</td>
              </tr>
        ';
      endforeach; ?>
      </table>
      <br/><br/>

      <table>
        <tr>
            <td COLSPAN="3">
               <h3><BR>Search results in filelist table</h3>
            </td>
         </tr>
         <th>Hostname</th>
         <th>File</th>
         <th>Hash</th>
         <th>Date</th>
      <?php foreach ($filelistResults as $key => $value):
        echo '
              <tr class="innerTable">
                <td>'.$value["hostname"].'</td>
                <td>'.$value["file"].'</td>
                <td>'.$value["hash"].'</td>
                <td>'.$value["date"].'</td>
              </tr>
        ';
      endforeach; ?>
      </table>
    </div>
  </div>
</body>


<style>
  body  {
    background-color:#34495e; color:#ecf0f1;
  }
  table, th, td {
     padding: 5px;
  }
  th, td {
    border-bottom: 1px solid #7f8c8d;
  }
  table {
    background-color:#2c3e50; border-collapse: collapse;
  }
  th {
    height: 30px;
    background-color: #e67e22;
  }
  td {
    text-align: center;
    font-weight: bold;
  }
  .innerTable:hover {background-color: #34495e}

  .inner {
    display:table; margin: 0 auto;
    overflow-x:auto;
  }
  .tableHolder {
    width: 100%; margin: 0 auto;
  }
</style>
