<?php
require_once "functions.php";
require_once 'DBconnect.php';

$arrResults = fetchTable($mysqli, "notification");
?>

<br /><br />
<body>
  <div class="tableHolder">
    <div class="inner">
      <table>
        <tr>
            <td COLSPAN="3">
               <h3><BR>Notification Table</h3>
            </td>
         </tr>
         <th>Path</th>
         <th>Hostname</th>
         <th>Oldhash</th>
         <th>Newhash</th>
      <?php foreach ($arrResults as $key => $value):
        echo '
              <tr class="innerTable">
                <td>'.$value["file"].'</td>
                <td>'.$value["hostname"].'</td>
                <td>'.$value["old_hash"].'</td>
                <td>'.$value["new_hash"].'</td>
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
