<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CS290 Assignment 4.2:Index</title>
  </head>
  <body>
  <h1>Video Inventory</h1>
  <br><hr><br>
  <form action="add.php" method="post">
    Adding a Video
    <br><br>
    Video Title: <input type="text" name="name" required>
    <br>
    Video Genre: <input type="text" name="category" placeholder="e.g. Action">
    <br>
    Video Length: <input type="text" name="length">
    <br>
    <input type="submit"  value="Add Video">
  </form>
  <br>
  <h3>List of Videos in Inventory</h3>
  <br>
  <form action="index.php" method="post">
    <select name="vidCat">
      <option value="all">All Movies</option>
  <?php
    ini_set('display_errors', 'On'); //from the lecture
    include 'storedInfo.php'; //from the lecture
    
    //connection sequence is from the lecture
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "grealisa-db", $password, "grealisa-db");
    if(!$mysqli || $mysqli->connect_errno) {
      echo "There was a connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
    
    $catResult = $mysqli->query("SELECT DISTINCT category FROM videos ORDER BY category ASC");
    
    while ($cat = $catResult->fetch_assoc()) {
      echo "<option value='" . $cat . "'>" . $cat . "</option>";
    }
    echo "</select>";
    echo "<input type='submit' value='Filter by Genre'>";
    
    $mysqli->close();
  ?>
  </form>
  <table>
    <thead>
      <tr>
        <th>Video Name <th>Video Genre <th>Video Length <th>Status <th>Change Status <th>Delete
      <tbody>
  
  <?php
    ini_set('display_errors', 'On'); //from the lecture
    include 'storedInfo.php'; //from the lecture
    
    //connection sequence is from the lecture
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "grealisa-db", $password, "grealisa-db");
    if(!$mysqli || $mysqli->connect_errno) {
      echo "There was a connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
    
    $genre = NULL;
    if(isset($_POST['vidCat'])) {
      $genre = $_POST['vidCat'];
    }
    
    if(is_null($genre) || $genre=='all') { //this is if all movies should be shown
      //if statement below comes from the mysqli quickstart for prepared statements from php.net
      if(!($stmt = $mysqli->prepare("SELECT id, name, category, length, rented FROM videos"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
    }
    else { //only specific movies should be shown - according to $genre
      //if statements below come from the mysqli quickstart for prepared statements from php.net
      //prepare statement
      if(!($stmt = $mysqli->prepare("SELECT id, name, category, length, rented FROM videos WHERE category = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }
      //bind statement
      if(!$stmt->bind_param("s", $genre)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }
    }
    
    //execute if statement below comes from the mysqli quickstart for prepared statements from php.net
    if(!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    
    $id = NULL;
    $name = NULL;
    $category = NULL;
    $length = NULL;
    $status = NULL;
    //bind parameters if statement below comes from the mysqli quickstart for prepared statements from php.net
    if(!$stmt->bind_result($id, $name, $category, $length, $status)) {
      echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    
    //output results to table - some of this taken from the mysqli quickstart for prepared statements from php.net
    while ($stmt->fetch()) {
      $rented = NULL;
      //set the string of rented to either 'available' or 'checked out'
      if($status == 0) {
        $rented = "available";
      }
      else {
        $rented = "checked out";
      }
      echo "<tr>";
      echo "<td>" . $name . "<td>" . $category . "<td>" . $length . "<td>" . $rented;
      //forms within tables found at: http://stackoverflow.com/questions/5528419/html-table-with-button-on-each-row
      echo "<td> <form action='update.php' method='post'> <input type='submit' name='" . $id . "' value='Change Status'> </form>";
      echo "<td> <form action='delete.php' method='post'> <input type='submit' name='" . $id . "' value='Delete'> </form>";
    }
    
    $stmt->close();
    $mysqli->close();
  ?>
    </tbody>
  </table>
  <form action = "delete.php" method = "post">
    <input type="submit" name="all" value='Delete All'>
  </form>"
  </body>
</html>