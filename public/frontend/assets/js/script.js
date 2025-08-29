const site_url = "http://127.0.0.1:8000/";

$("body").on("keyup", "#search", function () {
    let text = $("#search").val();

    if (text.length > 0) {
        $.ajax({
            data: { search: text },
            url: site_url + "search-product",
            type: "POST",
            beforeSend: function (request) {
                return request.setRequestHeader(
                    "X-CSRF-TOKEN",
                    $('meta[name="csrf-token"]').attr("content")
                );
            },
            success: function (reault) {
                $("#searchProducts").html(reault);
            },
        });
    } //end if

    if (text.length < 1) {
        $("#searchProducts").html("");
    }
});
