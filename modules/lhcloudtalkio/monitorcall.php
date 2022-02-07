<?php
header ( 'content-type: application/json; charset=utf-8' );

$message = erLhcoreClassModelmsg::fetch($Params['user_parameters']['id']);

$tpl = erLhcoreClassTemplate::getInstance('lhgenericbot/message/content/call_status_admin.tpl.php');
$tpl->set('metaMessage',$message->meta_msg_array['content']['cloudtalk']);
$tpl->set('msg',$message->getState());

echo json_encode(['status' => $message->meta_msg_array['content']['cloudtalk']['status'], 'content' => $tpl->fetch()]);

exit;