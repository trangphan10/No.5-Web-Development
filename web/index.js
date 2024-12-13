$(function() {
    $("#start-time, #end-time").timepicker({
        timeFormat: "HH:mm",
        interval: 30,
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
});
