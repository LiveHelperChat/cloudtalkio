<?php

erLhcoreClassRestAPIHandler::setHeaders();
erTranslationClassLhTranslation::$htmlEscape = false;

$db = ezcDbInstance::get();
$db->beginTransaction();

try {

    $message = erLhcoreClassModelmsg::fetch($Params['user_parameters']['msg_id']);

    $chat = erLhcoreClassModelChat::fetch($Params['user_parameters']['chat_id']);

    if (!($chat instanceof erLhcoreClassModelChat)) {
        throw new Exception(erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Chat not found!'));
    }

    if (!($message instanceof erLhcoreClassModelmsg)) {
        throw new Exception(erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Message not found!'));
    }

    if ($chat->hash != $Params['user_parameters']['hash']) {
        throw new Exception(erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Invalid hash!'));
    }

    $metaMessage = $message->meta_msg_array;

    // Find relevant message
    $callRecord = LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::findOne(['filter' => ['msg_id' => $message->id]]);

    // Stop sync if nothing happens with the call for the 5 minutes and call has started
    if (
        is_object($callRecord) && (
            (($metaMessage['content']['cloudtalk']['status'] == 'start_sync' || $metaMessage['content']['cloudtalk']['status'] == 'call_started') && $callRecord->created_at < time() - 5 * 60) ||
            ($callRecord->status == \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_ENDED && $callRecord->call_id == 0)
        )
    ) {
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($message->id, [
            'status' => 'failure',
            'failure_reason' => erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Time to accept a call has expired!')
        ], $message);
        echo json_encode(['status' => 'failure','reason' => 'expired']);
    } else {
        echo json_encode(['status' => $metaMessage['content']['cloudtalk']['status']]);
    }

    $db->commit();

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode($e->getMessage());
    $db->rollback();

    // Log error
    erLhcoreClassLog::write($e->getTraceAsString(),
        ezcLog::SUCCESS_AUDIT,
        array(
            'source' => 'cloudtalk',
            'category' => 'cloudtalk',
            'line' => __LINE__,
            'file' => __FILE__,
            'object_id' => $Params['user_parameters']['chat_id']
        )
    );
    
}

exit;
?>