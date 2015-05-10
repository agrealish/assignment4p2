<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CS290 Assignment 4.2:Update</title>
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
    
    if(isset($_POST['upd'])) {
      $id = (int)$_POST['upd'];
      
      //if statements below come from the mysqli quickstart for prepared statements from php.net
      //prepare statement
      if(!($stmt1 = $mysqli->prepare("SELECT rented FROM videos WHERE id  = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
      //bind statement
      if(!$stmt1->bind_param("i", $id)) {
        echo "Binding parameters failed: (" . $stmt1->errno . ") " . $stmt1->error;
      }
      if(!$stmt1->execute()) {
        echo "Execute failed: (" . $stmt1->errno . ") " . $stmt1->error;
      }
      else {
        echo "<p>Video successfully deleted.</p>";
      }
      
      $status = NULL;
      if(!stmt1->bind_result($status)) {
        echo "Binding output parameters failed: (" . $stmt1->errno . ") " . $stmt1->error;
      }
      
      $stmt1->close();
      if($status == 0) {
        $status = 1;
      }
      else {
        $status = 0;
      }
      
      //if statements below come from the mysqli quickstart for prepared statements from php.net
      //prepare statement
      if(!($stmt = $mysqli->prepare("UPDATE videos SET rented = ? WHERE id  = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
      //bind statement
      if(!$stmt->bind_param("ii", $status, $id)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if(!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      else {
        echo "<p>Video successfully updated.</p>";
      }
    }
    
    echo '<p>Please click <a href="index.php">here</a> to return to the main page.</p>';
    
    $stmt->close();
    $mysqli->close();
  ?>
  </body>
</html>