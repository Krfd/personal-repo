<?php

include "pdoaction.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query_file = $conn->prepare("SELECT fileName, parent_folder, asc_folder FROM file_upload WHERE file_id = '$id'");
    $query_file->execute();

    while ($row = $query_file->fetch(PDO::FETCH_ASSOC)) {
        $name = $row['fileName'];
        $parent = $row['parent_folder'];
        $asc = $row['asc_folder'];

        if ($parent != '' && $asc != '') {
            $path = "folders/" . $asc . "/" . $parent . "/" . $name;
        } else if ($parent != '') {
            $path = "folders/" . $parent . "/" . $name;
        } else {
            $path = "files_upload/" . $name;
        }
    }
    unlink($path);

    $sql = "DELETE FROM file_upload WHERE file_id = $id";
    $delete = $conn->query($sql);

    echo "success";
} else {
    echo "error";
}
