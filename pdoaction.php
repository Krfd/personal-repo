<?php

// Connection for server and database
$dsn = "mysql:host=localhost;dbname=uiojt2023";
$username = "root";
$password = "";

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start();

// Function to redirect
function redirectTo($location)
{
    header("Location: $location");
    exit();
}

// Profile update function
if (isset($_POST['edit'])) {
    $client_id = htmlspecialchars($_POST['client_id']);
    $fullname = htmlspecialchars($_POST['updatefullname']);
    $email = htmlspecialchars($_POST['updateemail']);
    $phone = htmlspecialchars($_POST['updatephone']);
    $password = htmlspecialchars($_POST['updatepassword']);

    $type = "Document";
    $updateProfile = $_FILES['updateProfile']['name'];
    $fileTmpName = $_FILES['updateProfile']['tmp_name'];
    $fileExt = explode('.', $updateProfile);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg', 'jpeg', 'png', 'svg');


    try {
        // Update works but error in condition
        if (in_array($fileActualExt, $allowed)) {

            $newProfile = $updateProfile;
            $dir_path = "profile/" . $updateProfile;

            $updateQuery = "UPDATE client SET fullname = :fullname, email = :email, profile = :profile, phone = :phone, password = :password WHERE client_id = :client_id";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':profile', $newProfile);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            move_uploaded_file($fileTmpName, $dir_path);

            redirectTo('storage.php');
        } else {

            $newProfile = "";
            $dir_path = "profile/" . $updateProfile;

            $updateQuery = "UPDATE client SET fullname = :fullname, email = :email, profile = :profile, phone = :phone, password = :password WHERE client_id = :client_id";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':profile', $newProfile);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            move_uploaded_file($fileTmpName, $dir_path);

            redirectTo('storage.php');
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Add File function
if (isset($_POST['addFile'])) {
    $publisher = htmlspecialchars($_POST['publisher']);
    $client_id = htmlspecialchars($_POST['client_id']);
    $category = htmlspecialchars($_POST['category']);
    $othersInput = isset($_POST['othersInput']) ? htmlspecialchars($_POST['othersInput']) : null;

    // Variable for returning to the page
    $back = $_POST['return'];

    // Folder handling
    $folder = htmlspecialchars($_POST['folder']) ? htmlspecialchars($_POST['folder']) : '';
    $parent_folder = htmlspecialchars($_POST['parent_folder']) ? htmlspecialchars($_POST['parent_folder']) : '';
    $asc_folder = htmlspecialchars($_POST['asc_folder']) ? htmlspecialchars($_POST['asc_folder']) : '';

    $fileName = $_FILES['fileName']['name'][0];
    $fileTmpName = $_FILES['fileName']['tmp_name'][0];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed = array('jpg', 'jpeg', 'png', 'svg', 'txt', 'pdf', 'docx', 'doc', 'pptx', 'xlsx', 'xlr');

    // CSRF token
    $key = htmlspecialchars($_POST['key']);
    $token = hash_hmac('sha256', 'This is from a form', $key);

    if (hash_equals($token, $_POST['csrfToken'])) {
        if (in_array($fileExt, $allowed)) {
            try {
                // $uploadDir = "";
                if ($folder != '' && $asc_folder != '') {
                    $uploadDir = "folders/" . $asc_folder . "/" . $folder . "/";
                } else if ($folder != '') {
                    $uploadDir = "folders/" . $folder . "/";
                } else {
                    $uploadDir = "files_upload/";
                }

                for ($i = 0; $i < count($_FILES['fileName']['tmp_name']); $i++) {
                    // File handling
                    $fileName = $_FILES['fileName']['name'][$i];
                    $fileTmpName = $_FILES['fileName']['tmp_name'][$i];
                    $fileExtension = explode('.', $fileName);
                    $fileActualExt = strtolower(end($fileExtension));
                    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                    $allowed = array('jpg', 'jpeg', 'png', 'svg', 'txt', 'pdf', 'docx', 'doc', 'pptx', 'xlsx', 'xlr');

                    $newFileName = explode('.', $_FILES['fileName']['name'][$i]);
                    $newName = $newFileName[0];
                    $newExt = $newFileName[1];

                    // Scan each directory for duplicated files
                    $counter = array_diff(scandir($uploadDir), array('..', '.'));
                    // Counts the number of files on each directory
                    $count = count($counter);

                    if ($count > 0) {
                        if (in_array($fileName, $counter)) {
                            $newFileName = $newName . "(" . $count . ")" . "." . $newExt;
                        } else {
                            $newFileName = $newName . "." . $newExt;
                        }
                    } else {
                        $newFileName = $newName . "." . $newExt;
                    }

                    $filePath = $uploadDir . $newFileName;

                    // Insert data into the database
                    if ($category == 'Others' && !empty($othersInput)) {
                        $stmt = $conn->prepare("INSERT INTO file_upload (publisher, title, client_id, category, fileName, extension, folder, parent_folder, asc_folder) VALUES (:publisher, :title, :client_id, :category, :fileName, :extension, :folder, :parent_folder, :asc_folder)");
                        $stmt->bindParam(':publisher', $publisher);
                        $stmt->bindParam(':title', $newFileName);
                        $stmt->bindParam(':client_id', $client_id);
                        $stmt->bindParam(':category', $othersInput);
                        $stmt->bindParam(':fileName', $newFileName);
                        $stmt->bindParam(':extension', $newExt);
                        $stmt->bindParam(':folder', $folder);
                        $stmt->bindParam(
                            ':parent_folder',
                            $parent_folder
                        );
                        $stmt->bindParam(':asc_folder', $asc_folder);
                        $stmt->execute();

                        $cat = $conn->prepare("SELECT category FROM category");
                        $cat->execute();

                        if ($cat->rowCount() == 0) {
                            $insertCategory = $conn->prepare("INSERT INTO category (category) VALUES (:category)");
                            $insertCategory->bindParam(':category', $othersInput);
                            $insertCategory->execute();
                        } else {
                            while ($res = $cat->fetch(PDO::FETCH_ASSOC)) {
                                $result = $res['category'];
                            }
                            if ($othersInput != $result) {
                                $insertCategory = $conn->prepare("INSERT INTO category (category) VALUES (:category)");
                                $insertCategory->bindParam(':category', $othersInput);
                                $insertCategory->execute();
                            }
                        }
                    } else {
                        $stmt = $conn->prepare("INSERT INTO file_upload (publisher, title, client_id, category, fileName, extension, folder, parent_folder, asc_folder) VALUES (:publisher, :title, :client_id, :category, :fileName, :extension, :folder, :parent_folder, :asc_folder)");
                        $stmt->bindParam(':publisher', $publisher);
                        $stmt->bindParam(':title', $newFileName);
                        $stmt->bindParam(':client_id', $client_id);
                        $stmt->bindParam(':category', $category);
                        $stmt->bindParam(':fileName', $newFileName);
                        $stmt->bindParam(':extension', $newExt);
                        $stmt->bindParam(':folder', $folder);
                        $stmt->bindParam(
                            ':parent_folder',
                            $parent_folder
                        );
                        $stmt->bindParam(':asc_folder', $asc_folder);
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

                    move_uploaded_file($fileTmpName, $filePath);
                }



                // Function the returns to the page
                function back($back)
                {
                    header("Location: $back");
                }
                back($back);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        echo "CSRF Token is invalid";
        function back($back)
        {
            header("Location: $back");
        }
        back($back);
    }
    // Function the returns to the page
    function back($back)
    {
        header("Location: $back");
    }
    back($back);
}

// Function for rename file
if (isset($_POST['saveRename'])) {
    $renameFile = htmlspecialchars($_POST['rename']) ? htmlspecialchars($_POST['rename']) : null;
    $uploaded = htmlspecialchars($_POST['uploaded']);
    $updated = htmlspecialchars($_POST['updated']) ? htmlspecialchars($_POST['updated']) : null;
    $file_id = $_POST['file_id'];
    $currentFile = htmlspecialchars($_POST['current']);
    $extension = $_POST['extension'];

    $parent = htmlspecialchars($_POST['parent']);
    $asc = htmlspecialchars($_POST['asc']);

    // Variable for returning to the page
    $back = $_POST['return'];

    // CSRF token
    $key = htmlspecialchars($_POST['key']);
    $token = hash_hmac('sha256', 'This is from a form', $key);

    $name = pathinfo($renameFile, PATHINFO_FILENAME);

    if (hash_equals($token, $_POST['csrfToken'])) {

        // Directory path
        if ($parent != '' && $asc != '') {
            $path = "folders/" . $asc . "/" . $parent . "/";
        } else if ($parent != '') {
            $path = "folders/" . $parent . "/";
        } else {
            $path = "files_upload/";
        }

        $newFileName = str_replace(' ', ' ', $renameFile);

        $newFile = $newFileName . "." . $extension;

        $current = pathinfo($newFileName, PATHINFO_FILENAME);

        $count = 0;
        if (file_exists('files_upload/' . $newFile)) {

            $filterFile = $conn->prepare("SELECT COUNT(fileName) FROM file_upload WHERE fileName LIKE '$name%'");
            $filterFile->execute();

            while ($count < $filterFile->rowCount()) {
                $count = $filterFile->rowCount();
                $newFileName = $current . "($count)" . "." . $extension;
            }
        } else {
            $newFileName = $current . "." . $extension;
        }
        rename($path . $currentFile, $path . $newFileName);

        $rename = $conn->prepare("UPDATE file_upload SET title = :title, fileName = :fileName, uploaded = :uploaded, updated = :updated WHERE file_id = :file_id");
        $rename->bindParam(':title', $newFile);
        $rename->bindParam(':fileName', $newFile);
        $rename->bindParam(':uploaded', $uploaded);
        $rename->bindParam(':updated', $updated);
        $rename->bindParam(':file_id', $file_id);
        $rename->execute();
    } else {
        echo "CSRF Token is invalid";
        function back($back)
        {
            header("Location: $back");
        }
        back($back);
    }

    function back($back)
    {
        header("Location: $back");
    }
    back($back);
}

// Session id function
if (!isset($_SESSION['id'])) {
    redirectTo('index.php');
}

// File Details Function
if (isset($_POST['file_id'])) {
    $fileId = $_POST['file_id'];

    $stmt = $conn->prepare("SELECT * FROM file_upload WHERE file_id = :file_id");
    $stmt->bindParam(':file_id', $fileId);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $fileDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        $date = date('M. d, Y. h:i A - D', strtotime($fileDetails['uploaded']));
        $modified = date('M. d, Y. h:i A - D', strtotime($fileDetails['updated']));

        if ($modified == $date) {
            $modified = "No modification";
        }

        $parent = $fileDetails['parent_folder'];
        $asc = $fileDetails['asc_folder'];

        $parent = str_replace(" ", "%20", $parent);
        $asc = str_replace(" ", "%20", $asc);

        $fileDetails['fileName'] = str_replace(" ", "%20", $fileDetails['fileName']);

        // Slowing down the process

        if ($parent != '' && $asc != '') {
            $path = "folders/" . $asc . "/" . $parent . "/" . $fileDetails['fileName'];
        } else if ($parent != '') {
            $path = "folders/" . $parent . "/" . $fileDetails['fileName'];
        } else {
            $path = "files_upload/" . $fileDetails['fileName'];
        }

        $extension = pathinfo($fileDetails['fileName'], PATHINFO_EXTENSION);
        switch ($extension) {
            case "jpg":
                echo '<img src=' . $path . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-3 mb-3" alt=' . $fileDetails['title'] . ' style="max-width: auto; max-height: 300px;">';
                break;
            case "png":
                echo '<img src=' . $path . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-3 mb-3" alt=' . $fileDetails['title'] . ' style="max-width: auto; max-height: 300px;">';
                break;
            case "jpeg":
                echo '<img src=' . $path . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-3 mb-3" alt=' . $fileDetails['title'] . ' style="max-width: auto; max-height: 300px;">';
                break;
            case "svg":
                echo '<img src=' . $path . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-3 mb-3" alt=' . $fileDetails['title'] . ' style="max-width: auto; max-height: 300px;">';
                break;
            case "pdf":
                echo '<img src="img/pdf.png" class="object-fit-cover h-auto w-100 d-block mx-auto btn p-0 rounded-0" alt=' . $fileDetails['title'] . '>';
                break;
            case "txt":
                echo '<img src="img/text.png" class="object-fit-cover h-auto w-100 d-block mx-auto btn p-0 rounded-0" alt=' . $fileDetails['title'] . '>';
                break;
            case "docx":
                echo '<img src="img/word.png" class="object-fit-cover h-auto w-75 d-block mx-auto py-5 btn rounded-0 mb-3" alt=' . $fileDetails['title'] . '>';
                break;
            case "doc":
                echo '<img src="img/word.png" class="object-fit-cover h-auto w-75 d-block mx-auto py-5 btn rounded-0 mb-3" alt=' . $fileDetails['title'] . '>';
                break;
            case "pptx":
                echo '<img src="img/ppt.png" class="object-fit-cover h-auto w-75 d-block mx-auto py-5 btn rounded-0 mb-3" alt=' . $fileDetails['title'] . '>';
                break;
            case "xlsx":
                echo '<img src="img/excel.png" class="object-fit-cover h-auto w-75 d-block mx-auto py-5 btn rounded-0 mb-3" alt=' . $fileDetails['title'] . '>';
                break;
            case "xlr":
                echo '<img src="img/excel.png" class="object-fit-cover h-auto w-75 d-block mx-auto py-5 btn rounded-0 mb-3" alt=' . $fileDetails['title'] . '>';
                break;
            default:
                echo '<img src="img/text.png" class="object-fit-cover h-auto w-100 d-block mx-auto btn p-0 rounded-0" alt=' . $fileDetails['title'] . '>';
                break;
        }
        echo '
            <p>Owner: ' . $fileDetails['publisher'] . '</p>
            <p>Name: ' . $fileDetails['title'] . '</p>
            <p>Category: ' . $fileDetails['category'] . '</p>
            <p>Directory: ' . str_replace('%20', " ", $fileDetails['fileName']) . '</p>
            <p>Uploaded: ' . $date . '</p>
            <p>Date Modified: ' . $modified . '</p>
        ';
    }
}

// Function for renaming primary folder
if (isset($_POST['saveFolder'])) {

    $newFolderName = htmlspecialchars($_POST['rename_folder']);
    $folder_id = htmlspecialchars($_POST['id']);
    $client_id = htmlspecialchars($_POST['client_id']);
    // Current Folder or previous name of the folder before renaming
    $currentFolder = htmlspecialchars($_POST['currentFolder']);
    $parentFolder = htmlspecialchars($_POST['parentFolder']);

    // Date updated
    $created = htmlspecialchars($_POST['created']);
    $modified = htmlspecialchars($_POST['updated']) ? htmlspecialchars($_POST['updated']) : null;

    // Variable for returning to the page
    $back = htmlspecialchars($_POST['return']);

    $newFolderName = str_replace(' ', ' ', $newFolderName);

    // CSRF token
    $key = htmlspecialchars($_POST['key']);
    $token = hash_hmac('sha256', 'Create Folder', $key);

    if (hash_equals($token, $_POST['csrfToken'])) {

        if ($parentFolder != '') {
            $rootFolder = "folders/" . $parentFolder . "/";
        } else {
            $rootFolder = "folders/";
        }

        // Scan each directory for duplicated files
        $counter = array_diff(scandir($rootFolder), array('..', '.'));
        $count = count($counter);

        if ($counter > 0) {
            if (in_array($newFolderName, $counter)) {
                $newFolderName = $newFolderName . "(" . $count . ")";
            } else {
                $newFolderName = $newFolderName;
            }
        } else {
            $newFolderName = $newFolderName;
        }

        // Renaming Folder
        $renameFolder = rename($rootFolder . $currentFolder, $rootFolder . $newFolderName);

        // Query if the primary folder has a child folder
        $child_folder = $conn->prepare("SELECT id, folder FROM folder_upload WHERE parent_folder = :parent_folder AND client_id = :client_id");
        $child_folder->bindParam(':parent_folder', $currentFolder);
        $child_folder->bindParam(':client_id', $client_id);
        $child_folder->execute();

        if ($child_folder->rowCount() > 0) {
            while ($childFolder_id = $child_folder->fetch(PDO::FETCH_ASSOC)) {
                $child_folder_id = $childFolder_id['id'];
                $child_folder_name = $childFolder_id['folder'];

                // Update the parent_folder WITHIN CURRENT_FOLDER
                $updateChildFolder = $conn->prepare("UPDATE folder_upload SET parent_folder = '$newFolderName' WHERE parent_folder = '$currentFolder' AND folder = '$child_folder_name' AND id = '$child_folder_id' AND client_id = :client_id");
                $updateChildFolder->bindParam(':client_id', $client_id);
                $updateChildFolder->execute();
            }
        }

        // Query if the primary folder has a file
        $file = $conn->prepare("SELECT file_id, folder FROM file_upload WHERE parent_folder = :parent_folder AND client_id = :client_id");
        $file->bindParam(':parent_folder', $currentFolder);
        $file->bindParam(':client_id', $client_id);
        $file->execute();

        if ($file->rowCount() > 0) {
            while ($child_file = $file->fetch(PDO::FETCH_ASSOC)) {
                $file_id = $child_file['file_id'];
                $file_name = $child_file['folder'];

                // Update the parent_folder WITHIN CURRENT_FOLDER
                $updateChildFile = $conn->prepare("UPDATE file_upload SET parent_folder = '$newFolderName', folder = '$newFolderName' WHERE parent_folder = '$currentFolder' AND folder = '$file_name' AND file_id = '$file_id' AND client_id = :client_id");
                $updateChildFile->bindParam(':client_id', $client_id);
                $updateChildFile->execute();
            }
        }

        // Query if the primary folder has a descendant file
        $desc_file = $conn->prepare("SELECT file_id, folder FROM file_upload WHERE asc_folder = :parent_folder AND client_id = :client_id");
        $desc_file->bindParam(':parent_folder', $currentFolder);
        $desc_file->bindParam(':client_id', $client_id);
        $desc_file->execute();

        if ($desc_file->rowCount() > 0) {
            while ($descendant_file = $desc_file->fetch(PDO::FETCH_ASSOC)) {
                $file_id = $descendant_file['file_id'];
                $file_name = $descendant_file['folder'];

                // Update the parent_folder WITHIN CURRENT_FOLDER
                $update_desc_file = $conn->prepare("UPDATE file_upload SET asc_folder = '$newFolderName' WHERE asc_folder = '$currentFolder' AND folder = '$file_name' AND file_id = '$file_id' AND client_id = :client_id");
                $update_desc_file->bindParam(':client_id', $client_id);
                $update_desc_file->execute();
            }
        }

        // Updating the primary folder in the database
        $rename = $conn->prepare("UPDATE folder_upload SET folder_name = :folder_name, folder = :folder, created = :created, updated = :updated WHERE id = :id");
        $rename->bindParam(':folder_name', $newFolderName);
        $rename->bindParam(':folder', $newFolderName);
        $rename->bindParam(':created', $created);
        $rename->bindParam(':updated', $modified);
        $rename->bindParam(':id', $folder_id);
        $rename->execute();
    } else {

        echo "CSRF Token is invalid";

        function back($back)
        {
            header("Location: $back");
        }
        back($back);
    }

    // Function the returns to the page
    function back($back)
    {
        header("Location: $back");
    }
    back($back);
}

// Folder Details Function
if (isset($_POST['id'])) {
    $folder_id = $_POST['id'];

    $stmt = $conn->prepare("SELECT * FROM folder_upload WHERE id = :id");
    $stmt->bindParam(':id', $folder_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $folderDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        $date = date('M. d, Y. h:i A - D', strtotime($folderDetails['created']));
        $modified = date('M. d, Y. h:i A - D', strtotime($folderDetails['updated']));

        if ($modified == $date) {
            $modified = "No modification";
        }

        echo '
            <div class="col card shadow-sm p-3 my-1 my-md-3 mr-0 mr-md-1 mr-lg-3 d-flex h-auto w-100" id="cardFile" style="background: hsla(198, 100%, 95%, .5);">
                <i class="fa-solid fa-folder d-block mx-auto display-1 p-4 text-primary"></i>
            </div>
            <p>Owner: ' . $folderDetails['publisher'] . '</p>
            <p>Name: ' . $folderDetails['folder_name'] . '</p>
            <p>Category: ' . $folderDetails['category'] . '</p>
            <p>Directory: ' . $folderDetails['folder'] . '</p>
            <p>Created: ' . $date . '</p>
            <p>Date Modified: ' . $modified . '</p>';
    }
}

// Function for renaming secondary folder
if (isset($_POST['saveSecondaryFolder'])) {

    // CSRF Token
    $newFolderName = htmlspecialchars($_POST['rename_folder']);
    $folder_id = $_POST['id'];
    $currentFolder = $_POST['currentFolder'];
    $parent_folder = $_POST['parent_folder'];

    // Date updated
    $created = htmlspecialchars($_POST['created']);
    $modified = htmlspecialchars($_POST['updated']) ? htmlspecialchars($_POST['updated']) : null;

    // Variable for returning to the page
    $back = $_POST['return'];

    // CSRF token
    $key = htmlspecialchars($_POST['key']);
    $token = hash_hmac('sha256', 'Create Folder', $key);

    if (hash_equals($token, $_POST['csrfToken'])) {

        // Update the parent folder of the files inside this folder

        if ($parent_folder != '') {
            $folderPath = "folders/" . $parent_folder . "/";
        } else {
            $folderPath = "folders/";
        }

        $newFolderName = str_replace(' ', ' ', $newFolderName);

        // Renaming Folder
        $renameFolder = rename($folderPath . $currentFolder, $folderPath . $newFolderName);

        // Query if the folder folder has a file
        $file = $conn->prepare("SELECT file_id, folder FROM file_upload WHERE parent_folder = :parent_folder AND client_id = :client_id");
        $file->bindParam(':parent_folder', $currentFolder);
        $file->bindParam(':client_id', $client_id);
        $file->execute();

        if ($file->rowCount() > 0) {
            while ($child_file = $file->fetch(PDO::FETCH_ASSOC)) {
                $file_id = $child_file['file_id'];
                $file_name = $child_file['folder'];

                // Update the parent_folder WITHIN CURRENT_FOLDER
                $updateChildFile = $conn->prepare("UPDATE file_upload SET parent_folder = '$newFolderName', folder = '$newFolderName' WHERE parent_folder = '$currentFolder' AND folder = '$file_name' AND file_id = '$file_id' AND client_id = :client_id");
                $updateChildFile->bindParam(':client_id', $client_id);
                $updateChildFile->execute();
            }
        }

        $rename = $conn->prepare("UPDATE folder_upload SET folder_name = :folder_name, folder = :folder WHERE id = :id");
        $rename->bindParam(':folder_name', $newFolderName);
        $rename->bindParam(':folder', $newFolderName);
        $rename->bindParam(':id', $folder_id);
        $rename->execute();
    } else {
        echo "CSRF Token is invalid";

        function back($back)
        {
            header("Location: $back");
        }
        back($back);
    }

    // Function the returns to the page
    function back($back)
    {
        header("Location: $back");
    }
    back($back);
}

// User informaion
$client_id = htmlspecialchars($_SESSION['id']);

try {
    $stmt = $conn->prepare("SELECT * FROM client WHERE client_id = :client_id");
    $stmt->bindParam(':client_id', $client_id);
    $stmt->execute();

    $member_row = $stmt->fetch(PDO::FETCH_ASSOC);
    $id = $member_row['client_id'];
    $profile = $member_row['profile'];
    $fullname = $member_row['fullname'];
    $email = $member_row['email'];
    $phone = $member_row['phone'];
    $password = $member_row['password'];
    $created = $member_row['created'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Logout function
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    redirectTo('index.php');
    exit();
}
