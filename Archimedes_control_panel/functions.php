<?php
/**
 * Checks if the given hwid exists in the database
 *
 * @param string $hwid Hwid
 * @param object $mysqli The MySQLi Object
 */
function validateHwid($hwid, $mysqli){
  $query = $mysqli->query("SELECT * FROM user WHERE hwid ='$hwid'");
  if($query){
    for ($set = array (); $row = $query->fetch_assoc(); $set[] = $row);

    if(sizeof($set) != 1){
      return false;
    }

    return true;
  }
  return false;
}

/**
 * Checks if the given hostname exists in the database
 *
 * @param string $host Hostname
 * @param object $mysqli The MySQLi Object
 */
function validateHost($host, $mysqli){
  $query = $mysqli->query("SELECT * FROM host WHERE hostname = '$host'");

  if($query){
    $row = $query->fetch_array();

    if(!empty($row)){
      return true;
    }
    return false;
  }
  return false;
}

/**
 * Fetches the folderpath from the database.
 *
 * @param string $host The given hostname
 * @param object $mysqli The MySQLi Object
 *
 * @return string Folderpath/s
 */
function fetchPath($host, $mysqli){
  $query = $mysqli->query("SELECT * FROM host WHERE hostname = '$host'");

  if($query){
    $row = $query->fetch_array();

    if(!empty($row)){
      return $row['folderpath'];
    }
    return false;
  }
  return false;
}

/**
 * Inserts the hashed files into the database.
 *
 * @param json $json The json-encoded file array, containing each file with the corresponding hash, hostname, filepath and date
 * @param object $mysqli The MySQLi Object
 * @param string $hostname The hostname
 */
function fileEntry($json, $mysqli, $hostname){
  $jsonArray = json_decode($json, true);

  foreach($jsonArray as $item) { // Loop through the files
    $query = $mysqli->query("SELECT * from filelist WHERE hash = '$item[1]'");
    if($query){
      $row = $query->fetch_array();
      if(empty($row)){
        $filepath = mysqli_real_escape_string($mysqli, str_replace("_", '\\', $item[0]));
        $query = $mysqli->query("INSERT INTO `filelist`(`id`, `hostname`, `file`, `hash`, `date`) VALUES ('','". $hostname ."', '".$filepath."', '".$item[1]."', '".$item[2]."')");
      }
    }
  }

  if($query){
    return true;
  } else {
    return false;
  }
}

/**
 * Inserts the different files into the database.
 *
 * @param json $json The json-encoded file array, containing each file with the corresponding hash, hostname, filepath and date
 * @param object $mysqli The MySQLi Object
 * @param string $hostname The hostname
 */
function diffFileEntry($json, $mysqli, $hostname){
  $jsonArray = json_decode($json, true);

  foreach($jsonArray as $item) { // Loop through the files
        $filepath = mysqli_real_escape_string($mysqli, str_replace("_", '\\', $item["file"]));
        $query = $mysqli->query("INSERT INTO `notification`(`id`, `hostname`, `file`, `old_hash`, `new_hash`) VALUES ('','". $hostname ."', '".$filepath."', '".$item["oldhash"]."', '".$item["newhash"]."')");
  }

  if($query){
    return true;
  } else {
    return false;
  }
}

/**
 * Compares the existing hashings in the alert table with the ones in the filelist table
 *
 * @param object $mysqli  The MySQLi Object
 */
function compareFiles($mysqli){
  $query = $mysqli->query("SELECT * from alert");
  if($query){
    $arrSameHashes = array();
    $arrDifferentHashes = array();
    $alertHashes = array();
    $filelistHashes = array();

    while($row = $query->fetch_assoc()){
      if(!empty($row["hash"])){
        array_push($alertHashes, $row["hash"]);
      }
    }

    $_query = $mysqli->query("SELECT * from filelist");
    while($_row = $_query->fetch_assoc()){
      if(!empty($_row["hash"])){
        array_push($filelistHashes, $_row);
      }
    }


    //  This does the same, but it's only usable with a one-dimensional array. ( So just the hashes )
    //  Meaning I could just use the hashes, but I would have to loop through another array to get the fileinfo for each hash.
    //
    //  $arrDifferentHashes = array_diff($filelistHashes, $alertHashes);
    //  $arrSameHashes = array_intersect($filelistHashes, $alertHashes);


    // Loops through the hashes in the table alert and finds same files, but not the different ones.
    // An else statement in the while loop would find the different ones, but would also register the same files as different because it compares one to every other.
    //
    // foreach ($alertHashes as $key => $value) {
    //   // Finding the same files here
    //     $_query = $mysqli->query("SELECT * from filelist WHERE hash = '$value'");
    //     if($_query){
    //       while($_row = $_query->fetch_assoc()){
    //         if(!empty($_row["hash"]) && !in_array($_row, $arrSameHashes)){
    //           array_push($arrSameHashes, $_row);
    //         } // * here
    //       }
    //     }
    // }
    // print_r($arrSameHashes);
    // return array($arrSameHashes);


    // Going backwards here, looping through filelisthashes. Seems to be the best solution.
    //
    foreach ($filelistHashes as $kkey => $vvalue) {
      if(in_array($vvalue["hash"], $alertHashes)){
        array_push($arrSameHashes, $vvalue);
      } else {
        array_push($arrDifferentHashes, $vvalue);
      }
    }
    return array($arrSameHashes, $arrDifferentHashes);
  }
}

