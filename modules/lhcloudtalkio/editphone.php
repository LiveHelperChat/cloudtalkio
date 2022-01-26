<?php

$tpl = erLhcoreClassTemplate::getInstance('lhcloudtalk/editphone.tpl.php');

$item = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoPhoneNumber::fetch($Params['user_parameters']['id']);

if (ezcInputForm::hasPostData()) {

    if (isset($_POST['Cancel_action'])) {
        erLhcoreClassModule::redirect('cloudtalkio/phonenumbers');
        exit ;
    }

    $Errors = \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatValidator::validatePhone($item);

    if (count($Errors) == 0) {
        try {
            $item->saveThis();
            erLhcoreClassModule::redirect('cloudtalkio/phonenumbers');
            exit;

        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }

    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->setArray(array(
    'item' => $item,
));

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('cloudtalkio/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'CloudTalk')
    ),
    array (
        'url' =>erLhcoreClassDesign::baseurl('cloudtalkio/phonenumbers'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','Phone Numbers')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk', 'Edit phone')
    )
);

?>