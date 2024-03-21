<?php

include "pdoaction.php";

// Function to recursively delete a folder and its contents
function deleteFolder($path)
{
    $files = glob($path . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        } elseif (is_dir($file)) {
            deleteFolder($file); // Recursively delete subfolder
        }
    }
    rmdir($path);
}

if (isset($_GET['id']) && isset($_GET['folder']) && isset($_GET['client_id'])) {
    $id = $_GET['id'];
    $folder = $_GET['folder'];
    $client_id = $_GET['client_id'];

    // Fetching parent folder and child folder
    $folder_stmt = $conn->prepare("SELECT * FROM folder_upload WHERE id = :id");
    $folder_stmt->bindParam(':id', $id);
    $folder_stmt->execute();

    // Fetching files 
    $file_stmt = $conn->prepare("SELECT file_id FROM file_upload WHERE parent_folder = :parent_folder OR asc_folder = :asc_folder AND client_id = :client_id");
    $file_stmt->bindParam(':parent_folder', $folder);
    $file_stmt->bindParam(':asc_folder', $folder);
    $file_stmt->bindParam(':client_id', $client_id);
    $file_stmt->execute();

    // Loop through each folder to delete
    while ($delete_folder = $folder_stmt->fetch(PDO::FETCH_ASSOC)) {
        $folder_id = $delete_folder['id'];
        $client_id = $delete_folder['client_id'];
        $folder_name = $delete_folder['folder'];
        $parent_folder = $delete_folder['parent_folder'];

        if ($folder_name != '') {
            $path = "folders/" . ($parent_folder != '' ? $parent_folder . "/" : "") . $folder_name;
            deleteFolder($path);
        }
    }

    // Deleting entries from the database
    $delete_entries_stmt = $conn->prepare("DELETE FROM folder_upload WHERE id = :id OR parent_folder = :folder_name");
    $delete_entries_stmt->bindParam(':id', $id);
    $delete_entries_stmt->bindParam(':folder_name', $folder);
    $delete_entries_stmt->execute();

    $files = $file_stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($files as $child_file) {
        $file_id = $child_file['file_id'];

        $delete_file_stmt = $conn->prepare("DELETE FROM file_upload WHERE file_id = :file_id");
        $delete_file_stmt->bindParam(':file_id', $file_id);
        $delete_file_stmt->execute();
    }

    echo "success";
} else {
    echo "error";
}
