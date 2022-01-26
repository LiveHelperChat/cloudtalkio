<?php

use \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoAgentNative;
use \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall;

$data = json_decode(file_get_contents('php://input'),true);

// Incomming call
//$data = json_decode('{"call_uuid":"2f5fb737-0617-4c5c-b276-1852d8d3a9a7","internal_number":37052143590,"direction":"incoming","contact_id":256079987,"agent_id":null,"external_number":37065272274,"action":"call_started","lhc_key":"my_secret_key"}',true);
//$data = json_decode('{"call_uuid":"2f5fb737-0617-4c5c-b276-1852d8d3a9a7","internal_number":37052143590,"direction":"incoming","contact_id":256079987,"agent_id":130831,"external_number":37065272274,"action":"ringing_on_agent","lhc_key":"my_secret_key"}',true);
//$data = json_decode('{"call_uuid":"2f5fb737-0617-4c5c-b276-1852d8d3a9a7","internal_number":37052143590,"direction":"incoming","contact_id":256079987,"agent_id":null,"external_number":37065272274,"action":"ended","waiting_time":17,"talking_time":0,"wrapup_time":0,"recording_url":"https:\/\/my.cloudtalk.io\/pub\/r\/MTIzOTE1NDE3\/NGU0N2ZkZjVjOGEzODIzMmM1MDQ2MmVjNjc3MGNhNmZkODMxYjY0YWRkZDY1Y2MzNTVhYmYwMGRjMjFjZDQxZg%3D%3D.wav","call_id":123915417,"lhc_key":"my_secret_key"}',true);


// {"call_uuid":"54891dbc-e140-43c4-9ab3-1859a9bb7b87","internal_number":37052143590,"direction":"outgoing","contact_id":256511755,"agent_id":null,"external_number":37065272274,"action":"call_started","lhc_key":"my_secret_key"}
// {"call_uuid":"54891dbc-e140-43c4-9ab3-1859a9bb7b87","internal_number":37052143590,"direction":"outgoing","contact_id":256511755,"agent_id":130831,"external_number":37065272274,"action":"ended","waiting_time":20,"talking_time":0,"wrapup_time":0,"recording_url":"https:\/\/my.cloudtalk.io\/pub\/r\/MTI0NDQ4OTcz\/MjQwZmU0YTA0NmZlOTBhMTViYjUyYWM5ZDQzMzE5NTgzOTI3Y2E1ZDNkOGRiYmE1ODVhZTBkZWMzNmM5YWQ5Yw%3D%3D.wav","call_id":124448973,"lhc_key":"my_secret_key"}



// To log always independently on debug mode
\erLhcoreClassLog::write(json_encode($data));

