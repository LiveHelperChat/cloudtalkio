<?php
/**
 * Updates most recent calls and set's phone number id if they don't have it.
 * Usefull for legacy calls updates
 *
 * php cron.php -s site_admin -e cloudtalkio -c cron/update_phone_number
 * */

$api = \erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->getApi();

foreach (\LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::getList(['limit' => 100, 'sort' => '`id` DESC', 'filtergt' => ['call_id' => 0],'filter' => ['phone_from_id' => 0]]) as $call) {
    $data = json_decode(json_encode($api->getCallHistory(['call_id' => $call->call_id])),true);
    
    if (isset($data['responseData']['data'][0]['Cdr']['public_internal'])) {
        $phone = str_replace('+','',$data['responseData']['data'][0]['Cdr']['public_internal']);
        $phoneInternal = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoPhoneNumber::findOne(['filter' => ['phone' => $phone]]);
        if (is_object($phoneInternal)) {
            $call->phone_from_id = $phoneInternal->id;
            $call->updateThis(['update' => ['phone_from_id']]);
            echo "Updating phone - $phone","\n";
        } else {
            echo "Phone not found - ",$phone,"\n";
        }
    }
}






