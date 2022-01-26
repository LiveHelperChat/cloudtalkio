<?php
$tpl = erLhcoreClassTemplate::getInstance('lhcloudtalk/newphone.tpl.php');

$item = new \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoPhoneNumber();

$tpl->set('item',$item);

if (ezcInputForm::hasPostData()) {

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
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk', 'New phone')
    )
);

?>