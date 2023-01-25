<?php

$tpl = erLhcoreClassTemplate::getInstance('lhcloudtalk/rawjson.tpl.php');

$item = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::fetch($Params['user_parameters']['id']);
$tpl->set('item',$item);

echo $tpl->fetch();
exit;

?>