<?php
    // Start the session - this must be the first thing in the file
    session_start();
    //echo "Session parameters: " . json_encode($_SESSION) . "<br>";
    if(isset($_SESSION['user']))
    {
        // If the user is logged in, then redirect to the content1 page
        header("location: currentHDCP.php");
        exit();
    }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Login Page</title>
</head>
<body>

<form action="currentHDCP.php" method="post">
Username: <input type="text" name="username"><br>
<input type="submit" value="Login">
</form>

</body>
</html>

