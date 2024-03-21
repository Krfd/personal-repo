<?php
include('pdoaction.php');
include "layout/header.php";
?>

<body id="page-top" class="bg-gradient-light">

    <!-- Page Wrapper -->
    <div id="wrapper" style="height: 100vh;">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item mt-2 my-0">
                <a class="nav-link collapsed btn" id="collapseProfile" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <img class="img-profile rounded-circle object-fit-cover" src="<?php if ($profile == "") {
                                                                                        echo "./img/random.jpg";
                                                                                    } else {
                                                                                        echo "profile/" . $profile;
                                                                                    } ?>" alt="<?php echo $fullname ?>">
                    <span class="small mt-3"><?php echo $fullname; ?></span>
                </a>
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
                                                } ?>" class="rounded-circle object-fit-cover shadow-sm w-25 h-auto mb-3" alt="<?php echo $fullname ?>">
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

            <hr class="sidebar-divider">

            <div class="sidebar-heading mb-2">Office Personal Repository</div>

            <li class="nav-item dropdown no-arrow d-sm-none">
                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-search fa-fw"></i>
                </a>
                <!-- Dropdown - Messages -->
                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                    <form class="form-inline mr-auto w-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>

            <!-- Add File Modal -->
            <div class="modal fade" id="addfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add File</h5>
                            <i class="fa-solid fa-circle-info align-self-center" style="cursor:pointer; color: rgb(30, 48, 80)" data-toggle="tooltip" title="Support files are: .png, .jpg, .svg, .jpeg, .txt, .pdf, .doc, .docx, .pptx, xlsx, .xlr"></i>
                        </div>
                        <div class="modal-body">
                            <form action="pdoaction.php" method="POST" enctype="multipart/form-data" class="user">
                                <input type="hidden" name="return" value="<?php echo "trash.php" ?>">
                                <div class="form-group">
                                    <input type="hidden" name="client_id" aria-checked="" value="<?php echo $member_row['client_id'] ?>" readonly placeholder="Owner" autocomplete="on" required title="Owner">
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="publisher" aria-checked="" value="<?php echo $member_row['fullname'] ?>" readonly placeholder="Owner" autocomplete="on" required title="Owner">
                                </div>

                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input btn rounded-pill" name="fileName" id="customFile" onchange="updateFileName()" required>

                                        <script>
                                            function updateFileName() {

                                                // Get the input elements
                                                var input = document.getElementById('customFile');
                                                var inputOne = document.getElementById('updateProfile');

                                                // Check if the element is found
                                                console.log(input);
                                                console.log(inputOne);

                                                // Get the file names
                                                var fileName = input.files[0] ? input.files[0].name : "No file selected";
                                                var fileNameOne = inputOne.files[0] ? inputOne.files[0].name : "No file selected";

                                                console.log(fileName);
                                                console.log(fileNameOne);

                                                // Update the labels with the file names
                                                var label = document.querySelector('#custom-file-label');
                                                label.textContent = fileName;

                                                var labelOne = document.querySelector('.custom-file-label');
                                                labelOne.textContent = fileNameOne;
                                            }
                                        </script>
                                        <label class="custom-file-label btn d-flex float-left rounded-pill" id="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>

                                <?php
                                $client_id = $_SESSION['id'];
                                try {
                                    $stmt = $conn->prepare("SELECT DISTINCT category FROM file_upload WHERE client_id = :client_id AND category NOT IN ('IPCR', 'PDS', 'DTR', 'OFFICE ORDER', 'SALN', 'PAYSLIP', 'Detail Order', 'Property Accountability','Others') ORDER BY category DESC");
                                    $stmt->bindParam(':client_id', $client_id);
                                    $stmt->execute();
                                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                } catch (PDOException $e) {
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>

                                <div class="form-group d-flex align-items-center">
                                    <select name="category" id="category" class="custom-select ml-1 rounded-pill scrollable-dropdown" onchange="toggleInputField()" required>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?php echo $category['category'] ?>"><?php echo $category['category'] ?></option>
                                        <?php endforeach; ?>
                                        <option value="IPCR">IPCR</option>
                                        <option value="PDS">PDS</option>
                                        <option value="DTR">DTR</option>
                                        <option value="OFFICE ORDER">OFFICE ORDER(S)</option>
                                        <option value="SALN">SALN</option>
                                        <option value="PAYSLIP">PAYSLIP</option>
                                        <option value="Detail Order">Detail Order</option>
                                        <option value="Property Accountability">Property Accountability</option>
                                        <option value="Others">Others:</option>
                                    </select>
                                </div>

                                <div class="form-group" id="othersInputContainer" style="display: none;">
                                    <label for="othersInput">Other Category:</label>
                                    <input type="text" class="form-control rounded-pill" name="othersInput" id="othersInput" placeholder="Enter other category" oninput="updateOthersOption()">
                                </div>

                                <script>
                                    function toggleInputField() {
                                        var categorySelect = document.getElementById('category');

                                        if (categorySelect.value === 'Others') {
                                            othersInputContainer.style.display = 'block';
                                        } else {
                                            othersInputContainer.style.display = 'none';
                                        }
                                    }

                                    function updateOthersOption() {
                                        var categorySelect = document.getElementById('category');
                                        var othersInput = document.getElementById('othersInput');

                                        var othersOption = categorySelect.querySelector('option[value="Others"]');
                                        if (othersOption) {
                                            othersOption.textContent = 'Others: ' + othersInput.value;
                                        }
                                    }
                                </script>

                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa">&#xf00d;</i> Cancel</button>
                            <button class="btn btn-primary" type="submit" name="addFile"><i class="fas fa-plus-circle"></i> Add</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add Folder Modal -->
            <div class="modal fade" id="addfolder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Folder</h5>
                        </div>
                        <div class="modal-body">
                            <form action="pdoaction.php" method="POST" class="user" enctype="multipart/form-data">
                                <input type="hidden" name="return" value="<?php echo "trash.php" ?>">
                                <div class="form-group">
                                    <input type="hidden" name="client_id" aria-checked="" value="<?php echo $member_row['client_id'] ?>" readonly placeholder="Owner" autocomplete="on" required title="Owner">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="publisher" aria-checked="" value="<?php echo $member_row['fullname'] ?>" readonly placeholder="Owner" autocomplete="on" required title="Owner">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="folder" id="folder" aria-checked="" placeholder="Folder name" autocomplete="on" required title="Folder name">
                                </div>
                                <?php
                                $client_id = $_SESSION['id'];
                                try {
                                    $stmt = $conn->prepare("SELECT DISTINCT category FROM folder_upload WHERE client_id = :client_id AND category NOT IN ('IPCR', 'PDS', 'DTR', 'OFFICE ORDER', 'SALN', 'PAYSLIP', 'Detail Order', 'Property Accountability','Others') ORDER BY category DESC");
                                    $stmt->bindParam(':client_id', $client_id);
                                    $stmt->execute();
                                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                } catch (PDOException $e) {
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>

                                <div class="form-group d-flex align-items-center">
                                    <select name="folderCategory" id="folderCategory" class="custom-select ml-1 rounded-pill scrollable-dropdown" onchange="toggleFolderCategory()" required>
                                        <option>Category</option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?php echo $category['category'] ?>"><?php echo $category['category'] ?></option>
                                        <?php endforeach; ?>
                                        <option value="IPCR">IPCR</option>
                                        <option value="PDS">PDS</option>
                                        <option value="DTR">DTR</option>
                                        <option value="OFFICE ORDER">OFFICE ORDER(S)</option>
                                        <option value="SALN">SALN</option>
                                        <option value="PAYSLIP">PAYSLIP</option>
                                        <option value="Detail Order">Detail Order</option>
                                        <option value="Property Accountability">Property Accountability</option>
                                        <option value="Others">Others:</option>
                                    </select>
                                </div>

                                <div class="form-group" id="othersFolderContainer" style="display: none;">
                                    <label for="othersFolderCategory">Other Category:</label>
                                    <input type="text" class="form-control rounded-pill" name="otherFolderCategory" id="otherFolderCategory" placeholder="Enter other category" oninput="updateOtherCategory()">
                                </div>

                                <script>
                                    function toggleFolderCategory() {
                                        var categorySelect = document.getElementById('folderCategory');
                                        var othersFolderContainer = document.getElementById('othersFolderContainer');

                                        if (categorySelect.value === 'Others') {
                                            othersFolderContainer.style.display = 'block';
                                        } else {
                                            othersFolderContainer.style.display = 'none';
                                        }
                                    }

                                    function updateOtherCategory() {
                                        var categorySelect = document.getElementById('folderCategory');
                                        var othersInput = document.getElementById('otherFolderCategory');

                                        var othersOption = categorySelect.querySelector('option[value="Others"]');
                                        if (othersOption) {
                                            othersOption.textContent = 'Others: ' + othersInput.value;
                                        }
                                    }
                                </script>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa">&#xf00d;</i> Cancel</button>
                            <button class="btn btn-primary" type="submit" name="addfolder"> <i class="fas fa-plus-circle"></i> Create</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <li class="nav-item link m-1 rounded">
                <a class="nav-link" href="storage.php">
                    <i class="fas fa-database "></i>
                    <span>Storage</span></a>
                </a>
            </li>
            <li class="nav-item link m-1 rounded">
                <a class="nav-link" href="recent.php">
                    <i class="fas fa-clock"></i>
                    <span>Recent</span></a>
                </a>
            </li>
            <li class="nav-item link m-1 rounded">
                <a class="nav-link" href="archive.php">
                    <i class="fa-solid fa-box-archive"></i>
                    <span>Archive</span></a>
                </a>
            </li>
            <li class="nav-item link bg-light m-1 rounded">
                <a class="nav-link text-secondary active" href="trash.php">
                    <i class="fas fa-trash text-secondary"></i>
                    <span>Trash</span>

                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item link mx-1 mb-1 rounded">
                <a class="nav-link" href="pdoaction.php?logout=true">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw"></i>
                    <span>Logout</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

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
                    <form class="d-none d-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
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
                            <a class="nav-link dropdown-toggle" href="trash.php">
                                <img class="h-75 w-auto m-2" src="img/city-gov-logo.png" />
                            </a>
                        </li>
                    </ul>

                </nav>
                <!-- Body Content -->
                <div class="container-fluid">
                    <div class="header">
                        <h1 class="font-weight-semibold">Trash</h1>
                    </div>
                    <!-- File Category -->
                    <div class="dropdown">

                        <button type="button" class="btn btn-sm btn-dark dropdown-toggle" id="toggleCategory" data-toggle="dropdown">
                            Category
                        </button>
                        <div class="dropdown-menu" id="categoryDropdown">
                            <?php

                            include("category/delete_category.php");

                            ?>
                        </div>
                    </div>
                    <div class="my-4">
                        <h2 class="header mb-3">Folders</h2>
                        <div class="d-block d-md-flex d-md-flex-row justify-content-start flex-wrap" id="file-container">

                            <?php
                            $folder = $conn->prepare("SELECT * FROM folder_upload WHERE folder_upload.client_id = :client_id AND deleted_parent_folder = 0 AND is_deleted = 1");
                            $folder->bindParam(':client_id', $client_id);
                            $folder->execute();

                            if ($folder->rowCount() > 0) {
                                foreach ($folder as $folderupload) {

                                    $folder_id = $folderupload['id'];
                                    $folder_path = $folderupload['folder'];
                                    $modified = date('M. d, y', strtotime($folderupload['updated']));

                                    $folder_path = str_replace('', '%20', $folder_path);

                                    $folderName = $folderupload['folder'];

                                    if (strlen($folderName) > 15) {
                                        $folderName = substr($folderName, 0, 13) . "...";
                                    } else {
                                        $folderName = $folderName;
                                    }

                                    echo '
                                    <ul class="list-group list-unstyled" id="myFolder">
                                        <li class="list-group" data-category="' . $folderupload['category'] . '">
                                            <div class="col card shadow-sm p-2 my-1 my-md-3 mr-0 mr-md-1 mr-lg-3 d-flex" id="cardFile" style="max-width: 210px; min-width: 210px; min-height: 210px; max-height: 210px;" data-toggle="tooltip" title="Category: ' . $folderupload['category'] . '">
                                                <a href="trash_folders.php?path=' . $folder_path . '" class="d-block mx-auto display-1 p-3 imageContainer">
                                                    <i class="fa-solid fa-folder" style="color: #0275d8"></i>
                                                </a>
                                                <div class="mt-auto px-1">
                                                    <small>' . $modified . '</small>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="overflow-hidden pr-3">' . $folderName . '</span>
                                                            <i class="fa-solid fa-ellipsis-vertical dropdown rounded-circle py-1 px-2 align-self-center" data-toggle="dropdown" type="button" id="toggleMenuOption"></i>
                                                        <div class="dropdown-menu">
                                                            <a href="#" class="dropdown-item restore_folder" id="restore_folder" data-folder-id=' . $folder_id . ' data-folder=' . $folderupload['folder'] . ' data-client-id=' . $client_id . '><i class="fa-solid fa-recycle"></i> Restore</a>
                                                            <a href="#" class="dropdown-item delete_folder" id="delete_folder" data-folder-id=' . $folder_id . ' data-folder=' . $folderupload['folder'] . ' data-client-id=' . $client_id . '><i class="fa-solid fa-trash-can"></i> Delete Permanently</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                        </li>
                                    </ul>';
                                }
                            } else {
                                echo "Currently no folders in the trash.";
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

                            // File deleted only
                            $file = $conn->prepare("SELECT * FROM file_upload WHERE client_id = :client_id AND is_deleted = 1 AND deleted_parent_folder = 0");
                            $file->bindParam(':client_id', $client_id);
                            $file->execute();

                            if ($file->rowCount() > 0) {
                                foreach ($file as $fileupload) {
                                    $file_id = $fileupload['file_id'];
                                    $parent = $fileupload['parent_folder'];
                                    $asc = $fileupload['asc_folder'];

                                    if ($parent != '' && $asc != '') {
                                        $filePath = 'folders/' . $asc . "/" . $parent . "/" . $fileupload['fileName'];
                                    } else if ($parent != '') {
                                        $filePath = 'folders/' . $parent . "/" . $fileupload['fileName'];
                                    } else {
                                        $filePath = 'files_upload/' . $fileupload['fileName'];
                                    }
                                    $filePath = str_replace(' ', '%20', $filePath);

                                    $modal_id = 'renameModal_' . $file_id;
                                    $modified = date('M. d, y', strtotime($fileupload['updated']));

                                    echo '<ul class="list-group" id="myList">
                                    <li class="list-group" data-category="' . $fileupload['category'] . '">
                                    <div class="col card shadow-sm p-2 my-1 my-md-3 mr-0 mr-md-1 mr-lg-3 d-flex" id="cardFile" style="max-width: 210px; min-width: 210px; min-height: 210px; max-height: 210px;">
                                        <a disabled class="imageContainer" style="overflow: hidden; position:relative; cursor: default;" data-toggle="tooltip" title="Category: ' . $fileupload['category'] . '">';
                                    $extension = pathinfo($fileupload['fileName'], PATHINFO_EXTENSION);
                                    switch ($extension) {
                                        case "jpg":
                                            echo '<img src=' . $filePath . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-0" alt=' . $fileupload['title'] . ' style="min-height: 150px;">';
                                            break;
                                        case "png":
                                            echo '<img src=' . $filePath . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-0" alt=' . $fileupload['title'] . ' style="min-height: 150px;">';
                                            break;
                                        case "jpeg":
                                            echo '<img src=' . $filePath . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-0" alt=' . $fileupload['title'] . ' style="min-height: 150px;">';
                                            break;
                                        case "svg":
                                            echo '<img src=' . $filePath . ' class="object-fit-cover h-auto w-100 btn p-0 rounded-0" alt=' . $fileupload['title'] . ' style="min-height: 150px;">';
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
                                            echo '<img src="img/text.png" class="object-fit-cover h-auto w-75 d-block mx-auto py-3 btn rounded-0" alt=' . $fileupload['title'] . '>';
                                            break;
                                    }
                                    echo '</a>
                                    <div class="mt-auto px-1">
                                    <small>' . $modified . '</small>
                                            <div class="d-flex justify-content-between">
                                                <span class="overflow-hidden pr-3">' . substr($fileupload['fileName'], 0, 18) . '</span>
                                                <i class="fa-solid fa-ellipsis-vertical dropdown rounded-circle py-1 px-2 align-self-center" data-toggle="dropdown" type="button" id="toggleMenuOption"></i>
                                                <div class="dropdown-menu">
                                                    <a href="#" class="dropdown-item restore_file" id="restore_file" data-file-id=' . $file_id . '><i class="fa-solid fa-recycle"></i> Restore</a>
                                                    <a href="#" class="dropdown-item delete_file" id="delete_file" data-file-id=' . $file_id . '><i class="fa-solid fa-trash-can"></i> Delete Permanently</a>
                                                </div>
                                            </div>
                                            </div> 
                                            </div>
                                        </li>
                                    </ul>';
                                }
                            } else {
                                echo "Currently no files in the trash.";
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

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="js/restore_file.js"></script>
    <script src="js/delete_file_permanently.js"></script>
    <script src="js/restore_folder.js"></script>
    <script src="js/delete_folder_permanently.js"></script>
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

    <?php include "layout/scripts.php"; ?>
</body>

</html>