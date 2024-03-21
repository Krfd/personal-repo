$(document).ready(function () {
    $("#createFolder").submit(function (e) {
        e.preventDefault();
        var addFolder = $(this).serialize();

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

        $.ajax({
            type: "POST",
            url: "folderFunction/addFolder.php",
            data: addFolder,
            beforeSend: function () {
                $(".folderButton").attr("disabled", "disabled");
            },
            afterSend: function () {
                $(".folderButton").attr("disabled", false);
            },
            success: function (response) {
                switch (response) {
                    case "success":
                        Toast.fire({
                            icon: "success",
                            iconColor: "#28a745",
                            title: "Folder added successfully!",
                        }).then(() => {
                            location.reload();
                        });
                        break;
                    case "error":
                        Toast.fire({
                            icon: "success",
                            iconColor: "#dc3545",
                            title: "Unable to add folder!",
                        }).then(() => {
                            location.reload();
                        });
                        break;
                    case "invalid":
                        Toast.fire({
                            icon: "error",
                            iconColor: "#dc3545",
                            title: "Invalid CSRF Token!",
                        }).then(() => {
                            location.reload();
                        });
                        break;
                    default:
                        Toast.fire({
                            icon: "error",
                            iconColor: "#dc3545",
                            title: response,
                        }).then(() => {
                            location.reload();
                        });
                }
            },
        });
        return false;
    });
});
