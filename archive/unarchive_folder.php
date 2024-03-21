<?php

include "../pdoaction.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $page = $_GET['url'];
    $folder = $_GET['folder'];
    $client_id = $_GET['client_id'];

    // Fetching and filter folders inside a certain folder
    $main_folder = $conn->prepare("SELECT * FROM folder_upload WHERE folder = '$folder' AND client_id = '$client_id'");
    $main_folder->execute();

    // Fetching and filter files inside a certain folder
    // $filter_files = $conn->prepare("SELECT * FROM file_upload WHERE asc_folder = '$folder' OR parent_folder = '$folder' AND client_id = :client_id");
    $filter_files = $conn->prepare("SELECT * FROM file_upload WHERE parent_folder = '$folder' AND client_id = '$client_id'");
    $filter_files->execute();

    // Fetching filter folders inside a certain folder
    $filter_folder = $conn->prepare("SELECT * FROM folder_upload WHERE parent_folder = '$folder' AND client_id = '$client_id'");
    $filter_folder->execute();

    // query if there are files and folders inside the main folder
    if ($main_folder->rowCount() > 0) {

        while ($main = $main_folder->fetch(PDO::FETCH_ASSOC)) {

            // Check if there are files or folder inside the main folder
            if ($filter_files->rowCount() > 0 || $filter_folder->rowCount() > 0) {

                // Check if there are folders inside the main folder
                while ($second_folder = $filter_folder->fetch(PDO::FETCH_ASSOC)) {

                    $check_desc_files = $conn->prepare("SELECT * FROM file_upload WHERE asc_folder = '$folder'");
                    $check_desc_files->execute();

                    if ($check_desc_files->rowCount() > 0) {

                        // Removing descendant files
                        $remove_desc_files = $conn->prepare("UPDATE file_upload SET is_archive = 0, archive_parent_folder = 0 WHERE asc_folder = '$folder'");
                        $remove_desc_files->execute();

                        // Removing child folder
                        $remove_child_folder = $conn->prepare("UPDATE folder_upload SET is_archive = 0, archive_parent_folder = 0 WHERE parent_folder = '$folder'");
                        $remove_child_folder->execute();
                    } else {
                        // Removing child folder
                        $remove_child_folder = $conn->prepare("UPDATE folder_upload SET is_archive = 0, archive_parent_folder = 0 WHERE parent_folder = '$folder'");
                        $remove_child_folder->execute();

                        // Removing the folder only
                        $remove_folder = $conn->prepare("UPDATE folder_upload SET is_archive = 0, archive_parent_folder = 0 WHERE id = '$id'");
                        $remove_folder->execute();
                    }
                }

                // Removing child files
                $remove_child_files = $conn->prepare("UPDATE file_upload SET is_archive = 0, archive_parent_folder = 0 WHERE parent_folder = '$folder'");
                $remove_child_files->execute();

                // Removing the folder only
                $remove_folder = $conn->prepare("UPDATE folder_upload SET is_archive = 0 , archive_parent_folder = 0 WHERE id = '$id'");
                $remove_folder->execute();
            } else {
                // Removing the folder only
                $remove_folder = $conn->prepare("UPDATE folder_upload SET is_archive = 0, archive_parent_folder = 0 WHERE id = '$id'");
                $remove_folder->execute();
            }
        }
    }

    if ($conn) {
        function redirect($location)
        {
            header("location: $location");
        }
    }
    redirect($page);
}
