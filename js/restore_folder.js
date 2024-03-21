$(document).ready(function () {
    $(".restore_folder").click(function (e) {
        e.preventDefault();
        var id = $(this).attr("data-folder-id");
        var folder = $(this).attr("data-folder");
        var client_id = $(this).attr("data-client-id");

        Swal.fire({
            title: "Are you sure?",
            text: "You want to restore this folder and its content?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Restore",
            confirmButtonColor: "#4e73df",
            cancelButtonText: "Cancel",
            cancelButtonColor: "#dc3545",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: "./restore_folder.php",
                    data: {
                        id: id,
                        folder: folder,
                        client_id: client_id,
                    },
                    success: function (response) {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top",
                            customClass: {
                                popup: "colored-toast",
                            },
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: true,
                        });

                        switch (response) {
                            case "success":
                                Toast.fire({
                                    icon: "success",
                                    title: "Folder has been restored successfully.",
                                    iconColor: "#28a745",
                                }).then(() => {
                                    var delay = 100;
                                    setTimeout(function () {
                                        location.reload();
                                    }, delay);
                                });
                                break;
                            case "error":
                                Toast.fire({
                                    icon: "error",
                                    title: "Folder has not been restored. Please try again.",
                                    iconColor: "#dc3545",
                                }).then(() => {
                                    location.reload();
                                });
                                break;
                            default:
                                Toast.fire({
                                    icon: "error",
                                    title: "Something went wrong. Please try again.",
                                    iconColor: "#dc3545",
                                }).then(() => {
                                    location.reload();
                                });
                        }
                    },
                });
            }
        });
    });
});
