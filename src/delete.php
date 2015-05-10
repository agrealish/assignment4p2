<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CS290 Assignment 4.2:Delete</title>
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
    
    if($_POST['del'] == 'all') { //we are deleting all the data in the table
      //if statements below come from the mysqli quickstart for prepared statements from php.net
      //prepare statement
      if(!($stmt = $mysqli->prepare("DELETE FROM videos"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
      if(!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      else {
        echo "<p>All videos successfully deleted.</p>";
      }
    } else if($_POST['del'] != "" && $POST['del'] != 'all') { //delete specific video
      //if statements below come from the mysqli quickstart for prepared statements from php.net
      //prepare statement
      $id = (int)$_POST['del'];
      if(!($stmt = $mysqli->prepare("DELETE FROM videos WHERE id  = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
      //bind statement
      if(!$stmt->bind_param("i", $id)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if(!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      else {
        echo "<p>Video successfully deleted.</p>";
      }
    }
    
    echo '<p>Please click <a href="index.php">here</a> to return to the main page.</p>';
    
    $stmt->close();
    $mysqli->close();
  ?>
  </body>
</html>