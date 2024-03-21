<?php

include "connect.php";

$publisher = htmlspecialchars($_POST['publisher']);
$client_id = htmlspecialchars($_POST['client_id']);
$folder = htmlspecialchars($_POST['folder']);
$newFolder = htmlspecialchars($_POST['newFolder']);
$category = htmlspecialchars($_POST['folderCategory']);
$othersFolderCategory = isset($_POST['otherFolderCategory']) ? htmlspecialchars($_POST['otherFolderCategory']) : null;

// Parent Folder
$parent_folder = htmlspecialchars($_POST['parent_folder']) ? htmlspecialchars($_POST['parent_folder']) : null;

$rootFolder = "../folders/";
$counter = array_diff(scandir($rootFolder), array('..', '.'));
$count = count($counter);

$parent = "../folders/" . $parent_folder . "/";
$counterParent = array_diff(scandir($parent), array('..', '.'));
$countParent = count($counterParent);

if ($countParent > 0) {
    if (in_array($newFolder, $counterParent)) {
        $newFolder = $newFolder . "(" . $countParent . ")";
    } else {
        $newFolder = $newFolder;
    }
} else {
    $newFolder = $newFolder;
}

$key = htmlspecialchars($_POST['key']);
$token = hash_hmac('sha256', 'Create Folder', $key);

if (hash_equals($token, $_POST['csrfToken'])) {

    if (!is_dir($rootFolder . "/" . $parent_folder . "/" . $newFolder)) {
        mkdir($rootFolder . "/" . $parent_folder . "/" . $newFolder);
    }

    // Check if the directory exists or has been created
    if (is_dir($rootFolder)) {
        // Folder created successfully or already exists
        if ($category == 'Others' && !empty($othersFolderCategory)) {
            $stmt = $conn->prepare("INSERT INTO folder_upload (publisher, category, client_id, folder_name, folder, parent_folder) VALUES (:publisher, :category, :client_id, :folder_name, :folder, :parent_folder)");
            $stmt->bindParam(':publisher', $publisher);
            $stmt->bindParam(':category', $othersFolderCategory);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':folder_name', $newFolder);
            $stmt->bindParam(':folder', $newFolder);
            $stmt->bindParam(':parent_folder', $parent_folder);
            $stmt->execute();

            $insertCategory = $conn->prepare("INSERT INTO category (category) VALUES (:category)");
            $insertCategory->bindParam(':category', $othersFolderCategory);
            $insertCategory->execute();
        } else {
            $stmt = $conn->prepare("INSERT INTO folder_upload (publisher, category, client_id, folder_name, folder, parent_folder) VALUES (:publisher, :category, :client_id, :folder_name, :folder, :parent_folder)");
            $stmt->bindParam(':publisher', $publisher);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':folder_name', $newFolder);
            $stmt->bindParam(':folder', $newFolder);
            $stmt->bindParam(':parent_folder', $parent_folder);
            $stmt->execute();

            $insertCategory = $conn->prepare("INSERT INTO category (category) VALUES (:category)");
            $insertCategory->bindParam(':category', $category);
            $insertCategory->execute();
        }
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid";
}
