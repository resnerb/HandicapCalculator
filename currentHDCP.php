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
    //Create database if it doesn't already exist
    $sql = "CREATE DATABASE IF NOT EXISTS " . $database;
    if ($conn->query($sql) === FALSE)
    {
        die ("Database creation failed: " . $conn->error);
        //TODO Should exit if database can't be created
    }
    
    echo "After creatubg database or checking that it exists already in currentHDCP<br>";
    
    // Select the golfHDCP as the default database
    mysqli_select_db($conn, $database);
    
    // Check if the playerScores table exists in the golfHDCP database
    // If playerScores table does not exist then we know we must create both the
    // playerScores and golfCourses tables
    $sql = "SHOW TABLES IN `". $database . "` WHERE `Tables_in_" . $database . "` = 'playerScores'";
    
    // Perform the query and store the result
    $result = $conn->query($sql);
    
    echo "Before checking if tables exist in currentHDCP<br>";
    // If there are no rows returned then the table does not exist
    if ($result->num_rows == 0)
    {
        echo "Just before creating playerscore table currentHDCP<br>";
        // Create playerScore table
        $sql = "CREATE TABLE playerScores (
        playerID INT (5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        playerName VARCHAR (255) NOT NULL,
        golfCourseName VARCHAR (255) NOT NULL,
        score INT (3) UNSIGNED NOT NULL,
        datePlayed DATE NOT NULL
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Table playerScores created successfully<br>";
        } else {
            echo "Error creating table: " . $conn->error;
        }
        // Create golfCourses table
        $sql = "CREATE TABLE golfCourses (
        courseID INT (5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR (255) NOT NULL,
        slope INT (3) UNSIGNED NOT NULL,
        rating FLOAT (4,1) NOT NULL,
        par INT (2) UNSIGNED,
        website VARCHAR (255)
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Table golfCourses created successfully<br>";
        } else {
            echo "Error creating table: " . $conn->error;
        }
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
    if (isset($_SESSION['user']))
    {
        // Make numVisits into an integer by adding a zero to it
        $nv = $_SESSION['numVisits'] + 0;
        if ($nv == 1)
        {
            echo 'Hello ' . $_SESSION['user'] . ' you have visited this site ' . $_SESSION['numVisits'] . ' time before. Click <a href="logoutHDCP.php">here</a> to logout.<br>';
        }
        else
        {
            echo 'Hello ' . $_SESSION['user'] . ' you have visited this site ' . $_SESSION['numVisits'] . ' times before. Click <a href="logoutHDCP.php">here</a> to logout.<br>';
        }
        echo 'If you want to be directed to the content2 page, click <a href="content2.php">here</a>!';
        
    }
    else
    {
        $username = $_POST["username"];
        
        if ($username === '' || !isset($username))
        {
            echo 'A username must be entered. Click <a href="login.php">here</a> to return to the login screen.';
        }
        else
        {
            $_SESSION['user'] = $username;
            $_SESSION['numVisits'] = 0;
            echo 'Hello ' . $_SESSION['user'] . ' you have visited this site ' . $_SESSION['numVisits'] . ' times before. Click <a href="logout.php">here</a> to logout.<br>';
            echo 'If you want to be directed to the content2 page, click <a href="content2.php">here</a>!';
        }
    }
?>

<form action="newScoreHDCP.php">
    <input type="submit" value="Enter New Score">
</form>

<?php
    $sql = "SELECT * FROM playerScores";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>Date Played</th><th>Score</th><th>Par</th><th>Slope</th><th>Rating</th><th>Course</th></tr>";
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $availStatus = "Checked out";
            $bt = "Check In";
            if ($row["availability"])
            {
                $availStatus = "Available";
                $bt = "Check Out";
            }
            
            $rid = $row["id"];
            
            // Remove button was working prior to adding the check in/out button - don't know why
            // the remove button stopped working!
            $removeButtonText = "<form action='remove_row.php' method='post'><button type='submit' name='removeRowID' value='" . $rid . "'>Remove Movie</button>";
            $checkButtonText = "<form action='check_availability.php' method='post'><button type='submit' name='checkRowID' value='" . $rid . "'>".$bt."</button>";
            
            echo "<tr><td>".$checkButtonText."</td><td>".$availStatus."</td><td>".$row["title"]."</td><td>".$row["category"]."</td><td>".$row["minutes"]."</td><td>". $removeButtonText ."</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<br>There are no scores entered in the database!<br>";
    }
    $conn->close();
?>
</body>
</html>
