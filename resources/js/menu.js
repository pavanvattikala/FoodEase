//on document ready
document.addEventListener("DOMContentLoaded", function () {
    $("#category").select2();

    $(document).on("select2:open", () => {
        document.querySelector(".select2-search__field").focus();
    });

    alert($('input[name="type"]:checked').val());
    if ($('input[name="type"]:checked').val() === "stock") {
        $("#quantity_input").show();
    } else {
        $("#quantity").val(0);
        $("#quantity_input").hide();
    }

    $('input[name="type"]').change(function () {
        console.log($(this).val());
        if ($(this).val() === "stock") {
            $("#quantity_input").show();
        } else {
            $("#quantity").val(0);
            $("#quantity_input").hide();
        }
    });
});
