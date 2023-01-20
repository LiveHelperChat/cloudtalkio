<?php

$tpl = erLhcoreClassTemplate::getInstance('lhcloudtalk/history.tpl.php');
$tpl->set('default_date',date('Y-m-d',time()-31*24*3600));

// Chats filter
if (isset($_GET['ds'])) {
    $filterParams = erLhcoreClassSearchHandler::getParams(array(
        'customfilterfile' => 'extension/cloudtalkio/classes/filter/history.php',
        'format_filter' => true,
        'use_override' => true,
        'uparams' => $Params['user_parameters_unordered']
    ));
    $filterParams['is_search'] = true;
} else {
    $filterParams = erLhcoreClassSearchHandler::getParams(array(
        'customfilterfile' => 'extension/cloudtalkio/classes/filter/history.php',
        'format_filter' => true,
        'uparams' => $Params['user_parameters_unordered']
    ));
    $filterParams['is_search'] = false;
}

$filterParams['input_form']->form_action = erLhcoreClassDesign::baseurl('cloudtalkio/elastichistory');
$tpl->set('input', $filterParams['input_form']);

$sparams = array(
    'body' => array()
);

$dateFilter = array();

if (trim((string)$filterParams['input_form']->call_id) != '') {
    $sparams['body']['query']['bool']['must'][]['term']['call_id'] = (int)trim($filterParams['input_form']->call_id);
}

if ($filterParams['input_form']->phone != '') {
    $sparams['body']['query']['bool']['must'][]['term']['phone'] = $filterParams['input_form']->phone;
}

/*if (trim($filterParams['input_form']->status) != '') {
    $sparams['body']['query']['bool']['must'][]['term']['status'] = (int)trim($filterParams['input_form']->status);
}*/

if (trim($filterParams['input_form']->chat_call) != '') {
    $sparams['body']['query']['bool']['must_not'][]['term']['chat_id'] = 0;
}

if (trim((string)$filterParams['input_form']->direction) != '') {
    $sparams['body']['query']['bool']['must'][]['term']['direction'] = (int)trim($filterParams['input_form']->direction);
}

if (trim((string)$filterParams['input_form']->status_outcome) != '') {
    $sparams['body']['query']['bool']['must'][]['term']['status_outcome'] = (int)trim($filterParams['input_form']->status_outcome);
}

if (isset($filterParams['input']->group_ids) && is_array($filterParams['input']->group_ids) && !empty($filterParams['input']->group_ids)) {
    erLhcoreClassChat::validateFilterIn($filterParams['input']->group_ids);
    $db = ezcDbInstance::get();
    $stmt = $db->prepare('SELECT user_id FROM lh_groupuser WHERE group_id IN (' . implode(',',$filterParams['input']->group_ids) .')');
    $stmt->execute();
    $userIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (!empty($userIds)) {
        $sparams['body']['query']['bool']['must'][]['terms']['user_id'] = $userIds;
    }
}

if (isset($filterParams['input']->department_group_ids) && is_array($filterParams['input']->department_group_ids) && !empty($filterParams['input']->department_group_ids)) {
    erLhcoreClassChat::validateFilterIn($filterParams['input']->department_group_ids);
    $db = ezcDbInstance::get();
    $stmt = $db->prepare('SELECT dep_id FROM lh_departament_group_member WHERE dep_group_id IN (' . implode(',',$filterParams['input']->department_group_ids) . ')');
    $stmt->execute();
    $depIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (!empty($depIds)) {
        $sparams['body']['query']['bool']['must'][]['terms']['dep_id'] = $depIds;
    }
}

if (isset($filterParams['input']->department_ids) && is_array($filterParams['input']->department_ids) && !empty($filterParams['input']->department_ids)) {
    erLhcoreClassChat::validateFilterIn($filterParams['input']->department_ids);
    $sparams['body']['query']['bool']['must'][]['terms']['dep_id'] = $filterParams['input']->department_ids;
}

if (isset($filterParams['input']->phone_from_ids) && is_array($filterParams['input']->phone_from_ids) && !empty($filterParams['input']->phone_from_ids)) {
    erLhcoreClassChat::validateFilterIn($filterParams['input']->phone_from_ids);
    $sparams['body']['query']['bool']['must'][]['terms']['phone_from_id'] = $filterParams['input']->phone_from_ids;
}

if (isset($filterParams['input']->user_ids) && is_array($filterParams['input']->user_ids) && !empty($filterParams['input']->user_ids)) {
    erLhcoreClassChat::validateFilterIn($filterParams['input']->user_ids);
    $sparams['body']['query']['bool']['must'][]['terms']['user_id'] = $filterParams['input']->user_ids;
}

if (isset($filterParams['filter']['filtergte']['created_at'])) {
    $sparams['body']['query']['bool']['must'][]['range']['created_at']['gte'] = $filterParams['filter']['filtergte']['created_at'] * 1000;
    $dateFilter['gte'] = $filterParams['filter']['filtergte']['created_at'];
}

if (isset($filterParams['filter']['filterlte']['created_at'])) {
    $sparams['body']['query']['bool']['must'][]['range']['created_at']['lte'] = $filterParams['filter']['filterlte']['created_at'] * 1000;
    $dateFilter['lte'] = $filterParams['filter']['filterlte']['created_at'];
}

erLhcoreClassChatEventDispatcher::getInstance()->dispatch('cloudtalkio.elasticsearchexecute',array('sparams' => & $sparams, 'filter' => $filterParams));

if ($filterParams['input_form']->sortby == 'idasc') {
    $sort = array('_id' => array('order' => 'asc'));
} else {
    $sort = array('_id' => array('order' => 'desc'));
}

$append = erLhcoreClassSearchHandler::getURLAppendFromInput($filterParams['input_form'], false, ['form_action']);

if (isset($Params['user_parameters_unordered']['export']) && $Params['user_parameters_unordered']['export'] == 1) {
    session_write_close();
    $ignoreFields = (new \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall)->getState();
    unset($ignoreFields['id']);
    $ignoreFields = array_keys($ignoreFields);

    $filterSQL = [];

    $chats = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::getList(array(
        'offset' => 0,
        'limit' => 9000,
        'body' => array_merge(array(
            'sort' => $sort
        ), $sparams['body'])
    ),
    array('date_index' => $dateFilter));

    $chatIDs = [];
    foreach ($chats as $chatID) {
        $filterSQL['filterin']['id'][] = $chatID->id;
    }

    \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatValidator::callListExport(\LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::getList(array_merge($filterSQL, array('limit' => 100000, 'offset' => 0, 'ignore_fields' => $ignoreFields))));
    exit;
}

if ($filterParams['input_form']->ds == 1)
{
    $total = LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::getCount($sparams, array('date_index' => $dateFilter));
    $tpl->set('total_literal',$total);

    $pages = new lhPaginator();
    $pages->serverURL = erLhcoreClassDesign::baseurl('cloudtalkio/elastichistory') . $append;
    $pages->items_total = $total > 9000 ? 9000 : $total;
    $pages->setItemsPerPage(30);
    $pages->paginate();

    if ($pages->items_total > 0) {
        $chats = LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::getList(array(
            'offset' => $pages->low,
            'limit' => $pages->items_per_page,
            'body' => array_merge(array(
                'sort' => $sort
            ), $sparams['body'])
        ),
        array('date_index' => $dateFilter));
        $tpl->set('items', $chats);
    }

    $tpl->set('pages', $pages);
}

$tpl->set('inputAppend',$append);

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('cloudtalkio/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'CloudTalk')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Elastic Call History')
    )
);

$Result['content'] = $tpl->fetch();
