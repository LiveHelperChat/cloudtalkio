<?php

if (!$currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    die('Invalid CSFR Token');
    exit;
}

$phone = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoPhoneNumber::fetch($Params['user_parameters']['id']);
$phone->removeThis();

erLhcoreClassModule::redirect('cloudtalkio/phonenumbers');
exit;

?>