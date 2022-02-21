<?php

erLhcoreClassRestAPIHandler::setHeaders();
erTranslationClassLhTranslation::$htmlEscape = false;

$db = ezcDbInstance::get();
$db->beginTransaction();

try {

    $message = erLhcoreClassModelmsg::fetch($Params['user_parameters']['msg_id']);

    $chat = erLhcoreClassModelChat::fetch($Params['user_parameters']['chat_id']);

    if (!($chat instanceof erLhcoreClassModelChat)) {
        throw new Exception('Chat not found!!');
    }

    if (!($message instanceof erLhcoreClassModelmsg)) {
        throw new Exception('Message not found!');
    }

    if ($chat->hash != $Params['user_parameters']['hash']) {
        throw new Exception('Invalid hash!');
    }

    if ($chat->phone == '') {
        echo json_encode(['status' => 'missing_phone']);
        exit;
    }

    $status = false;

    \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::makeDirectCall([
        'status' => & $status,
        'chat' => & $chat,
        'msg'  => & $message,
        'init' => 'visitor',
        'params_dispatch' => [
            'caller_user_id' => ($message->user_id > 0 ? $message->user_id : $chat->user_id),
            'caller_user_class' => 'erLhcoreClassModelUser'
        ]
    ]);

    // Update meta Meta Message
    if ($status == true) {
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($message->id, 'start_sync', $message);
        echo json_encode(['status' => 'start_sync']);
    } else {
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($message->id, ['status' => 'failure', 'failure_reason' => 'API Call has failed!'], $message);
        echo json_encode(['status' => 'failure']);
    }

    $chat->operation_admin = "lhinst.updateMessageRowAdmin({$chat->id},{$message->id});\n";
    $chat->updateThis(['update' => ['operation_admin']]);

    $db->commit();

    // For NodeJS
    erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.message_updated', array('chat' => & $chat, 'msg' => $message));

    // For operation
    erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.added_operation', array('chat' => & $chat));

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode($e->getMessage());
    $db->rollback();

    // Log error
    \erLhcoreClassLog::write(
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
}

exit;
?>