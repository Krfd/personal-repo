<?php
include('pdoaction.php');
include('layout/header.php');
?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper" style="height: 100vh;">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item mt-2 my-0">
                <!-- the original -->
                <a class="nav-link collapsed btn" id="collapseProfile" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <img class="img-profile rounded-circle object-fit-cover" src="<?php
                                                                                    if ($profile == "") {
                                                                                        echo "./img/random.jpg";
                                                                                    } else {
                                                                                        echo "profile/" . $profile;
                                                                                    }
                                                                                    ?>" alt="<?php echo $fullname ?>">
                    <span class="small mt-3"><?php echo $fullname; ?></span>

                </a>

                <!-- The original collapse -->
                <div id="collapseTwo" class="collapse mx-2" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" data-toggle="modal" data-target="#profile">Profile</a>
                    </div>
                </div>


                <!-- Edit Modal -->
                <div class="modal fade" id="profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Profile</h5>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <img src="<?php if ($profile == "") {
                                                    echo "./img/random.jpg";
                                                } else {
                                                    echo "profile/" . $profile;
                                                } ?>" class="rounded-circle object-fit-cover shadow-lg w-25 h-auto mb-3" alt="<?php echo $fullname ?>">
                                    <div class="text-start">
                                        <h1 class="font-weight-bold"><?php echo $fullname; ?></h1>
                                        <p>Email: <?php echo $email ?></p>
                                        <p>Contact: <?php echo $phone ?></p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa">&#xf00d;</i> Cancel</button>
                                    <a class="btn btn-primary" data-toggle="modal" data-target="#edit"> <i class="fas fa-pen"></i> Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
            </li>

            <!--Update profile Modal-->
            <?php
            $get_user = $conn->prepare("SELECT * FROM client WHERE client_id = :client_id");
            $get_user->bindParam(':client_id', $client_id);
            $get_user->execute();

            $user = $get_user->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                include("update/updateProfile.php");
            } else {
                echo "User not found.";
            }
            ?>

            <hr class="sidebar-divider text-white">

            <div class="sidebar-heading mb-2">Office Personal Repository</div>

            <li class="nav-item link m-1 rounded">
                <a class="nav-link" href="storage.php">
                    <i class="fas fa-database"></i>
                    <span>Storage</span></a>
                </a>
            </li>
            <li class="nav-item link bg-light m-1 rounded">
                <a class="nav-link text-secondary" href="recent.php">
                    <i class="fas fa-clock text-secondary"></i>
                    <span>Recent</span></a>
                </a>
            </li>
            <li class="nav-item link m-1 rounded">
                <a class="nav-link" href="archive.php">
                    <i class="fa-solid fa-box-archive"></i>
                    <span>Archive</span></a>
                </a>
            </li>
            <li class="nav-item link m-1 rounded">
                <a class="nav-link" href="trash.php">
                    <i class="fas fa-trash"></i>
                    <span>Trash</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider text-white">

            <li class="nav-item link mx-1 mb-1 rounded">
                <a class="nav-link" href="pdoaction.php?logout=true">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw"></i>
                    <span>Logout</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider text-white">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <div class="text-center mt-auto text-white mb-3">
                <small id="date"></small>
                <br>
                <small id="time"></small>
            </div>
            <script src="js/time.js"></script>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="bg-gradient-light">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group shadow-sm rounded-lg">
                            <input type="search" class="form-control bg-light border-0 small " placeholder="Search here..." aria-label="Search" aria-describedby="basic-addon2" id="myInput" />
                        </div>
                    </form>

                    <script>
                        $(document).ready(function() {
                            $("#myInput").on("keyup", function() {
                                var value = $(this).val().toLowerCase();
                                $("#myList li").filter(function() {
                                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                });
                                $("#myFolder li").filter(function() {
                                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                });
                            });
                        });
                    </script>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto ">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="storage.php">
                                <img class="h-75 w-auto m-2" src="img/city-gov-logo.png" />
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- Body Content -->
                <div class="container-fluid mx-auto">
                    <div class="header">
                        <?php
                        $folderPath = isset($_GET['path']) ? htmlspecialchars($_GET['path']) : '';

                        $folder = $conn->prepare("SELECT folder_name, category FROM folder_upload WHERE folder_upload.client_id = :client_id AND folder_upload.folder = :folder_path ORDER BY id DESC");
                        $folder->bindParam(':client_id', $client_id);
                        $folder->bindParam(':folder_path', $folderPath);
                        $folder->execute();

                        if ($folder->rowCount() > 0) {
                            foreach ($folder as $folderupload) {
                                $folderName = $folderupload['folder_name'];
                                $folderCategory = $folderupload['category'];
                                if (strlen($folderName) > 15) {
                                    $folderName = substr($folderName, 0, 13) . "...";
                                } else {
                                    $folderName = $folderName;
                                }

                                echo '<h1 class="header font-weight-semibold"><a href="recent.php" class="text-secondary header">Recent</a> <i class="fa-solid fa-angle-right small"></i> ' . $folderCategory . ' - ' . $folderName . '</h1>';
                            }
                        }
                        ?>
                    </div>
                    <div class="my-4">
                        <h2 class="header mb-3">Folders</h2>
                        <div class="d-block d-md-flex d-md-flex-row justify-content-start flex-wrap" id="file-container">
                            <?php

                            $mainFolder = isset($_GET['path']) ? htmlspecialchars($_GET['path']) : '';

                            // Get the folder path
                            $folder = $conn->prepare("SELECT folder FROM folder_upload WHERE folder_upload.client_id = :client_id AND folder = :folder AND is_deleted = 0 AND is_archive = 0 ORDER BY id DESC");
                            $folder->bindParam(':client_id', $client_id);
                            $folder->bindParam(':folder', $mainFolder);
                            $folder->execute();

                            if ($folder->rowCount() > 0) {
                                foreach ($folder as $folderupload) {

                                    $folder = $conn->prepare("SELECT * FROM folder_upload WHERE folder_upload.client_id = :client_id AND parent_folder = '$mainFolder' AND is_deleted = 0 AND is_archive = 0 ORDER BY id DESC");
                                    $folder->bindParam(':client_id', $client_id);
                                    $folder->execute();

                                    if ($folder->rowCount() > 0) {
                                        foreach ($folder as $folderupload) {

                                            $folder_id = $folderupload['id'];
                                            $folderName = $folderupload['folder'];
                                            $folder_path = $folderupload['folder'];
                                            $folder_modal = 'renameFolder_' . $folder_id;
                                            $parent_path = $folderupload['parent_folder'];

                                            $modified = date('M. d, y', strtotime($folderupload['updated']));

                                            $folderName = $folderupload['folder'];

                                            if (strlen($folderName) > 15) {
                                                $folderName = substr($folderName, 0, 13) . "...";
                                            } else {
                                                $folderName = $folderName;
                                            }

                                            $page = "recent_folders.php?path=" . $mainFolder;

                                            echo
                                            '<ul class="list-group list-unstyled" id="myFolder">
                                                <li class="list group" data-category="' . $folderupload['category'] . '">
                                                    <div class="col card shadow-sm p-2 my-1 my-md-3 mr-0 mr-md-1 mr-lg-3 d-flex" id="cardFile" style="max-width: 210px; min-width: 210px; min-height: 210px; max-height: 280px;" data-toggle="tooltip" title="Category: ' . $folderupload['category'] . '">
                                                        <a href="recent_inner_folder.php?parent_path=' . $parent_path . '&path=' . $folder_path . '" class="d-block mx-auto display-1 p-3 imageContainer">
                                                            <i class="fa-solid fa-folder" style="color: #0275d8"></i>
                                                        </a>
                                                        <div class="mt-auto px-1">
                                                            <small>' . $modified . '</small>
                                                            <div class="d-flex justify-content-between">
                                                                <span class="overflow-hidden pr-3">' . $folderName . '</span>
                                                                <i class="fa-solid fa-ellipsis-vertical dropdown rounded-circle py-1 px-2 align-self-center" data-toggle="dropdown" type="button" id="toggleMenuOption"></i>
                                                                <div class="dropdown-menu">
                                                                    <a href="recent_inner_folder.php?parent_path=' . $parent_path . '&path=' . $folder_path . '"  class="dropdown-item"><i class="fa-regular fa-folder-open"></i> Open</a>
                                                                    
                                                                    <a href="#" class="dropdown-item" data-toggle="canvas" data-target="#folder-id" aria-expanded="false" aria-controls="bs-canvas-right" data-folder-id=' . $folderupload['id'] . '>
                                                                        <i class="fa-solid fa-circle-info"></i> Folder Information
                                                                    </a>
                                                                   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                    
                                                        <div id="folder-id" class="bs-canvas bs-canvas-anim bs-canvas-right position-fixed bg-light h-100">
                                                            <header class="bs-canvas-header p-3 bg-primary overflow-auto">
                                                                <button type="button" class="bs-canvas-close float-left close mr-3" aria-label="Close"><span aria-hidden="true" class="text-light">&times;</span></button>
                                                                <h4 class="d-inline-block text-light mb-0">Folder Details</h4>
                                                            </header>
                                                        <div class="bs-canvas-content p-3 folder-content">
                                                    
                                                        <div class="col card shadow-sm p-3 my-1 my-md-3 mr-0 mr-md-1 mr-lg-3 d-flex h-auto w-100" id="cardFile" style="background: hsla(198, 100%, 95%, .5);">
                                                            <i class="fa-solid fa-folder d-block mx-auto display-1 p-4 text-primary"></i>
                                                        </div>
                                                        <!-- Folder details display here from folder_detail.js data from pdoaction.php=folder details -->
                                                </li>
                                            </ul>';
                                        }
                                    } else {
                                        echo "No folders found in this folder.";
                                    }
                                }
                            }

                            ?>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="my-4">
                        <h2 class="header mb-3">Files</h2>

                        <div class="d-block d-md-flex d-md-flex-row justify-content-start flex-wrap" id="file-container">

                            <!-- LOOP THROUGH THE FILES IN THE DATABASE -->
                            <?php

                            $mainFolder = isset($_GET['path']) ? htmlspecialchars($_GET['path']) : '';

                            // Get the folder path
                            $folder = $conn->prepare("SELECT folder FROM folder_upload WHERE folder_upload.client_id = :client_id AND folder = :folder AND is_deleted = 0 AND is_archive = 0 ORDER BY id DESC");
                            $folder->bindParam(':client_id', $client_id);
                            $folder->bindParam(':folder', $mainFolder);
                            $folder->execute();

                            if ($folder->rowCount() > 0) {
                                foreach ($folder as $folder_upload) {

                                    $folderPath = $folder_upload['folder'];

                                    $file = $conn->prepare("SELECT * FROM file_upload WHERE file_upload.client_id = :client_id AND folder = '$mainFolder' AND is_deleted = 0 AND is_archive = 0 ORDER BY file_id DESC");
                                    $file->bindParam(':client_id', $client_id);
                                    $file->execute();

                                    if ($file->rowCount() > 0) {
                                        foreach ($file as $fileupload) {

                                            $file_id = $fileupload['file_id'];
                                            $modal_id = 'renameModal_' . $file_id;
                                            $file_path = 'folders/' . $folderPath . "/" . $fileupload['fileName'];
                                            $modified = date('M. d, y', strtotime($fileupload['updated']));

                                            $file_path = str_replace(" ", "%20", $file_path);

                                            $fileName = $fileupload['fileName'];

                                            if (strlen($fileName) > 15) {
                                                $fileName = substr($fileName, 0, 13) . "...";
                                            } else {
                                                $fileName = $fileName;
                                            }

                                            $page = "recent_folders.php?path=$folderPath";

                                            echo '
                                            <ul class="list-group" id="myList" >
                                                <li class="list-group" data-category="' . $fileupload['category'] . '">
                                                    <div class="col card shadow-sm p-2 my-1 my-md-3 mr-0 mr-md-1 mr-lg-3 d-flex" id="cardFile" style="max-width: 210px; min-width: 210px; min-height: 210px; max-height: 210px;">
                                                        <a href=' . $file_path . ' target="_blank" class="imageContainer" style="overflow: hidden" style="position:relative">';
                                            // Conditional Rendering for different file formats
                                            $extension = pathinfo($fileupload['fileName'], PATHINFO_EXTENSION);
                                            switch ($extension) {
                                                case "jpg":
                                                    echo '<img src=' . $file_path . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-0" alt=' . $fileupload['title'] . ' style="min-height: 150px;">';
                                                    break;
                                                case "png":
                                                    echo '<img src=' . $file_path . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-0" alt=' . $fileupload['title'] . ' style="min-height: 150px;">';
                                                    break;
                                                case "jpeg":
                                                    echo '<img src=' . $file_path . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-0" alt=' . $fileupload['title'] . ' style="min-height: 150px;">';
                                                    break;
                                                case "svg":
                                                    echo '<img src=' . $file_path . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-0" alt=' . $fileupload['title'] . ' style="min-height: 150px;">';
                                                    break;
                                                case "pdf":
                                                    echo '<img src="img/pdf.png" class="object-fit-cover h-auto w-75 d-block mx-auto btn p-0 rounded-0" alt=' . $fileupload['title'] . '>';
                                                    break;
                                                case "txt":
                                                    echo '<img src="img/text.png" class="object-fit-cover h-auto w-75 d-block mx-auto btn p-0 rounded-0" alt=' . $fileupload['title'] . '>';
                                                    break;
                                                case "docx":
                                                    echo '<img src="img/word.png" class="object-fit-cover h-auto w-75 d-block mx-auto py-3 btn rounded-0" alt=' . $fileupload['title'] . '>';
                                                    break;
                                                case "doc":
                                                    echo '<img src="img/word.png" class="object-fit-cover h-auto w-75 d-block mx-auto py-3 btn rounded-0" alt=' . $fileupload['title'] . '>';
                                                    break;
                                                case "pptx":
                                                    echo '<img src="img/ppt.png" class="object-fit-cover h-auto w-75 d-block mx-auto py-3 btn rounded-0" alt=' . $fileupload['title'] . '>';
                                                    break;
                                                case "xlsx":
                                                    echo '<img src="img/excel.png" class="object-fit-cover h-auto w-75 d-block mx-auto py-3 btn rounded-0" alt=' . $fileupload['title'] . '>';
                                                    break;
                                                case "xlr":
                                                    echo '<img src="img/excel.png" class="object-fit-cover h-auto w-75 d-block mx-auto py-3 btn rounded-0" alt=' . $fileupload['title'] . '>';
                                                    break;
                                                default:
                                                    echo '<img src="img/text.png" class="object-fit-cover h-auto w-75 d-block mx-auto btn p-0 rounded-0" alt=' . $fileupload['title'] . '>';
                                                    break;
                                            }
                                            echo '</a>
                                            <div class="mt-auto px-1">
                                            <small>' . $modified . '</small>
                                                        <div class="d-flex justify-content-between cardName" ">
                                                            <span class="overflow-hidden pr-3 cardName">' . $fileName . '</span>
                                                                <i class="fa-solid fa-ellipsis-vertical dropdown rounded-circle py-1 px-2 align-self-center" data-toggle="dropdown" type="button" id="toggleMenuOption"></i>
                                                                <div class="dropdown-menu">
                                                                    <a href=' . $file_path . ' class="dropdown-item" target="_blank"><i class="fa-regular fa-folder-open" target="_blank"></i> Open</a>
                                                                    
                                                                    <a href="#" class="dropdown-item" data-toggle="canvas" data-target="#bs-canvas-right" aria-expanded="false" aria-controls="bs-canvas-right"  data-file-id=' . $fileupload['file_id'] . '><i class="fa-solid fa-circle-info"></i> File Information</a>
                                                                    
                                                                    <a href=' . $file_path . ' class="dropdown-item" download><i class="fa-solid fa-download"></i> Download</a>
                                                                   
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                
                                                        <div id="bs-canvas-right" class="bs-canvas bs-canvas-anim bs-canvas-right position-fixed bg-light h-100">
                                                            <header class="bs-canvas-header p-3 bg-primary overflow-auto">
                                                                <button type="button" class="bs-canvas-close float-left close mr-3" aria-label="Close"><span aria-hidden="true" class="text-light">&times;</span></button>
                                                                <h4 class="d-inline-block text-light mb-0">File Details</h4>
                                                            </header>
                                                            <div class="bs-canvas-content p-3">

                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>';
                                        }
                                    } else {
                                        echo "No files found in this folder.";
                                    }
                                }
                            }

                            ?>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End of Main Content -->

            <?php include "layout/footer.php" ?>

        </div>
        <!-- End of Content Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
        <script src="js/addSecondaryFolder.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Add event listener to category dropdown items
                var categoryLinks = document.querySelectorAll('#categoryDropdown a');
                var fileItems = document.querySelectorAll('#myList li');
                var folderItems = document.querySelectorAll('#myFolder li');

                categoryLinks.forEach(function(link) {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        var selectedCategory = link.textContent.trim();

                        fileItems.forEach(function(item) {
                            var itemCategory = item.getAttribute('data-category');
                            if (selectedCategory === 'All' || selectedCategory === itemCategory) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                        folderItems.forEach(function(item) {
                            var itemCategory = item.getAttribute('data-category');
                            if (selectedCategory === 'All' || selectedCategory === itemCategory) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });
                });
            });

            $('#collapseProfile').click(function() {
                $('#collapseTwo').toggle(500);
            });

            $('#toggleCategory').click(function() {
                $('#categoryDropdown').toggle(300);
            });

            $('.dropdown-item').click(function() {
                $('#categoryDropdown').hide();
            });
        </script>

        <?php include "layout/scripts.php" ?>

</body>

</html>