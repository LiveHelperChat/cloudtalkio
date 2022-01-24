<?php
/**
 * php cron.php -s site_admin -e cloudtalkio -c cron/sync_agents
 * */

use \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoAgentNative;

$api = \erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->getApi();

$hasAgents = true;
$page = 1;

$itemsStats = ['update' => 0, 'created' => 0];

while ($hasAgents) {

    $paramsQuery = ['limit' => 40, 'page' => $page];
    $items = $api->getAgents($paramsQuery);

    foreach ($items->responseData->data as $item) {
        $agent = $item->Agent;

        $existingAgent = erLhcoreClassModelCloudTalkIoAgentNative::findOne(['filter' => ['cloudtalk_user_id' => $agent->id]]);
        if (!($existingAgent instanceof erLhcoreClassModelCloudTalkIoAgentNative)){
            $existingAgent = new erLhcoreClassModelCloudTalkIoAgentNative();
        }

        $existingAgent->cloudtalk_user_id =  $agent->id;
        $existingAgent->in_sync = 1;
        $existingAgent->updated_at = time();
        $existingAgent->firstname = $agent->firstname;
        $existingAgent->lastname = $agent->lastname;
        $existingAgent->email = $agent->email;
        $existingAgent->availability_status = $agent->availability_status;
        $existingAgent->saveThis();
    }

    if ($page < $items->responseData->pageCount) {
        $page++;
    } else {
        $hasAgents = false;
    }
}