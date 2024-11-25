<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ./public/signin.php");
    exit(); 
} else {
    header("Location: ./public/admin_dashboard.php");
}
?>