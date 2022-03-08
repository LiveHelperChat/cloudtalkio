<?php

// Set new chat owner
$currentUser = erLhcoreClassUser::instance();

$chat = erLhcoreClassModelChat::fetch($Params['user_parameters']['id']);

$tpl = erLhcoreClassTemplate::getInstance('lhcloudtalk/updatephoneoperator.tpl.php');

session_write_close();

if ( erLhcoreClassChat::hasAccessToRead($chat) && $currentUser->hasAccessTo('lhchat','modifychatcore') ) {

    if (ezcInputForm::hasPostData()) {

        $definition = array(
            'UserPhone' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
        );

        $form = new ezcInputForm( INPUT_POST, $definition );
        $Errors = array();

        $currentUser = erLhcoreClassUser::instance();

        if (!isset($_SERVER['HTTP_X_CSRFTOKEN']) || !$currentUser->validateCSFRToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
            $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/startchat','Invalid CSRF token!');
        }

        if ($form->hasValidData( 'UserPhone' ) ) {
            $chat->phone = $form->UserPhone;
        }

        if (count($Errors) == 0) {

            $chat->saveThis(array('update' => array('phone')));

            erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.modified', array('chat' => & $chat, 'params' => $Params));

            $tpl->set('chat_updated',true);

        } else {
            $tpl->set('errors',$Errors);
        }

    }

    $tpl->set('chat',$chat);
}

echo $tpl->fetch();
exit;

?>