/**
 * Gets the time.
 *
 * @param string $host The given hostname
 * @param object $mysqli The MySQLi Object
 *
 * @return Datetime Time
 */
function getTime($host, $mysqli){
  $query = $mysqli->query("SELECT ctime FROM ttime WHERE hostname = '$host'");

  if($query){
    $row = $query->fetch_assoc();
    if(!empty($row)){
      return $row["ctime"];
    }
    return false;
  }
  return false;
}

/**
 * Gets the old hashes from filelist table.
 *
 * @param string $host The given hostname
 * @param object $mysqli The MySQLi Object
 *
 * @return hashes
 */
function getHashes($host, $mysqli){
  $query = $mysqli->query("SELECT * FROM filelist WHERE hostname = '$host'");
  $data = array();

  if($query){
    while($row = $query->fetch_assoc()){
      $data[] = $row;
    }
    echo str_replace("\"date\"", '"ddate"', json_encode($data));
  }
}

/**
 * Fetches data from the database
 *
 *  @param  object $mysqli  The MySQLi Object
 *  @param  string $table  The table
 *  @return array  $data    The table data
 */
function fetchTable($mysqli, $table){
  $query = $mysqli->query("SELECT * FROM $table");
  $data = array();

  if($query){
    while($row = $query->fetch_assoc()){
      $data[] = $row;
    }
    return $data;
  }
}

/**
 * Searches the entries from "alert" in the notification and filelist table
 *
 *  @param  object $mysqli  The MySQLi Object
 *  @return array  $filelistResults, $notificationResults
 */
function searchAlertFiles($mysqli){
  $query = $mysqli->query("SELECT * FROM alert");
  $alertData = array();

  if($query){
    while($row = $query->fetch_assoc()){
      $alertData[] = $row;
    }
  }
  unset($query, $row);

  if(!empty($alertData)){
    $filelistResults = array();
    $notificationResults = array();

    // Fetch filelist data
    $filelistData = fetchTable($mysqli, "filelist");

    // Fetch notification data
    $notificationData = fetchTable($mysqli, "notification");

    $foundInNot = array();
    $foundInFil = array();

    foreach ($alertData as $key => $value) {
      $nKeyFile = array_search(array_find_file($value["hash"], $notificationData)["file"], array_column($notificationData, 'file'));
      $nKeyOldHash = array_search($value["hash"], array_column($notificationData, 'old_hash'));
      $nKeyNewHash = array_search($value["hash"], array_column($notificationData, 'new_hash'));

      if(count($nKeyFile) > 0){
        if(!in_array($nKeyFile, $foundInNot, true)){
          array_push($foundInNot, $nKeyFile);
        }
      }

      if(count($nKeyOldHash) > 0){
        if(!in_array($nKeyOldHash, $foundInNot, true)){
          array_push($foundInNot, $nKeyOldHash);
        }
      }

      if(count($nKeyNewHash) > 0){
        if(!in_array($nKeyNewHash, $foundInNot, true)){
          array_push($foundInNot, $nKeyNewHash);
        }
      }


      // // Search $filelistData
      $fKeyFile = array_search(array_find_file($value["hash"], $filelistData)["file"], array_column($filelistData, 'file'));
      $fKeyHash = array_search($value["hash"], array_column($filelistData, 'hash'));
      if($fKeyFile){
        if(!in_array($fKeyFile, $foundInFil)){
          array_push($foundInFil, $fKeyFile);
        }
      }
      if($fKeyHash){
        if(!in_array($fKeyHash, $foundInFil)){
          array_push($foundInFil, $fKeyHash);
        }
      }

    }

    foreach ($foundInFil as $key => $value) {
      if(!empty($value) || $value === 0){
        array_push($filelistResults, $filelistData[$value]);
      }
    }
    foreach ($foundInNot as $key => $value) {
      if(!empty($value) || $value === 0){
        array_push($notificationResults, $notificationData[$value]);
      }
    }

    return array($filelistResults, $notificationResults );
  }
}

function array_find_file($needle, $haystack)
{
   foreach ($haystack as $item)
   {
      if (strpos($item["file"], $needle) !== FALSE)
      {
         return $item;
         break;
      }
   }
}
