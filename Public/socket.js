socket = function () {
    var ws;
    return {
        init: function (url) {
            ws =  new WebSocket("ws://"+url);
            ws.onopen= socket.onOpen;
        },
        onOpen:function () {

        },
        onClose:function () {

        },
        onMessage:function () {

        },
        send:function (msg) {
            ws.send(msg);
        },
        close:function () {
            ws.close();
        }
    };
}();