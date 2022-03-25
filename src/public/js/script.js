$(".table").on("click", "#delete", function (e) {
    e.preventDefault();
    // console.log("dasfs");
    user_id = $(this).data("user_id");
    console.log(user_id);
    $.ajax({
        method: "POST",
        url: "http://localhost:8080/delete",
        data: { blog_id: blog_id },
        // dataType: "JSON",
    }).done(function (data) {
        console.log("success");
        // console.log(data);
        window.location.reload();
    });
});