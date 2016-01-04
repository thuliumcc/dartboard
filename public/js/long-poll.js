var eventBus = {
    trigger: function (event, params) {
        $(eventBus).trigger(event, params);
    },
    bind: function (event, handler) {
        $(eventBus).bind(event, handler);
    }
};

var xhr;

function longPoll() {
    xhr = $.ajax({
        method: "POST",
        url: "/dartboard/long_poll",
        dataType: "json",
    }) .done(function( events ) {
        $.each(events, function() {
            eventBus.trigger(this.name, this.params);
        });
        longPoll();
    });
}

longPoll();

$( window ).unload(function() {
    if (xhr) {
        xhr.abort();
    }
});