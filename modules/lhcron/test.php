<?php
/**
 * Just for testing purposes
 *
 * php cron.php -s site_admin -e cloudtalkio -c cron/test
 * */

// Execute to insert all calls to be reindexed
// INSERT IGNORE INTO lhc_lhesctcall_index (`call_id`) SELECT `id` FROM `lhc_cloudtalkio_call

// Reindex calls
erLhcoreClassChatEventDispatcher::getInstance()->dispatch('system.elastic_search.index_objects',array());
