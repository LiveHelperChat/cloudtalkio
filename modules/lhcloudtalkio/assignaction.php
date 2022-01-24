<?php

if (!isset($_SERVER['HTTP_X_CSRFTOKEN']) || !$currentUser->validateCSFRToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
    die('Invalid CSRF Token');
    exit;
}

if ($currentUser->getUserID() == $Params['user_parameters']['id']) {
    $nativeUser = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoAgentNative::findOne(['filter' => ['email' => $currentUser->getUserData()->email]]);
    $nativeUser->user_id = $currentUser->getUserID();
    $nativeUser->saveThis();
}

exit;
?>