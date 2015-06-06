<?php
    // Start the session
    session_start();
    session_destroy();
    header("location: loginHDCP.php");
    exit();
    ?>
