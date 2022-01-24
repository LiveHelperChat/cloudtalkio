(function() {

    function chatLoaded(chat_id){
        $('#chat-cloudtalk-invitation-btn-'+chat_id+',#chat-cloudtalk-invitation-btn-right-'+chat_id).click(function(event){
            event.preventDefault();
            event.stopPropagation();
            lhinst.addmsgadmin(chat_id,'!cloudtalk --silent');
        });

        $('#chat-cloudtalk-direct-'+chat_id).click(function(event){
            event.preventDefault();
            event.stopPropagation();
            lhinst.addmsgadmin(chat_id,'!cloudtalkdirect --silent');
        });
    }

    function callStarted(msg_id) {
        $.getJSON(WWW_DIR_JAVASCRIPT + 'cloudtalkio/monitorcall/' + msg_id, function(data) {
            $('#status-call-'+msg_id).html(data.content);
            if (
                data.status == 'start_sync' ||
                data.status == 'call_started' ||
                data.status == 'answered'
            ) {
                setTimeout(function() {
                    callStarted(msg_id);
                },2000);
            }
        })
    }

    ee.addListener('chatTabInfoReload', chatLoaded);
    ee.addListener('adminChatLoaded', chatLoaded);
    ee.addListener('cloudtalk.monitor_call', callStarted);

})();

