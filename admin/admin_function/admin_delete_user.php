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
        $id = $_GET['id'];
        $sql = "DELETE FROM client WHERE client_id = $id";
        $delete = $conn->query($sql);
        if ($delete) {

            echo "success";

        } else {

            echo "error";     

          }
    }






?>
