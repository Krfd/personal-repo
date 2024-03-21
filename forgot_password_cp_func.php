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

$new_password = htmlspecialchars($_POST['new_password']);
$confirm_password = htmlspecialchars($_POST['confirm_password']);
$client_id = htmlspecialchars($_POST['client_id']);
$key = htmlspecialchars($_POST['key']);
$token = hash_hmac('sha256', '', $key);

try {
    $stmt = $conn->prepare("SELECT * FROM client WHERE client_id = :client_id");
    $stmt->bindParam(':client_id', $client_id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (hash_equals($token, $_POST['csrfToken'])) {
        if ($stmt->rowCount() == 1) {
            if ($new_password === $confirm_password) { 
                $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
                $update_password = "UPDATE client SET password = :password WHERE client_id = :client_id";
                $stmt = $conn->prepare($update_password);
                $stmt->bindParam(':password', $hashed_new_password);
                $stmt->bindParam(':client_id', $client_id);
                $stmt->execute();

                echo "successfully";
            } else {
                echo "unmatch";
            }
        } else {
            echo "not_found";
        }
    } else {
        echo "invalidCSRFToken";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
