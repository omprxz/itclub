function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight) + "px";
    }

$(".chat-app-send").on("click", function () {
    $(".chat-app-send i").toggleClass("animateSend");
    setTimeout(() => {
        $(".chat-app-send i").toggleClass("animateSend");
    }, 2000)
});

new ClipboardJS(".copy-response i")
