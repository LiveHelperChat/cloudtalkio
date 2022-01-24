<?php
// Change to your Script identifier
if ($ext == 'cloudtalk-call') : ?>
(function () {
    window.lhcCloudTalk = {};

    window.lhcCloudTalk.startMonitorCall = function(messageId, dispatch, getState, updateMessage) {
        setTimeout(function(){
            window.lhcAxios.post('<?php echo '//' . $_SERVER['HTTP_HOST'] . erLhcoreClassDesign::baseurl('cloudtalkio/checkcallstatus')?>/<?php echo $chat->id,'/',$chat->hash,'/'?>'+messageId, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function(response){
                (response.data.status == 'start_sync' || response.data.status == 'call_started' || response.data.status == 'answered') && window.lhcCloudTalk.startMonitorCall(messageId, dispatch, getState, updateMessage);
                updateMessage({'msg_id' : messageId ,'id' : <?php echo $chat->id?>, 'hash' : '<?php echo $chat->hash?>'})(dispatch, getState);
            })
        },1000);
    };

    window.lhcHelperfunctions.eventEmitter.addListener('cloudtalk-call.init', function (params, dispatch, getState, updateMessage) {

        if (params.action) {
            window.lhcCloudTalk.startMonitorCall(params['msg_id'], dispatch, getState, updateMessage);
            return;
        }

        window.lhcAxios.post('<?php echo '//' . $_SERVER['HTTP_HOST'] . erLhcoreClassDesign::baseurl('cloudtalkio/startacall')?>/<?php echo $chat->id,'/',$chat->hash,'/'?>'+params['msg_id'], {headers : {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function(response) {
            if (
                response.data.status == 'start_sync' ||
                response.data.status == 'call_started' ||
                response.data.status == 'answered' ||
                response.data.status == 'failure'
            ) { // Waiting for operator to accept a call
                window.lhcCloudTalk.startMonitorCall(params['msg_id'], dispatch, getState, updateMessage);
                // Update a widget
                updateMessage({'msg_id' : params['msg_id'] ,'id' : <?php echo $chat->id?>, 'hash' : '<?php echo $chat->hash?>'})(dispatch, getState);
            } else if (response.data.status == 'missing_phone') {
                alert(<?php echo json_encode(erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Seems you have not provided your phone number yet!'))?>);
            }
        })

    });

})();
<?php $extHandled = true; endif; ?>