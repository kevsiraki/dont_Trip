$(".toggle-password").click(function() {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
		$(this).css("opacity", "1")
    } else {
        input.attr("type", "password");
		$(this).css("opacity", "0.6")
    }
});