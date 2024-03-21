<?php
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

session_start();


if (isset($_GET['id'])) {
    $client_id = $_GET['id'];

    $query_client = "SELECT * FROM client WHERE client_id = :client_id";
    $stmt = $conn->prepare($query_client);
    $stmt->bindParam(':client_id', $client_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
       
        $remove = "UPDATE client SET is_deleted = 0 WHERE client_id = :client_id";
        $stmt = $conn->prepare($remove);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();
        
        echo "success";
        
    } else {
        echo "error";
    }

   
}






?>
