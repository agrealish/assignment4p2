<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CS290 Assignment 4.2:Add</title>
  </head>
  <body>
  <?php
    ini_set('display_errors', 'On'); //from the lecture
    include 'storedInfo.php'; //from the lecture
    
    //connection sequence is from the lecture
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "grealisa-db", $password, "grealisa-db");
    if(!$mysqli || $mysqli->connect_errno) {
      echo "There was a connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
    
    $eCheck = false; //if errors are found, change to true
    
    if($_POST['name'] === "") { //if name doesn't exist, print error message
      $eCheck = true;
      echo "<p>No Video Title provided.  Video Title is a required field.</p>";
    }
    if($_POST['length']!="") { //checks the length field - if it exists
      if(!(is_numeric($_POST['length']))) {
        $eCheck = true;
        echo "<p>The length of the video is invalid.  It must be an integer.";
      } else if ((int)$_POST['length']<0) {
        $eCheck = true;
        echo "<p>The length of the video is invalid.  It must be a postive integer.";
      }
    }
    
    if(!$eCheck) { //if no errors, add to table
      $vName = $_POST['name'];
      $vCat = NULL;
      $vLength = NULL;
      
      //if data exists for category and length then change values
      if($_POST['category'] != "") {
        $vCat = $_POST['category'];
      }
      if($_POST['length'] != "") {
        $vLength = (int)$_POST['length'];
      }
      //if statements below come from the mysqli quickstart for prepared statements from php.net
      //prepare statement
      if(!($stmt = $mysqli->prepare("INSERT INTO videos(name, category, length) VALUES (?,?,?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
      //bind statement
      if(!$stmt->bind_param("ssi", $vName, $vCat, $vLength)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if(!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      else {
        echo "<p>Video successfully added to table.</p>";
      }
      $stmt->close();
    }
    
    echo '<p>Please click <a href="index.php">here</a> to return to the main page.</p>';
    
    $mysqli->close();
  ?>
  </body>
</html>