try {

    if (!isset($data['action'])) {
        throw new \Exception('Missing `action` parameter');
    }

    if (!isset($data['lhc_key']) || $data['lhc_key'] != erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->settings['lhc_key']) {
        throw new \Exception('Invalid `lhc_key` parameter');
    }

    // Agent status update
    if ($data['action'] == 'agent_status_changed') {

        $existingAgent = erLhcoreClassModelCloudTalkIoAgentNative::findOne(['filter' => ['cloudtalk_user_id' => $data['id']]]);

        if (!($existingAgent instanceof erLhcoreClassModelCloudTalkIoAgentNative)) {
            exit;
        }

        $existingAgent->availability_status = $data['status'];
        $existingAgent->updateThis(['update' => ['availability_status']]);
        exit;
    }

    if (!isset($data['call_uuid'])) {
        throw new Exception('`call_uuid` attribute not set!');
    }

    if (!isset($data['external_number'])) {
        throw new Exception('`external_number` attribute not set!');
    }

    if (!isset($data['contact_id']) || $data['contact_id'] == null) {
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::contactByIncommingCall($data);
    } elseif ( // Contact exists but call, does not so create a call
        ($data['action'] == 'call_started' || $data['action'] == 'ringing_on_agent') &&
        $data['direction'] == 'incoming' &&
        erLhcoreClassModelCloudTalkIoCall::getCount(['filter' => ['call_uuid' => $data['call_uuid']]]) == 0) {
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::callByIncommingCall($data);
    }

    $callOngoing = erLhcoreClassModelCloudTalkIoCall::findOne(['filter' => ['call_uuid' => $data['call_uuid']]]);

    // Perhaps there is a call by call_uuid
    if (!($callOngoing instanceof erLhcoreClassModelCloudTalkIoCall)) {
        $callOngoing = erLhcoreClassModelCloudTalkIoCall::findOne([
            'filter' => [
                'contact_id' => $data['contact_id'],
                // call_started event not always has an agent assigned
                // 'cloudtalk_user_id' => $data['agent_id'], "agent_id":null,"external_number":37065272274,"action":"call_started"
                'phone' => $data['external_number'],
                'call_id' => 0 // This has number only if the call ends
            ],
            'filternot' => [
                'status_call' => erLhcoreClassModelCloudTalkIoCall::STATUS_PENDING_CALL_SET
            ],
            'filterin' => [
                'call_uuid' => ['',$data['call_uuid']]
            ],
            'sort' => 'id DESC'
        ]);
    }

    if (!($callOngoing instanceof erLhcoreClassModelCloudTalkIoCall)) {
        // Agent made a call directly to a visitor
        if ($data['direction'] == 'outgoing') {

            // Create a call record by outgoing call
            if (!isset($data['contact_id']) || $data['contact_id'] == null) {
                \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::contactByIncommingCall($data);
            } elseif ( // Contact exists but call, does not so create a call
                ($data['action'] == 'call_started' || $data['action'] == 'ringing_on_agent') &&
                erLhcoreClassModelCloudTalkIoCall::getCount(['filter' => ['call_uuid' => $data['call_uuid']]]) == 0) {
                \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::callByIncommingCall($data);
            }

            $callOngoing = erLhcoreClassModelCloudTalkIoCall::findOne(['filter' => ['call_uuid' => $data['call_uuid']]]);

            if (!($callOngoing instanceof erLhcoreClassModelCloudTalkIoCall)) {
                throw new \Exception('call not found!');
            }

        } else {
            throw new \Exception('call not found!');
        }
    }

    $callOngoing->call_uuid = $data['call_uuid'];

    if ($callOngoing->cloudtalk_user_id == 0 && is_numeric($data['agent_id']) && $data['agent_id'] > 0) {
        $callOngoing->cloudtalk_user_id = $data['agent_id'];
        $agent = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoAgentNative::findOne(['filter' => ['cloudtalk_user_id' => $callOngoing->cloudtalk_user_id]]);
        if (is_object($agent)) {
            $callOngoing->user_id = $agent->user_id;
        }
    }

    if ($data['action'] == 'call_started') {
        $callOngoing->status = erLhcoreClassModelCloudTalkIoCall::STATUS_STARTED;
        $callOngoing->direction = $data['direction'] == 'outgoing' ? erLhcoreClassModelCloudTalkIoCall::DIRECTION_OUTBOUND : erLhcoreClassModelCloudTalkIoCall::DIRECTION_INCOMMING;
        $callOngoing->updateThis(['update' => ['status','call_uuid','direction','cloudtalk_user_id','user_id']]);
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($callOngoing->msg_id,'call_started');
    }

    if ($data['action'] == 'ringing_on_agent') {
        $callOngoing->status = erLhcoreClassModelCloudTalkIoCall::STATUS_RINGING_AGENT;
        $callOngoing->updateThis(['update' => ['status', 'call_uuid', 'cloudtalk_user_id','user_id']]);
    }

    if ($data['action'] == 'answered') {
        $callOngoing->status = erLhcoreClassModelCloudTalkIoCall::STATUS_ANSWERED;
        $callOngoing->status_outcome = erLhcoreClassModelCloudTalkIoCall::STATUS_OUTCOME_ANSWERED;
        $callOngoing->answered_at = time();
        $callOngoing->updateThis(['update' => ['status', 'call_uuid', 'status_outcome', 'answered_at','user_id','cloudtalk_user_id']]);
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($callOngoing->msg_id,['status' => 'answered', 'answered_at' => time()]);
    }

    if ($data['action'] == 'ended') {
        $callOngoing->status = erLhcoreClassModelCloudTalkIoCall::STATUS_ENDED;
        $callOngoing->status_call = erLhcoreClassModelCloudTalkIoCall::STATUS_PENDING_CALL_SET;
        $callOngoing->waiting_time = $data['waiting_time'];
        $callOngoing->talking_time = $data['talking_time'];
        $callOngoing->wrapup_time = $data['wrapup_time'];
        $callOngoing->recording_url = $data['recording_url'];
        $callOngoing->call_id = $data['call_id'];
        $callOngoing->updateThis(['update' => [
            'waiting_time',
            'talking_time',
            'wrapup_time',
            'recording_url',
            'status',
            'status_call',
            'call_uuid',
            'call_id',
            'user_id',
            'cloudtalk_user_id'
        ]]);
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($callOngoing->msg_id,['status' => 'ended', 'ended_at' => time()]);
    }

} catch (\Exception $e) {

    print_r($e);
    \erLhcoreClassLog::write($e->getMessage().' | '. json_encode($data));
    /*if (\erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' ) == true){
        \erLhcoreClassLog::write($e->getMessage().' | '. json_encode($data));
    }*/
}


exit;

?>