<?php

include "../pdoaction.php";

if (isset($_GET['id'])) {
    $file_id = $_GET['id'];
    $page = $_GET['url'];

    $archive = "UPDATE file_upload SET is_archive = 1 WHERE file_id = $file_id";
    $conn->exec($archive);

    if ($conn) {
        function redirect($location)
        {
            header("location: $location");
        }
    }
    redirect($page);
}
