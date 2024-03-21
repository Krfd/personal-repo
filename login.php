<?php

session_start();

// Connection for server and database 
$dsn = "mysql:host=localhost;dbname=uiojt2023";
$username = "root";
$password = "";

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$key = htmlspecialchars($_POST['key']);
$token = hash_hmac('sha256', '', $key);

try {
    $stmt = $conn->prepare("SELECT * FROM client WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (hash_equals($token, $_POST['csrfToken'])) {
        if ($stmt->rowCount() == 1) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['client_id'];

                if ($row['is_admin'] == 1) {
                    echo "admin";
                } else if ($row['is_admin'] == 2) {
                    echo "sub_admin";
                } else if ($row['is_deleted'] == 1) {
                    echo "banned";
                } else {
                    echo "valid";
                }
            } else {
                echo "unknown";
            }
        } else {
            echo "notfound";
        }
    } else {
        echo "invalidCSRFToken";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
