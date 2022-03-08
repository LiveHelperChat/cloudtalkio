(function() {

    function chatLoaded(chat_id) {
        $('#chat-cloudtalk-invitation-btn-'+chat_id+',#chat-cloudtalk-invitation-btn-right-'+chat_id).click(function(event){
            event.preventDefault();
            event.stopPropagation();
            lhinst.addmsgadmin(chat_id,'!cloudtalk --silent --arg ' + $(this).attr('data-phone'));
        });

        $('#chat-cloudtalk-updatenumber-btn-right-'+chat_id).click(function(event){
            event.preventDefault();
            event.stopPropagation();
            lhinst.addmsgadmin(chat_id,'!cloudtalk --silent --arg ' + $(this).attr('data-phone') + ' --arg updatephone');
        });

        $('#chat-cloudtalk-direct-'+chat_id).click(function(event){
            event.preventDefault();
            event.stopPropagation();
            lhinst.addmsgadmin(chat_id,'!cloudtalkdirect --silent --arg ' + $(this).attr('data-phone'));
        });

        $('#chat-cloudtalk-editphone-btn-right-'+chat_id).click(function(event){
            event.preventDefault();
            event.stopPropagation();
            lhc.revealModal({'url':WWW_DIR_JAVASCRIPT+'cloudtalkio/updatephoneoperator/'+chat_id});
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

    function cancelCall(msg_id) {
        $.getJSON(WWW_DIR_JAVASCRIPT + 'cloudtalkio/cancelcall/' + msg_id, function(data) {
            $('#status-call-'+msg_id).html(data.content);
        })
    }

    ee.addListener('chatTabInfoReload', chatLoaded);
    ee.addListener('adminChatLoaded', chatLoaded);
    ee.addListener('cloudtalk.monitor_call', callStarted);
    ee.addListener('cloudtalk.cancel_call', cancelCall);

})();

