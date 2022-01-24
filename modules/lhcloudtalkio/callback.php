<?php

use \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoAgentNative;
use \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall;

$data = json_decode(file_get_contents('php://input'),true);

// To log always independently on debug mode
// \erLhcoreClassLog::write(json_encode($data));

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

    if (!isset($data['contact_id'])) {
        throw new Exception('`contact_id` attribute not set!');
    }

    if (!isset($data['external_number'])) {
        throw new Exception('`external_number` attribute not set!');
    }

    if (!isset($data['call_uuid'])) {
        throw new Exception('`call_uuid` attribute not set!');
    }

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

    // Perhaps there is a call by call_uuid
    if (!($callOngoing instanceof erLhcoreClassModelCloudTalkIoCall)) {
        $callOngoing = erLhcoreClassModelCloudTalkIoCall::findOne(['filter' => ['call_uuid' => $data['call_uuid']]]);
    }

    if (!($callOngoing instanceof erLhcoreClassModelCloudTalkIoCall)) {
        throw new \Exception('call not found!');
    }

    $callOngoing->call_uuid = $data['call_uuid'];

    if ($data['action'] == 'call_started') {
        $callOngoing->status = erLhcoreClassModelCloudTalkIoCall::STATUS_STARTED;
        $callOngoing->direction = $data['direction'] == 'outgoing' ? erLhcoreClassModelCloudTalkIoCall::DIRECTION_OUTBOUND : erLhcoreClassModelCloudTalkIoCall::DIRECTION_INCOMMING;
        $callOngoing->updateThis(['update' => ['status','call_uuid','direction']]);
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($callOngoing->msg_id,'call_started');
    }

    if ($data['action'] == 'ringing_on_agent') {
        $callOngoing->status = erLhcoreClassModelCloudTalkIoCall::STATUS_RINGING_AGENT;
        $callOngoing->updateThis(['update' => ['status','call_uuid']]);
    }

    if ($data['action'] == 'answered') {
        $callOngoing->status = erLhcoreClassModelCloudTalkIoCall::STATUS_ANSWERED;
        $callOngoing->status_outcome = erLhcoreClassModelCloudTalkIoCall::STATUS_OUTCOME_ANSWERED;
        $callOngoing->answered_at = time();
        $callOngoing->updateThis(['update' => ['status', 'call_uuid', 'status_outcome', 'answered_at']]);
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
        ]]);
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($callOngoing->msg_id,['status' => 'ended', 'ended_at' => time()]);
    }

} catch (\Exception $e) {

    if (\erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' ) == true){
        \erLhcoreClassLog::write($e->getMessage().' | '. json_encode($data));
    }
}


exit;

?>