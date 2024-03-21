<?php

$files = $conn->prepare("SELECT DISTINCT files.category, categories.category FROM file_upload AS files LEFT JOIN category AS categories ON files.category = categories.category WHERE files.client_id = :client_id AND files.parent_folder = '' AND files.is_archive = 0 AND files.is_deleted = 0 GROUP BY categories.category");
$files->bindParam(':client_id', $client_id);
$files->execute();

$folder = $conn->prepare("SELECT DISTINCT folders.category, categories.category FROM folder_upload AS folders LEFT JOIN category AS categories ON folders.category = categories.category WHERE folders.client_id = :client_id AND folders.parent_folder = '' AND folders.is_archive = 0 AND folders.is_deleted = 0 GROUP BY categories.category;");
$folder->bindParam(':client_id', $client_id);
$folder->execute();

$sameCategory = $conn->prepare("SELECT DISTINCT files.category, folders.category, categories.category FROM file_upload AS files 
LEFT JOIN folder_upload AS folders ON files.category = folders.category
LEFT JOIN category AS categories ON files.category = categories.category OR folders.category = categories.category
WHERE files.client_id = :client_id AND folders.client_id = :client_id AND files.parent_folder = '' AND folders.parent_folder = '' 
AND files.is_archive = 0 AND files.is_deleted = 0 AND folders.is_archive = 0 AND folders.is_deleted = 0
GROUP BY categories.category");
$sameCategory->bindParam(':client_id', $client_id);
$sameCategory->execute();

$folders = $folder->fetchAll(PDO::FETCH_ASSOC);
$files = $files->fetchAll(PDO::FETCH_ASSOC);
$sameCategory = $sameCategory->fetchAll(PDO::FETCH_ASSOC);

if ($folders && $files) {
    $result = array_merge($folders, $files);
    $result = array_unique($result, SORT_REGULAR);
    $result = array_merge($result, $sameCategory);
    $result = array_unique($result, SORT_REGULAR);
    echo '<a class="dropdown-item" href="#">All</a>';
    foreach ($result as $category) {
        echo '<a class="dropdown-item" href="#">' . $category['category'] . '</a>';
    }
} elseif ($folders) {
    echo '<a class="dropdown-item" href="#">All</a>';
    foreach ($folders as $category) {
        echo '<a class="dropdown-item" href="#">' . $category['category'] . '</a>';
    }
} elseif ($files) {
    echo '<a class="dropdown-item" href="#">All</a>';
    foreach ($files as $category) {
        echo '<a class="dropdown-item" href="#">' . $category['category'] . '</a>';
    }
} else {
    echo '<a class="dropdown-item" href="#">No category found</a>';
}

// if ($files->rowCount() > 0 && $folder->rowCount() > 0) {
//     echo '<a class="dropdown-item" href="#">All</a>';
//     foreach ($files as $category) {
//         echo '<a class="dropdown-item" href="#">' . $category['category'] . '</a>';
//     }
//     foreach ($folder as $category) {
//         echo '<a class="dropdown-item" href="#">' . $category['category'] . '</a>';
//     }
// } else if ($files->rowCount() > 0) {
//     echo '<a class="dropdown-item" href="#">All</a>';
//     foreach ($files as $category) {
//         echo '<a class="dropdown-item" href="#">' . $category['category'] . '</a>';
//     }
// } else if ($folder->rowCount() > 0) {
//     echo '<a class="dropdown-item" href="#">All</a>';
//     foreach ($folder as $category) {
//         echo '<a class="dropdown-item" href="#">' . $category['category'] . '</a>';
//     }
// } else {
//     echo "No categories found.";
// }
