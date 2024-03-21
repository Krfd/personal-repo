<?php

include "connect.php";

$publisher = htmlspecialchars($_POST['publisher']);
$client_id = htmlspecialchars($_POST['client_id']);
$newFolder = htmlspecialchars($_POST['folder']);
$category = htmlspecialchars($_POST['folderCategory']);
$othersFolderCategory = isset($_POST['otherFolderCategory']) ? htmlspecialchars($_POST['otherFolderCategory']) : null;

$rootFolder = "../folders/";

$counter = array_diff(scandir($rootFolder), array('..', '.'));
// Counts the number of folders with same name
$count = count($counter);

// if ($count > 0) {
if ($count > 0) {
    if (in_array($newFolder, $counter)) {
        $newFolder = $newFolder . "(" . $count . ")";
    } else {
        $newFolder = $newFolder;
    }
} else {
    $newFolder = $newFolder;
}

$key = htmlspecialchars($_POST['key']);
$token = hash_hmac('sha256', 'Create Folder', $key);

if (hash_equals($token, $_POST['csrfToken'])) {

    if (!is_dir($rootFolder . "/" . $newFolder)) {
        mkdir($rootFolder . "/" . $newFolder);
    }

    // Check if the directory exists or has been created
    if (is_dir($rootFolder)) {
        // Folder created successfully or already exists
        if ($category == 'Others' && !empty($othersFolderCategory)) {
            $stmt = $conn->prepare("INSERT INTO folder_upload (publisher, category, client_id, folder_name, folder) VALUES (:publisher, :category, :client_id, :folder_name, :folder)");
            $stmt->bindParam(':publisher', $publisher);
            $stmt->bindParam(':category', $othersFolderCategory);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':folder_name', $newFolder);
            $stmt->bindParam(':folder', $newFolder);
            $stmt->execute();

            $cat = $conn->prepare("SELECT category FROM category");
            $cat->execute();

            if ($cat->rowCount() == 0) {
                $insertCategory = $conn->prepare("INSERT INTO category (category) VALUES (:category)");
                $insertCategory->bindParam(':category', $othersFolderCategory);
                $insertCategory->execute();
            } else {
                while ($res = $cat->fetch(PDO::FETCH_ASSOC)) {
                    $result = $res['category'];
                }
                if ($othersFolderCategory != $result) {
                    $insertCategory = $conn->prepare("INSERT INTO category (category) VALUES (:category)");
                    $insertCategory->bindParam(':category', $othersFolderCategory);
                    $insertCategory->execute();
                }
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO folder_upload (publisher, category, client_id, folder_name, folder) VALUES (:publisher, :category, :client_id, :folder_name, :folder)");
            $stmt->bindParam(':publisher', $publisher);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':folder_name', $newFolder);
            $stmt->bindParam(':folder', $newFolder);
            $stmt->execute();

            $cat = $conn->prepare("SELECT category FROM category");
            $cat->execute();

            if ($cat->rowCount() == 0) {
                $insertCategory = $conn->prepare("INSERT INTO category (category) VALUES (:category)");
                $insertCategory->bindParam(':category', $category);
                $insertCategory->execute();
            } else {
                while ($res = $cat->fetch(PDO::FETCH_ASSOC)) {
                    $result = $res['category'];
                }
                if ($category != $result) {
                    $insertCategory = $conn->prepare("INSERT INTO category (category) VALUES (:category)");
                    $insertCategory->bindParam(':category', $category);
                    $insertCategory->execute();
                }
            }
        }

        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid";
}
