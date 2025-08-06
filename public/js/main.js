/*
    Main javascript
    @Author Yves Ponchelet
    @Version 0.1
    @Creation date: 05/09/2023
    @Last update: 01/01/2024
*/

$(document).ready(function() {
    $("#alert-success .close").click(function () {
        $("#alert-success").removeClass('show');
    });

    $(".btn-url-img").click(function () {
        var $img = $(this).closest(".figure").find("img");
        var imageURL = $img.attr("src");
        
        var absoluteURL = new URL(imageURL, window.location.origin);

        copyToClipboard(absoluteURL.href);
    });

    function copyToClipboard(text) {
        var textarea = document.createElement("textarea");

        textarea.value = text;

        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
    }

    $('.form-check-input').change(function () {
        var hiddenField = $(this).next('.check-hidden');

        hiddenField.val(this.checked ? '1' : '0');
    });
});