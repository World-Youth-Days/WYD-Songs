$(document).ready(function() {
    $("#select-from").change(function() {
        if($("#select-to").val()!="--") getAvailableSongs();
    });
    $("#select-to").change(function() {
        if($("#select-from").val()!="--") getAvailableSongs();
    });
    function getAvailableSongs() {
        $.get("availableSongs.php?from="+$("#select-from").val()+"&to="+$("#select-to").val(), function(data) {
            $("#select-song").html(data);
        });
    }
});