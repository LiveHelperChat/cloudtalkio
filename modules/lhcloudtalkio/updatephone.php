<?php

erLhcoreClassRestAPIHandler::setHeaders();
erTranslationClassLhTranslation::$htmlEscape = false;

$db = ezcDbInstance::get();
$db->beginTransaction();

include_once 'extension/cloudtalkio/vendor/autoload.php';

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

    if ($Params['user_parameters_unordered']['mode'] == 'updatephone') {
        $payload = json_decode(file_get_contents('php://input'),true);
        $chat->phone = str_replace(' ','',$payload['phone']);

        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        $invalidMessage = '';
        try {
            $cbNumberProto = $phoneUtil->parse($chat->phone);
            if ($phoneUtil->isValidNumber($cbNumberProto)) {
                $chat->phone = $phoneUtil->format($cbNumberProto, \libphonenumber\PhoneNumberFormat::E164);
            } else {
                $invalidMessage = erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Your phone number does not look as valid phone number!');
            }
        } catch (\libphonenumber\NumberParseException $e) {
            $invalidMessage = $e->getMessage();
        }

        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($message->id, [
            'status' => 'invite',
            'status_sub' => (empty($invalidMessage) ? 'updated_phone' : 'invalid_phone'),
            'message_validation' => $invalidMessage,
            'phone' => $chat->phone
        ], $message);

        echo json_encode(['success' => true]);

        if (empty($invalidMessage)){
            $chat->operation_admin = "lhinst.updateMessageRowAdmin({$chat->id},{$message->id});\nlhinst.updateVoteStatus({$chat->id});\n";
        } else {
            $chat->operation_admin = "lhinst.updateMessageRowAdmin({$chat->id},{$message->id});\n";
        }

        $chat->updateThis(['update' => ['operation_admin','phone']]);

        // For node js to update main attribute
        erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.update_main_attr', array('chat' => & $chat));

    } else if ($Params['user_parameters_unordered']['mode'] == 'cancelphone') {
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($message->id, ['status' => 'invite', 'status_sub' => 'pending_update'], $message);
        echo json_encode(['success' => true]);
        $chat->operation_admin = "lhinst.updateMessageRowAdmin({$chat->id},{$message->id});\n";
        $chat->updateThis(['update' => ['operation_admin']]);
    } else if ($Params['user_parameters_unordered']['mode'] == 'editphone') {
        \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($message->id, ['status' => 'updatephone', 'status_sub' => 'pending_update'], $message);
        echo json_encode(['status' => 'updatephone']);
        $chat->operation_admin = "lhinst.updateMessageRowAdmin({$chat->id},{$message->id});\n";
        $chat->updateThis(['update' => ['operation_admin']]);
    }

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