<?php
session_start();
session_unset();      // Here, Remove all session variables
session_destroy();    // Destroy the session are here 

header("Location: index.php"); // Redirect to login page
exit();
