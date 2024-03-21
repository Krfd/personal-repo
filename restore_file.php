<?php

include "pdoaction.php";

if (isset($_GET['id'])) {
    $file_id = $_GET['id'];

    // Query for restoring file
    $query_file = "SELECT * FROM file_upload WHERE file_id = $file_id";
    $result_file = $conn->query($query_file);

    while ($row = $result_file->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['file_id'];
        $client_id = $row['client_id'];
        $name = $row['fileName'];
        $category = $row['category'];
    }

    $restore = "UPDATE file_upload SET is_deleted = 0 WHERE file_id = $file_id";
    $conn->exec($restore);



    echo "success";
} else {
    echo "error";
}
