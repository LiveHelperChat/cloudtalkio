<?php

header ( 'content-type: application/json; charset=utf-8' );

$message = erLhcoreClassModelmsg::fetch($Params['user_parameters']['id']);

$chat = erLhcoreClassModelChat::fetch($message->chat_id);

\LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($message->id, 'canceled', $message);

$tpl = erLhcoreClassTemplate::getInstance('lhgenericbot/message/content/call_status_admin.tpl.php');
$tpl->set('metaMessage',$message->meta_msg_array['content']['cloudtalk']);

// Update message row for a visitor
$chat->operation .= "lhinst.updateMessageRow({$message->id});\n";
$chat->updateThis(array('update' => array('operation')));

// For NodeJS
erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.message_updated', array('chat' => & $chat, 'msg' => $message));

// For operation
\erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.added_operation', array('chat' => & $chat));

echo json_encode(['status' => $message->meta_msg_array['content']['cloudtalk']['status'], 'content' => $tpl->fetch()]);
exit;