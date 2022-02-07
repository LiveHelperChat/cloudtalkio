<?php
/**
 * Just for testing purposes
 *
 * php cron.php -s site_admin -e cloudtalkio -c cron/test
 * */

$data['call_uuid'] = 'xxx7be80-9a77-411b-b877-be70451f22f8';
$data['CallUUID'] = 'xxx7be80-9a77-411b-b877-be70451f22f8';
\LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::sendCueCard($data);
