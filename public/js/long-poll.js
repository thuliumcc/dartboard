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
        dataType: "json"
    }).always(function (events, textStatus) {
        if (textStatus != 'abort') {
            $.each(events, function () {
                logMessage("Event: " + this.name + ", params: " + JSON.stringify(this.params), "debug");
                eventBus.trigger(this.name, this.params);
            });
            longPoll();
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        var response = jqXHR.responseText || '(null)';
        var msg = "long_pool failed! status: '" + textStatus + "', errorThrown: '" + errorThrown + "'. Response text: '" + response + "'.";
        logMessage(msg);
    });
}
$(function () {
    longPoll();
});

window.onbeforeunload = function () {
    if (xhr) {
        xhr.abort();
    }
};
