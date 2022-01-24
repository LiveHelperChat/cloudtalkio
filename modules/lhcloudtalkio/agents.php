<?php

$tpl = erLhcoreClassTemplate::getInstance('lhcloudtalk/agents.tpl.php');

$settings = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->settings;

$api = new \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChat($settings['ACCESS_KEY_ID'], $settings['ACCESS_KEY_SECRET']);

$paramsQuery = ['limit' => 20];
if (isset($Params['user_parameters_unordered']['page']) && is_numeric($Params['user_parameters_unordered']['page'])) {
    $paramsQuery['page'] = $Params['user_parameters_unordered']['page'];
}

$items = $api->getAgents($paramsQuery);
$tpl->set('items',$items);

$pages = new lhPaginator();
$pages->items_total = $items->responseData->itemsCount;
$pages->translationContext = 'chat/activechats';
$pages->serverURL = erLhcoreClassDesign::baseurl('cloudtalkio/agents');
$pages->setItemsPerPage(20);
$pages->paginate();

$tpl->set('pages',$pages);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('cloudtalkio/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'CloudTalk')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Agents')
    )
);

?>