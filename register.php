<?php

session_start();
// Connection for server and database
$dsn = "mysql:host=localhost;dbname=uiojt2023";
$username = "root";
$password = "";

try {
    $conn = new PDo($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed " . $e->getMessage());
}

// Create account function 
$fullname = htmlspecialchars($_POST['fullname']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$password = htmlspecialchars($_POST['password']);
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$confirm = htmlspecialchars($_POST['confirm']);
// $newDATE = date('Y F d', strtotime($date));
if ($password == $confirm) {
    // Check if the email already exists
    try {
        $stmt = $conn->prepare("SELECT * FROM client WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            echo "emailExist";
        } else {
            $insertQuery = "INSERT INTO client (fullname,email, phone, password) VALUES (:fullname, :email, :phone, :password)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();

            echo "true";
        }
    } catch (PDOException $e) {

        echo "Error: " . $e->getMessage();
    }
} else {

    echo "unmatched";
}
