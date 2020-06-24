<script>
    var form = $("#form-menu");
    $("#btnSubmitForm").hide();
    $("#btnCancelForm").hide();
    $("#btnSubmitForm").click(function (event) {
        event.preventDefault();
        form.submit();
    });
    $("#btnCancelForm").click(function (event) {
        event.preventDefault();
        $('#form-menu *').filter(':input').each(function () {
            $(this).prop("disabled", true);
        });
        $(this).hide();
        $("#btnSubmitForm").hide();
        $("#btnAddSubmenu").show();
        $("#btnEditForm").show();
        $("#btnDelete").show();
    });
    $("#btnEditForm").click(function (event) {
        event.preventDefault();
        $('#form-menu *').filter(':input').each(function () {
            $(this).prop("disabled", false);
        });
        $(this).hide();
        $("#btnDelete").hide();
        $("#btnAddSubmenu").hide();
        $("#btnSubmitForm").show();
        $("#btnCancelForm").show();
    });
    $("#btnSubmitCreateForm").click(function (event) {
        event.preventDefault();
        form.submit();
    });

    $("#btnDelete").click(function (event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure ?',
            text: "Delete this sidebar menu?",
            type: 'warning',
            allowOutsideClick: false,
            showCancelButton: true,
            confirmButtonColor: '#00CFE8',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
            preConfirm: (destroy) => {
                return $.ajax({
                    url: '{!! route("backend::sidebars.delete", $model->id ) !!}',
                    type: 'POST',
                    success: function () {
                        Swal.fire({
                            title: 'Success',
                        });
                    },
                    error: function (result) {
                        Swal.fire({
                            icon: 'error',
                            text: `Something when wrong, error code : ` + data.status,
                            type: 'warning',
                        });
                    }
                }).done(function (data) {

                });
            }
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    text: `Success`
                });
            }
        });
        return false;
    });
</script>