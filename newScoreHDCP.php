<?php
    // Start the session
    session_start();
    //echo "Server parameters: " . json_encode($_SERVER) . "<br>";
    //echo "Server Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
    //echo "Server HTTP Referer: " . $_SERVER['HTTP_REFERER'] . "<br>";
    echo "Entering currentHDCP<br>";
    echo "Session parameters: " . json_encode($_SESSION) . "<br>";
    if(!isset($_SESSION['user']))
    {
        // We have been assured that the only way we will get to this page with a
        // request method of POST is via the login.php page
        if ($_SERVER['REQUEST_METHOD'] !== "POST")
        {
            // Since the user is not correctly logged in, then redirect back to login page
            echo "You must be logged in to access this page! You will be redirected to the login page!";
            // TODO Need to figure out how to make the redirection delay so the user can
            // see the above comment about why they are being redirected
            sleep(3);
            header("location: loginHDCP.php");
            exit();
        }
    }
    
    echo "After checking for login currentHDCP<br>";
    //echo "Session parameters: " . json_encode($_SESSION) . "<br>";
    
    //$servername = "oniddb.cws.oregonstate.edu";
    //$username = "resnerb-db";
    //$password = "7qKnFUFXqMYOmsTZ";
    //$database = "resnerb-db";
    $servername = "localhost";
    $username = "root";
    $password = "resnerb";
    $database = "golfHDCP";
    
    $conn = new mysqli($servername, $username, $password);
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
</head>
<body>
<h1>Post a New Score</h1>

<form method="post" action="process.php">
<input type="hidden" name="process" value="addScore">
Date Played [YYYY-MM-DD]:<br>
<input type="text" name="datePlayed">
<br>
Course Name:<br>
<input type="text" name="courseName">
<br>
Score:<br>
<input type="text" name="score">
<br>
<br>
<input type="submit" value="Submit Score">
</form>

<form method="post" action="newCourseHDCP.php">
<br>
<input type="submit" value="Add New Course">
</form>
</body>
</html>