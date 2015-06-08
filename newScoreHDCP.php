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
    
    //$servername = "oniddb.cws.oregonstate.edu";
    //$username = "resnerb-db";
    //$password = "7qKnFUFXqMYOmsTZ";
    //$database = "resnerb-db";
    $servername = "localhost";
    $username = "root";
    $password = "resnerb";
    $database = "golfHDCP";
    
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
</head>
<body>
<h1>Post a New Score</h1>

<?php
    echo "Entering scores for player: " . $_SESSION['user'] . "<br><br>";

    //Use golfCourses table
    $sql = "SELECT * FROM golfCourses";
    $result = $conn->query($sql);
    
    echo "Number of rows in golfCourses table: " . $result->num_rows . "<br>";
    
    if ($result->num_rows > 0) {
        
        echo "<form method='post' action='process.php'>";
        echo "<input type='hidden' name='process' value='addScore'>";
        
        echo "Date Played [YYYY-MM-DD]:<br>";
        echo "<input type='text' name='datePlayed'>";
        echo "<br>";
        echo "Score:<br>";
        echo "<input type='text' name='score'>";
        echo "<br>";
        echo "Course Name:<br>";
        echo "<select name='courseName'>";
        
        while ($row = mysqli_fetch_array($result)) {
            $courseName = $row['name'];
            
            echo "<option value='" . $courseName . "'>" . $courseName . "</option>";
        }
        echo "</select>";
        
        echo "<br>";
        echo "<br>";

        echo "<input type='submit' value='Submit Score'>";
        echo "</form>";
        
    }
    else {
        echo "There are no golf courses in the database. You must enter golf course information before entering a new score.";
    }
?>

<form method="post" action="newCourseHDCP.php">
<br>
<input type="submit" value="Add New Course">
</form>

</body>
</html>