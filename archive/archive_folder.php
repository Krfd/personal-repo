<?php

include "../pdoaction.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $page = $_GET['url'];
    $folder = $_GET['folder'];
    $client_id = $_GET['client_id'];

    try {
        $conn->beginTransaction();

        $archive_folder_stmt = $conn->prepare("UPDATE folder_upload SET is_archive = 1, archive_parent_folder = 1 WHERE id = :id AND folder = :folder AND client_id = :client_id");
        $archive_folder_stmt->bindParam(':id', $id);
        $archive_folder_stmt->bindParam(':folder', $folder);
        $archive_folder_stmt->bindParam(':client_id', $client_id);
        $archive_folder_stmt->execute();

        // Archive associated files
        $archive_files_stmt = $conn->prepare("UPDATE file_upload SET is_archive = 1, archive_parent_folder = 1 WHERE parent_folder = :folder AND client_id = :client_id");
        $archive_files_stmt->bindParam(':folder', $folder);
        $archive_files_stmt->bindParam(':client_id', $client_id);
        $archive_files_stmt->execute();

        // Archive associated child folders
        $archive_child_folders_stmt = $conn->prepare("UPDATE folder_upload SET is_archive = 1 WHERE parent_folder = :folder AND client_id = :client_id");
        $archive_child_folders_stmt->bindParam(':folder', $folder);
        $archive_child_folders_stmt->bindParam(':client_id', $client_id);
        $archive_child_folders_stmt->execute();

        // Archive associated descendant files
        $archive_descendant_files_stmt = $conn->prepare("UPDATE file_upload SET is_archive = 1, archive_parent_folder = 1 WHERE asc_folder = :folder AND client_id = :client_id");
        $archive_descendant_files_stmt->bindParam(':folder', $folder);
        $archive_descendant_files_stmt->bindParam(':client_id', $client_id);
        $archive_descendant_files_stmt->execute();

        $conn->commit();

        function redirect($location)
        {
            header("location: $location");
        }

        redirect($page);
    } catch (PDOException $e) {
        // Rollback the transaction on error
        $conn->rollBack();
        echo "error: " . $e->getMessage();
    }
} else {
    echo "error";
}
