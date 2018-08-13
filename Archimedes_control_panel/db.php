<?php
// connect to the server
$con = new mysqli("localhost","root","","wazf");

// check connection
if (mysqli_connect_errno()) {
  exit('Connect failed: '. mysqli_connect_error());
}
?>