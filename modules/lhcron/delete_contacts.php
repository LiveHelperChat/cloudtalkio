<?php
/**
 *
 * Run every hour to delete older contacts than 1 hour.
 * This is optional cronjob which deletes contact records from CloudTalk
 * for privacy reasons.
 *
 * php cron.php -s site_admin -e cloudtalkio -c cron/delete_contacts
 * */

use LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient;
use LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall;

foreach (erLhcoreClassModelCloudTalkIoCall::getList(['filter' => ['contact_removed' => 0]]) as $call) {

    // It's not yet time to delete a contact
    if ($call->contact_id == 0 || $call->created_at > (time() - 3600) || erLhcoreClassModelCloudTalkIoCall::getCount([
        'filtergt' => ['created_at' => (time() - 3600), 'id' => $call->id],
        'filter' => ['contact_id' => $call->contact_id, 'contact_removed' => 0]
            ]) > 0) {
        continue;
    }

    $callsRelated = erLhcoreClassModelCloudTalkIoCall::getList(['filter' => ['contact_removed' => 0, 'contact_id' => $call->contact_id]]);

    foreach ($callsRelated as $callRelated) {
        $callRelated->contact_removed = 1;
        $callRelated->updateThis(['update' => ['contact_removed']]);
    }

    if (!empty($callsRelated)) {
        $api = \erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->getApi();
        $api->deleteContact($call->contact_id);
    }

}

?>