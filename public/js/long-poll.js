var eventBus = {
    trigger: function (event, params) {
        $(eventBus).trigger(event, params);
    },
    subscribe: function (event, handler) {
        $(eventBus).subscribe(event, handler);
    }
};

var xhr;

function longPoll() {
    xhr = $.ajax({
        method: "POST",
        url: "/dartboard/long_poll",
        dataType: "json",
    }) .done(function( response ) {
        longPoll();
    });
}

longPoll();

$( window ).unload(function() {
    if (xhr) {
        xhr.abort();
    }
});