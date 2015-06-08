<?php
    // Start the session
    session_start();
    //echo "Server parameters: " . json_encode($_SERVER) . "<br>";
    //echo "Server Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
    //echo "Server HTTP Referer: " . $_SERVER['HTTP_REFERER'] . "<br>";
    
    if(!isset($_SESSION['user']))
    {
        // We have been assured that the only way we will get to this page with a
        // request method of POST is via the login.php page
        if ($_SERVER['REQUEST_METHOD'] !== "POST")
        {
            // Since the user is not correctly logged in, then redirect back to login page
            //echo "You must be logged in to access this page! You will be redirected to the login page!";
            // TODO Need to figure out how to make the redirection delay so the user can
            // see the above comment about why they are being redirected
            //sleep(3);
            header("Location: loginHDCP.php");
            exit();
        }
    }
    
    //echo "Session parameters: " . json_encode($_SESSION) . "<br>";
    
    $servername = "oniddb.cws.oregonstate.edu";
    $username = "resnerb-db";
    $password = "7qKnFUFXqMYOmsTZ";
    $database = "resnerb-db";
    //$servername = "localhost";
    //$username = "root";
    //$password = "resnerb";
    //$database = "golfHDCP";
    
    $conn = new mysqli($servername, $username, $password, $database);
    //Check if connection works
    if ($conn->connect_error)
    {
        die ("Connection failed: " . $conn->connect_error);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>newScoreHDCP</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1 id="header">Post a New Score</h1>

<?php

    //Use golfCourses table
    $sql = "SELECT * FROM golfCourses";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        
        echo "<form align='center' method='post' action='process.php'>";
        echo "<input type='hidden' name='process' value='addScore'>";
        
        echo "<font face='Courier New'>Date Played [YYYY-MM-DD]:</font><br>";
        echo "<input align='center' type='text' name='datePlayed'>";
        echo "<br>";
        echo "<font face='Courier New'>Score:</font><br>";
        echo "<input align='center' type='text' name='score'>";
        echo "<br>";
        echo "<font face='Courier New'>Course Name:</font><br>";
        echo "<select name='courseName'>";
        
        while ($row = mysqli_fetch_array($result)) {
            $courseName = $row['name'];
            
            echo "<option value='" . $courseName . "'>" . $courseName . "</option>";
        }
        echo "</select>";
        
        echo "<br>";
        echo "<br>";

        echo "<input align='center' type='submit' value='Submit Score'>";
        echo "</form>";
        
    }
    else {
        echo "<font face='Courier New'>There are no golf courses in the database. You must enter course information before entering a new score.</font>";
    }
?>
<h2 id="header">Did you play a new course? Enter course information by clicking the button below.</h2><br>
<form align="center" method="post" action="newCourseHDCP.php">
    <input type="submit" value="Add New Course">
</form>
<br>
<form align="center" method="post" action="currentHDCP.php">
<input type="submit" value="Go To Main Page">
</form>

</body>
</html>