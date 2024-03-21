<?php

include "../pdoaction.php";

if (isset($_GET['id'])) {
    $file_id = $_GET['id'];
    $page = $_GET['url'];

    $query_file = "SELECT * FROM file_upload WHERE file_id = $file_id";
    $result_file = $conn->query($query_file);

    while ($row = $result_file->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['file_id'];
        $client_id = $row['client_id'];
        $name = $row['fileName'];
    }

    $remove = "UPDATE file_upload SET is_deleted = 1 WHERE file_id = $file_id";
    $conn->exec($remove);

    if ($conn) {
        function redirect($location)
        {
            header("location: $location");
        }
    }
    redirect($page);
}
