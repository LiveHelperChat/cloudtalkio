<?php

$tpl = erLhcoreClassTemplate::getInstance('lhcloudtalk/history.tpl.php');

if (isset($_GET['doSearch'])) {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/cloudtalkio/classes/filter/history.php','format_filter' => true, 'use_override' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = true;
} else {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/cloudtalkio/classes/filter/history.php','format_filter' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = false;
}

erLhcoreClassChatStatistic::formatUserFilter($filterParams, 'lhc_cloudtalkio_call');

if (isset($filterParams['filter']['filterin']['lh_chat.dep_id'])) {
    $filterParams['filter']['filterin']['dep_id'] = $filterParams['filter']['filterin']['lh_chat.dep_id'];
    unset($filterParams['filter']['filterin']['lh_chat.dep_id']);
}

$append = erLhcoreClassSearchHandler::getURLAppendFromInput($filterParams['input_form'], false, ['form_action']);


if (isset($Params['user_parameters_unordered']['export']) && $Params['user_parameters_unordered']['export'] == 1) {
    session_write_close();
    $ignoreFields = (new \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall)->getState();
    unset($ignoreFields['id']);
    $ignoreFields = array_keys($ignoreFields);
    \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatValidator::callListExport(\LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::getList(array_merge($filterParams['filter'], array('limit' => 100000, 'offset' => 0, 'ignore_fields' => $ignoreFields))));
    exit;
}

$rowsNumber = null;

$filterWithoutSort = $filterParams['filter'];
unset($filterWithoutSort['sort']);

if (empty($filterWithoutSort)) {
    $rowsNumber = ($rowsNumber = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::estimateRows()) && $rowsNumber > 10000 ? $rowsNumber : null;
}

$pages = new lhPaginator();
$pages->items_total = is_numeric($rowsNumber) ? $rowsNumber : \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::getCount($filterParams['filter']);
$pages->translationContext = 'chat/activechats';
$pages->serverURL = erLhcoreClassDesign::baseurl('cloudtalkio/history').$append;
$pages->setItemsPerPage(60);
$pages->paginate();
$tpl->set('pages',$pages);

if ($pages->items_total > 0) {
    $items = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::getList(array_merge(array('limit' => $pages->items_per_page, 'offset' => $pages->low),$filterParams['filter']));
    $tpl->set('items',$items);
}

$filterParams['input_form']->form_action = erLhcoreClassDesign::baseurl('cloudtalkio/history');
$tpl->set('input',$filterParams['input_form']);
$tpl->set('inputAppend',$append);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('cloudtalkio/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'CloudTalk')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Call history')
    )
);

?>