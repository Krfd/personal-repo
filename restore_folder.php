<?php

include "pdoaction.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $folder = $_GET['folder'];
    $client_id = $_GET['client_id'];

    // Fetching the main folder
    $main_folder = $conn->prepare("SELECT * FROM folder_upload WHERE id = :id AND client_id = :client_id");
    $main_folder->bindParam(':id', $id);
    $main_folder->bindParam(':client_id', $client_id);
    $main_folder->execute();

    if ($main_folder->rowCount() > 0) {
        // Fetching files and folders inside the main folder
        $filter_files = $conn->prepare("SELECT * FROM file_upload WHERE parent_folder = :folder AND client_id = :client_id");
        $filter_files->bindParam(':folder', $folder);
        $filter_files->bindParam(':client_id', $client_id);
        $filter_files->execute();

        $filter_folder = $conn->prepare("SELECT * FROM folder_upload WHERE parent_folder = :folder AND client_id = :client_id");
        $filter_folder->bindParam(':folder', $folder);
        $filter_folder->bindParam(':client_id', $client_id);
        $filter_folder->execute();

        // Loop through each folder inside the main folder
        while ($second_folder = $filter_folder->fetch(PDO::FETCH_ASSOC)) {
            $second_folder_name = $second_folder['folder'];

            // Check if there are files inside the folder
            $check_desc_files = $conn->prepare("SELECT * FROM file_upload WHERE asc_folder = :folder");
            $check_desc_files->bindParam(':folder', $folder);
            $check_desc_files->execute();

            if ($check_desc_files->rowCount() > 0) {
                // Restore descendant files
                $remove_desc_files = $conn->prepare("UPDATE file_upload SET is_deleted = 0, deleted_parent_folder = 0 WHERE asc_folder = :folder");
                $remove_desc_files->bindParam(':folder', $folder);
                $remove_desc_files->execute();

                // Restore child folder
                $remove_child_folder = $conn->prepare("UPDATE folder_upload SET is_deleted = 0, deleted_parent_folder = 0 WHERE parent_folder = :folder");
                $remove_child_folder->bindParam(':folder', $folder);
                $remove_child_folder->execute();
            } else {
                // Restore child folder only
                $remove_child_folder = $conn->prepare("UPDATE folder_upload SET is_deleted = 0, deleted_parent_folder = 0 WHERE parent_folder = :folder");
                $remove_child_folder->bindParam(':folder', $folder);
                $remove_child_folder->execute();

                // Restore the folder only
                $remove_folder = $conn->prepare("UPDATE folder_upload SET is_deleted = 0 WHERE id = :id");
                $remove_folder->bindParam(':id', $id);
                $remove_folder->execute();
            }
        }

        // Restore child files
        $remove_child_files = $conn->prepare("UPDATE file_upload SET is_deleted = 0, deleted_parent_folder = 0 WHERE parent_folder = :folder");
        $remove_child_files->bindParam(':folder', $folder);
        $remove_child_files->execute();

        // Restore the main folder
        $remove_folder = $conn->prepare("UPDATE folder_upload SET is_deleted = 0 WHERE id = :id");
        $remove_folder->bindParam(':id', $id);
        $remove_folder->execute();

        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}
