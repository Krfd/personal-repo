<?php
include("pdoaction.php");

// Folder Details Function
if (isset($_POST['folder_id'])) {
    $folderId = $_POST['folder_id'];

    var_dump($_POST); // Debugging line

    $stmt = $conn->prepare("SELECT * FROM folder_upload WHERE folder_id = :folder_id");
    $stmt->bindParam(':folder_id', $folderId);
    $stmt->execute();

    var_dump($stmt->errorInfo()); // Debugging line

    if ($stmt->rowCount() > 0) {
        $folderDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        $folderDate = date('M, D, h:i A', strtotime($folderDetails['date']));

        echo '
            <div class="col card shadow-sm p-3 my-1 my-md-3 mr-0 mr-md-1 mr-lg-3 d-flex h-auto w-100" id="cardFile" style="background: hsla(198, 100%, 95%, .5);">
                <i class="fa-solid fa-folder d-block mx-auto display-1 p-4" style="color: gray"></i>
            </div>
            <div>
                <p>Owner: ' . $folderDetails['publisher'] . '</p>
                <p>Name: ' . $folderDetails['folder_name'] . '</p>
                <p>Uploaded: ' . $folderDate . '</p>
            </div>';
    } else {
        echo 'Folder not found';
    }
}
