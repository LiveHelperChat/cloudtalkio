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
                throw new \Exception('call not found! [outgoing]');
            }

        } else {
            throw new \Exception('call not found! [incoming]');
        }
    }

    if ($callOngoing->phone_from_id == 0 && isset($data['internal_number'])) {
        $phoneNumberInternal = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoPhoneNumber::findOne(['filter' => ['phone' => $data['internal_number']]]);
        if (is_object($phoneNumberInternal)) {
            $callOngoing->phone_from_id = $phoneNumberInternal->id;
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
        $callRefreshed = erLhcoreClassModelCloudTalkIoCall::fetch($callOngoing->id);

        // Sometimes create a contact call takes long time
        // So we want to be sure that status was not changed already
        if ($callRefreshed->status == erLhcoreClassModelCloudTalkIoCall::STATUS_PENDING) {
            $callOngoing->status = erLhcoreClassModelCloudTalkIoCall::STATUS_STARTED;
        } else {
            $callOngoing->status = $callRefreshed->status;
        }

        $callOngoing->direction = $data['direction'] == 'outgoing' ? erLhcoreClassModelCloudTalkIoCall::DIRECTION_OUTBOUND : erLhcoreClassModelCloudTalkIoCall::DIRECTION_INCOMMING;
        $callOngoing->updateThis(['update' => ['status','call_uuid','direction','cloudtalk_user_id','user_id','phone_from_id']]);
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($callOngoing->msg_id,'call_started');
    }

    if ($data['action'] == 'ringing_on_agent') {
        $callOngoing->status = erLhcoreClassModelCloudTalkIoCall::STATUS_RINGING_AGENT;
        $callOngoing->updateThis(['update' => ['status', 'call_uuid', 'cloudtalk_user_id','user_id','phone_from_id']]);
    }

    if ($data['action'] == 'answered') {
        $callOngoing->status = erLhcoreClassModelCloudTalkIoCall::STATUS_ANSWERED;
        $callOngoing->status_outcome = erLhcoreClassModelCloudTalkIoCall::STATUS_OUTCOME_ANSWERED;
        $callOngoing->answered_at = time();
        $callOngoing->updateThis(['update' => ['status', 'call_uuid', 'status_outcome', 'answered_at','user_id','cloudtalk_user_id','phone_from_id']]);
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($callOngoing->msg_id,['status' => 'answered', 'answered_at' => time()]);

        if (isset(erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->settings['control_auto_assign']) && erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->settings['control_auto_assign'] === true) {
            // Turn off to agent auto assignment
            if ($callOngoing->user_id > 0 && ($UserData = \erLhcoreClassModelUser::fetch($callOngoing->user_id)) instanceof \erLhcoreClassModelUser && $UserData->exclude_autoasign == 0) {

                // Auto assignment was changed
                // On call end we know we have to switch back
                $callOngoing->exclude_autoasign = 1;
                $callOngoing->updateThis(['update' => ['exclude_autoasign']]);

                // Change main data
                $UserData->exclude_autoasign = 1;
                $UserData->updateThis(['update' => ['exclude_autoasign']]);

                // Update auto exclude
                $db = ezcDbInstance::get();
                $stmt = $db->prepare('UPDATE lh_userdep SET exclude_autoasign = :exclude_autoasign WHERE user_id = :user_id');
                $stmt->bindValue(':user_id', $UserData->id, PDO::PARAM_INT);
                $stmt->bindValue(':exclude_autoasign', $UserData->exclude_autoasign, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        // Send CueCard
        // CueCard works only if both parties has accepted a call
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::sendCueCard($data, $callOngoing);
    }

    if ($data['action'] == 'ended') {
        $callOngoing->status = erLhcoreClassModelCloudTalkIoCall::STATUS_ENDED;
        $callOngoing->status_call = erLhcoreClassModelCloudTalkIoCall::STATUS_PENDING_CALL_SET;
        $callOngoing->waiting_time = $data['waiting_time'];
        $callOngoing->talking_time = $data['talking_time'];
        $callOngoing->wrapup_time = $data['wrapup_time'];
        $callOngoing->recording_url = $data['recording_url'];
        $callOngoing->call_id = $data['call_id'];

        if (isset(erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->settings['control_auto_assign']) && erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->settings['control_auto_assign'] === true) {
            // Restore back auto assign workflow
            if ($callOngoing->exclude_autoasign == 1) {
                $callOngoing->exclude_autoasign = 0;
                if ($callOngoing->user_id > 0 && ($UserData = \erLhcoreClassModelUser::fetch($callOngoing->user_id)) instanceof \erLhcoreClassModelUser) {
                    // Change main data
                    $UserData->exclude_autoasign = 0;
                    $UserData->updateThis(['update' => ['exclude_autoasign']]);

                    // Update auto exclude
                    $db = ezcDbInstance::get();
                    $stmt = $db->prepare('UPDATE lh_userdep SET exclude_autoasign = :exclude_autoasign WHERE user_id = :user_id');
                    $stmt->bindValue(':user_id', $UserData->id, PDO::PARAM_INT);
                    $stmt->bindValue(':exclude_autoasign', $UserData->exclude_autoasign, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }


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
            'cloudtalk_user_id',
            'exclude_autoasign',
            'phone_from_id'
        ]]);
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($callOngoing->msg_id,['status' => 'ended', 'ended_at' => time()]);
    }

} catch (\Exception $e) {

    // Log error
    \erLhcoreClassLog::write(
        json_encode($data, true).
        $e->getMessage().
        $e->getTraceAsString(),
        \ezcLog::SUCCESS_AUDIT,
        array(
            'source' => 'cloudtalk',
            'category' => 'cloudtalk',
            'line' => __LINE__,
            'file' => __FILE__,
            'object_id' => $Params['user_parameters']['chat_id']
        )
    );

    if (\erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' ) == true){
        \erLhcoreClassLog::write($e->getMessage().' | '. json_encode($data));
    }
}


exit;

?>