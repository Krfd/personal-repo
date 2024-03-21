$(document).ready(function () {
    $(".delete_folder").click(function (e) {
        e.preventDefault();
        var id = $(this).attr("data-folder-id");
        var folder = $(this).attr("data-folder");
        var client_id = $(this).attr("data-client-id");

        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this folder and it's content permanently?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Delete",
            confirmButtonColor: "#dc3545",
            cancelButtonText: "Cancel",
            cancelButtonColor: "#67CC65",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: "./permanent_delete_folder.php",
                    data: { id: id, folder: folder, client_id: client_id },
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
                                    title: "Folder has been deleted successfully.",
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
                                    title: "Folder has not been deleted. Please try again.",
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
