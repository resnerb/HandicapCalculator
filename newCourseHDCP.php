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
<h1 id="header">Enter Information for a New Golf Course</h1>

<form align="center" method="post" action="process.php">
<input align="center" type="hidden" name="process" value="addCourse">
<font face="Courier New">Course Name:</font><br>
<input align="center" type="text" name="courseName">
<br>
<font face="Courier New">Slope:</font><br>
<input align="center" type="text" name="slope">
<br>
<font face="Courier New">Rating:</font><br>
<input align="center" type="text" name="rating">
<br>
<font face="Courier New">Par:</font><br>
<input align="center" type="text" name="par">
<br>
<br>
<input align="center" type="submit" value="Submit Course Info">
</form>

<br>
<p id="header">The Course Rating is the numerical value given by the UGSA to each set of tees on a course. It approximates the number of strokes it should take a scratch golfer to finish the course. Most courses make this information available to their golfers; check the course website or inquire at the clubhouse.</p><br>
<p id="header">The Slope Rating shows the difficulty of a course for an average golfer and is is calculated by comparing the Course Rating to the scores of bogey golfers. Most golf courses make the Slope Rating available to their guests; again, check their website or at the clubhouse.</p><br><br>
</body>
</html>
