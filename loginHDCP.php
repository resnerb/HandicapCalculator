<?php
    // Start the session - this must be the first thing in the file
    session_start();
    //echo "Session parameters: " . json_encode($_SESSION) . "<br>";
    if(isset($_SESSION['user']))
    {
        // If the user is logged in, then redirect to the content1 page
        header("Location: currentHDCP.php");
        exit();
    }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Login Page</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<h1 id="header">Golf Handicap Calculator</h1>

<form align="center" action="currentHDCP.php" method="post">
<font face="Courier New">Username: </font><br><input type="text" name="username"><br><br>
<input type="submit" value="Login">
</form>

<h2 id="header">Here are some links to give you background on golf handicaps and why they are important:</h2>

<p id="header">Wikipedia provides a good background: <a href="http://en.wikipedia.org/wiki/Handicap_%28golf%29"> Click Here</a></p>
<p id="header">The United States Golf Association (USGA) explains the importance of having a hanidcap index: <a href="http://www.usga.org/Handicapping.html">Click Here</a></p>
<p id="header">This is my reference for computing a handicap index: <a href="http://www.wikihow.com/Calculate-Your-Golf-Handicap">Click Here</a></p>
</body>
</html>

