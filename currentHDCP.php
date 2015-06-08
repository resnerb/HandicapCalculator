<?php
    // Start the session
    session_start();
    //echo "Server parameters: " . json_encode($_SERVER) . "<br>";
    //echo "Server Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
    //echo "Server HTTP Referer: " . $_SERVER['HTTP_REFERER'] . "<br>";

    //echo "Session parameters: " . json_encode($_SESSION) . "<br>";
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
    //Create database if it doesn't already exist
    //$sql = "CREATE DATABASE IF NOT EXISTS " . $database;
    //if ($conn->query($sql) === FALSE)
    //{
    //    die ("Database creation failed: " . $conn->error);
        //TODO Should exit if database can't be created
    //}
    
    // Select the golfHDCP as the default database
    //mysqli_select_db($conn, $database);
    
    // Check if the playerScores table exists in the golfHDCP database
    // If playerScores table does not exist then we know we must create both the
    // playerScores and golfCourses tables
    $sql = "SHOW TABLES IN `". $database . "` WHERE `Tables_in_" . $database . "` = 'playerScores'";
    
    // Perform the query and store the result
    $result = $conn->query($sql);
    echo "Num rows for tables in DB: " . $result->num_rows . "<br>";
    
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
</head>
<body>
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
    echo "Handicap Index and Scores for Player: " . $username . "<br><br>";
    
    echo "<form action='newScoreHDCP.php' method='POST'>";
    echo "<input type='submit' value='Enter New Score'>";
    echo "</form>";

    $sql = "SELECT * FROM playerScores WHERE playerName='$username' ORDER BY datePlayed DESC LIMIT 20";
    $playerResult = $conn->query($sql);
    
    if ($playerResult->num_rows > 0) {
        // Create an array to hold the handicap differentials
        $hdArray = array();
        
        echo "<table border='1'><tr><th>Date Played</th><th>Score</th><th>Par</th><th>Slope</th><th>Rating</th><th>Course</th><th>Handicap Differential</th></tr>";
        // output data of each row
        while($p_data = mysqli_fetch_array($playerResult)) {
            
            $courseName = $p_data['golfCourseName'];
            $sql = "SELECT * FROM golfCourses WHERE name='$courseName'";
            $courseResult = $conn->query($sql);
            
            if ($courseResult->num_rows > 0) {
                $gc_data = mysqli_fetch_array($courseResult);
                
                $hd = ($p_data['score'] - $gc_data['rating']) * 113 / $gc_data['slope'];
                $hdArray[] = $hd;
                
                echo "<tr><td>".$p_data["datePlayed"]."</td><td>".$p_data["score"]."</td><td>".$gc_data["par"]."</td><td>".$gc_data["slope"]."</td><td>".$gc_data["rating"]."</td><td>".$gc_data["name"]."</td><td>".$hd."</td></tr>";
            }
            
            // Remove button was working prior to adding the check in/out button - don't know why
            // the remove button stopped working!
            //$removeButtonText = "<form action='remove_row.php' method='post'><button type='submit' name='removeRowID' value='" . $rid . "'>Remove Movie</button>";
            //$checkButtonText = "<form action='check_availability.php' method='post'><button type='submit' name='checkRowID' value='" . $rid . "'>".$bt."</button>";
            
            
        }
        echo "</table>";
    } else {
        echo "<br>There are no scores entered in the database!<br>";
    }
    $conn->close();
?>
print_r($hdArray);

</body>
</html>
