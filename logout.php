<?php

session_start();

// Logout function
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("location: index.html");
    exit();
}
