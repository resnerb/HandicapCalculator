<?php
    // Start the session
    session_start();
    
    if(!isset($_SESSION['user']))
    {
        // We have been assured that the only way we will get to this page with a
        // request method of POST is via the login.php page
        if ($_SERVER['REQUEST_METHOD'] !== "POST")
        {
            // Since the user is not correctly logged in, then redirect back to login page
            //sleep(3);
            header("Location: loginHDCP.php");
            exit();
        }
    }
    
    $servername = "oniddb.cws.oregonstate.edu";
    $username = "resnerb-db";
    $password = "7qKnFUFXqMYOmsTZ";
    $database = "resnerb-db";
    //$servername = "localhost";
    //$username = "root";
    //$password = "resnerb";
    //$database = "golfHDCP";
    
    $conn = new mysqli($servername, $username, $password);
    //Check if connection works
    if ($conn->connect_error)
    {
        die ("Connection failed: " . $conn->connect_error);
    }
    //Create database if it doesn't already exist
    //$sql = "CREATE DATABASE IF NOT EXISTS " . $database;
    //if ($conn->query($sql) === FALSE)
    //{
    //    die ("Database creation failed: " . $conn->error);
        //TODO Should exit if database can't be created
    //}
    
    // Select the golfHDCP as the default database
    mysqli_select_db($conn, $database);
    
    // Check if the playerScores table exists in the golfHDCP database
    // If playerScores table does not exist then we know we must create both the
    // playerScores and golfCourses tables
    $sql = "SHOW TABLES IN `". $database . "` WHERE `Tables_in_" . $database . "` = 'playerScores'";
    
    // Perform the query and store the result
    $result = $conn->query($sql);
    
    // If there are no rows returned then the table does not exist
    if ($result->num_rows == 0)
    {
        // Create playerScore table
        $sql = "CREATE TABLE playerScores (
        playerID INT (5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        playerName VARCHAR (255) NOT NULL,
        golfCourseName VARCHAR (255) NOT NULL,
        score INT (3) UNSIGNED NOT NULL,
        datePlayed DATE NOT NULL
        )";
        
        $conn->query($sql);
        
        // Create golfCourses table
        $sql = "CREATE TABLE golfCourses (
        courseID INT (5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR (255) NOT NULL,
        slope INT (3) UNSIGNED NOT NULL,
        rating FLOAT (4,1) NOT NULL,
        par INT (2) UNSIGNED,
        website VARCHAR (255)
        )";
        
        $conn->query($sql);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>CurrentHDCP</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1 id="header">Computing Handicap Index for Displayed Scores</h1><br><br>

<form align='center' action='newScoreHDCP.php' method='POST'>
    <input type='submit' style='font-family: Courier New, monospace; font-size: 30px;' value='Enter New Score'>
</form><br><br>

<?php
    if (!isset($_SESSION['user']))
    {
        $username = $_POST["username"];
        
        if ($username === '' || !isset($username))
        {
            echo 'A username must be entered. Click <a href="loginHDCP.php">here</a> to return to the login screen.';
        }
        else
        {
            $_SESSION['user'] = $username;
        }
    }
    
    $username = $_SESSION['user'];
    

    $sql = "SELECT * FROM playerScores WHERE playerName='$username' ORDER BY datePlayed DESC LIMIT 20";
    $playerResult = $conn->query($sql);
    
    if ($playerResult->num_rows > 0) {
        // Create an array to hold the handicap differentials
        $hdArray = array();
        
        echo "<table id='table1'><tr><th>Date Played</th><th>Score</th><th>Par</th><th>Slope</th><th>Rating</th><th>Course</th><th>Handicap Differential</th></tr>";
        // output data of each row
        while($p_data = mysqli_fetch_array($playerResult)) {
            
            $courseName = $p_data['golfCourseName'];
            $sql = "SELECT * FROM golfCourses WHERE name='$courseName'";
            $courseResult = $conn->query($sql);
            
            if ($courseResult->num_rows > 0) {
                $gc_data = mysqli_fetch_array($courseResult);
                
                $hd = ($p_data['score'] - $gc_data['rating']) * 113 / $gc_data['slope'];
                $hdArray[] = $hd;
                
                echo "<tr><td>".$p_data["datePlayed"]."</td><td>".$p_data["score"]."</td><td>".$gc_data["par"]."</td><td>".$gc_data["slope"]."</td><td>".$gc_data["rating"]."</td><td>".$gc_data["name"]."</td><td>". round($hd,1)."</td></tr>";
            }
            
        }
        echo "</table>";
        
        sort($hdArray);
        $numScores = count($hdArray);
        $numHD = 10;
        
        if ($numScores < 5) {
            $numHD = 0;
            echo "<br><p id='header'>Not enough rounds to compute handicap index. Need minimum of five rounds to establish a handicap.</p><br>";
        }
        elseif ($numScores < 7) {
            $numHD = 1;
        }
        elseif ($numScores < 9) {
            $numHD = 2;
        }
        elseif ($numScores < 11) {
            $numHD = 3;
        }
        elseif ($numScores < 13) {
            $numHD = 4;
        }
        elseif ($numScores < 15) {
            $numHD = 5;
        }
        elseif ($numScores < 17) {
            $numHD = 6;
        }
        elseif ($numScores == 17) {
            $numHD = 7;
        }
        elseif ($numScores == 18) {
            $numHD = 8;
        }
        elseif ($numScores == 19) {
            $numHD = 9;
        }
        
            
        if ($numHD > 0) {
             //Set avg handicap differential to zero
             $avgHD = 0;
             //Sum all handicap differentials based on number of rounds played
             for ($i = 0; $i < $numHD; $i++) {
                 $avgHD += $hdArray[$i];
             }
             //Compute avg handicap differential
             $avgHD = $avgHD/$numHD;
             //Multiply by 0.96 to get the handicap index
             $HDCP_index = $avgHD * 0.96;
             echo "<h2 id='header'>Current Handicap Index: " . round($HDCP_index, 1) . "</h2><br>";
        }
        
    } else {
        echo "<br><p id='header'>There are no scores entered in the database!</p><br>";
    }
    $conn->close();
?>

<form align='center' action='logoutHDCP.php' method='POST'>
    <input type='submit' style='font-family: Courier New, monospace; font-size: 30px;' value='Logout'>
</form><br><br>

</body>
</html>
