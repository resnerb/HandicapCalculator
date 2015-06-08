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
            // echo "You must be logged in to access this page! You will be redirected to the login page!";
            // TODO Need to figure out how to make the redirection delay so the user can
            // see the above comment about why they are being redirected
            // sleep(3);
            header("Location: loginHDCP.php");
            exit();
        }
    }
    
    //echo "After checking for login currentHDCP<br>";
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
    
    //echo "$_POST variables passed in: " . json_encode($_POST) . "<br>";
    
    // Create variables to submit to table
    if ($_POST['process'] === "addScore")
    {
        // Process adding a new score
        $playerName = $_SESSION['user'];
        $courseName = $_POST['courseName'];
        $score = $_POST['score']+0;
        $datePlayed = $_POST['datePlayed'];
        $sql = "INSERT INTO playerScores (playerName, golfCourseName, score, datePlayed)
            VALUES ('$playerName', '$courseName', '$score', '$datePlayed')";
    
        $conn->query($sql);
        
        //Throws user back to currentHDCP.php
        header("Location: currentHDCP.php");
    }
    else if ($_POST['process'] == "addCourse")
    {
        // Process adding a new course
        $courseName = $_POST['courseName'];
        $slope = $_POST['slope']+0;
        $rating = $_POST['rating']+0;
        $par = $_POST['par']+0;
        $sql = "INSERT INTO golfCourses (name, slope, rating, par)
        VALUES ('$courseName', '$slope', '$rating', '$par')";
        
        $conn->query($sql);
        
        //Throws user back to currentHDCP.php
        header("Location: newScoreHDCP.php");
    }
    
    //Throws user back to currentHDCP.php
    //header("Location: currentHDCP.php");
    exit();
    
?>
