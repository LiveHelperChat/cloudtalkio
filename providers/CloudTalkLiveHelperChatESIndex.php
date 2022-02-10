<?php

namespace LiveHelperChatExtension\cloudtalkio\providers;

class CloudTalkLiveHelperChatESIndex{

    public static function interactionsIndex($params)
    {
        $params['index_search'] .= ',' . \erLhcoreClassElasticSearchStatistic::getIndexByFilter([
                'filtergte' => ['time' => $params['date_filter']['gte'] ],
                'filterlte' => ['time' => $params['date_filter']['lte'] ]
            ], \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::$elasticType);
    }

    public static function interactionsClass($params)
    {
        if (strpos($params['index'],'lh_call') !== false) {
            $params['class_name'] = '\LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall';
        }
    }

    public static function elasticSearchStructure($params)
    {
        // Call module module
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['dep_id'] = array('type' => 'integer');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['user_id'] = array('type' => 'long');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['chat_id'] = array('type' => 'long');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['call_id'] = array('type' => 'long');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['contact_id'] = array('type' => 'long');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['cloudtalk_user_id'] = array('type' => 'long');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['status'] = array('type' => 'integer');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['status_call'] = array('type' => 'integer');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['contact_removed'] = array('type' => 'integer');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['exclude_autoasign'] = array('type' => 'integer');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['updated_at'] = array('type' => 'date');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['created_at'] = array('type' => 'date');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['answered_at'] = array('type' => 'date');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['phone'] = array('type' => 'keyword');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['waiting_time'] = array('type' => 'integer');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['talking_time'] = array('type' => 'integer');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['wrapup_time'] = array('type' => 'integer');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['nick'] = array('type' => 'text');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['call_uuid'] = array('type' => 'keyword');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['email'] = array('type' => 'keyword');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['direction'] = array('type' => 'integer');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['status_outcome'] = array('type' => 'integer');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['hour'] = array('type' => 'integer');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['msg_id'] = array('type' => 'long');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['call_variables'] = array('type' => 'text', 'index' => false);
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['recording_url'] = array('type' => 'text', 'index' => false);
    }

    public static function doCallIndex() {

        $db = \ezcDbInstance::get();
        $db->reconnect(); // Because it timeouts automatically, this calls to reconnect to database, this is implemented in 2.52v

        $esOptions = \erLhcoreClassChat::getSession()->load( 'erLhcoreClassModelChatConfig', 'elasticsearch_options' );
        $data = (array)$esOptions->data;

        if (isset($data['disable_es']) && $data['disable_es'] == 1) {
            \error_log('Elastic search disabled in erLhcoreClassElasticSearchWorker');
            return;
        }

        $db->beginTransaction();
        try {
            $stmt = $db->prepare('SELECT call_id FROM lhc_lhesctcall_index WHERE status = 0 LIMIT :limit FOR UPDATE ');
            $stmt->bindValue(':limit',100,\PDO::PARAM_INT);
            $stmt->execute();
            $chatsId = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        } catch (\Exception $e) {
            // Someone is already processing. So we just ignore and retry later
            return;
        }

        if (!empty($chatsId)) {
            // Delete indexed calls's records
            $stmt = $db->prepare('UPDATE lhc_lhesctcall_index SET status = 1 WHERE call_id IN (' . implode(',', $chatsId) . ')');
            $stmt->execute();
            $db->commit();

            $chats = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::getList(array('filterin' => array('id' => $chatsId)));

            if (!empty($chats)) {
                try {
                    self::indexCalls(array('calls' => $chats));
                } catch (\Exception $e) {
                    \error_log($e->getMessage() . "\n" . $e->getTraceAsString());
                    return;
                }
            }

            $esOptions = \erLhcoreClassChat::getSession()->load( 'erLhcoreClassModelChatConfig', 'elasticsearch_options' );
            $data = (array)$esOptions->data;

            if (isset($data['disable_es']) && $data['disable_es'] == 1) {
                \error_log('Elastic search disabled in erLhcoreClassElasticSearchWorker');
                return;
            }

            $stmt = $db->prepare('DELETE FROM lhc_lhesctcall_index WHERE call_id IN (' . implode(',', $chatsId) . ')');
            $stmt->execute();

        } else {
            $db->rollback();
        }
    }

    public static function indexCalls($params)
    {
        $sparams = array();
        $sparams['body']['query']['bool']['must'][]['terms']['_id'] = array_keys($params['calls']);
        $sparams['limit'] = 1000;

        $dateRange = array();
        foreach ($params['calls'] as $item) {
            if ($item->created_at > 0) {
                $dateRange[] = $item->created_at;
            }
        }

        $documentsReindexed = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::getList($sparams,array('date_index' => array('gte' => min($dateRange), 'lte' => max($dateRange))));

        $objectsSave = array();

        $esOptions = \erLhcoreClassModelChatConfig::fetch('elasticsearch_options');
        $dataOptions = (array)$esOptions->data;

        if (isset($dataOptions['check_if_exists']) && $dataOptions['check_if_exists'] == 1)
        {
            $dateRangesIndex = [];
            foreach ($dateRange as $dateRangeItem) {
                if ($dataOptions['index_type'] == 'daily') {
                    $dateRangesIndex[] = \date('Y.m.d',$dateRangeItem);
                } elseif ($dataOptions['index_type'] == 'yearly') {
                    $dateRangesIndex[] = \date('Y',$dateRangeItem);
                } elseif ($dataOptions['index_type'] == 'monthly') {
                    $dateRangesIndex[] = \date('Y.m',$dateRangeItem);
                }
            }

            if (!empty($dateRangesIndex)) {
                $settings = include ('extension/elasticsearch/settings/settings.ini.php');

                foreach (array_unique($dateRangesIndex) as $indexPrepend)
                {
                    $sessionElasticStatistic = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::getSession();
                    $esSearchHandler = \erLhcoreClassElasticClient::getHandler();
                    \erLhcoreClassElasticClient::indexExists($esSearchHandler, $settings['index'], $indexPrepend, true);
                }
            }
        }

        foreach ($params['calls'] as $keyValue => $item) {

            if (isset($documentsReindexed[$keyValue])) {
                $esChat = $documentsReindexed[$keyValue];
            } else {
                $esChat = new \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall();
            }

            $esChat->id = $item->id;
            $esChat->time = $item->created_at * 1000;
            $esChat->created_at = $item->created_at * 1000;
            $esChat->updated_at = $item->updated_at * 1000;
            $esChat->answered_at = $item->answered_at * 1000;
            $esChat->cloudtalk_user_id = $item->cloudtalk_user_id;
            $esChat->user_id = $item->user_id;
            $esChat->call_id = $item->call_id;
            $esChat->contact_id = $item->contact_id;
            $esChat->contact_removed = $item->contact_removed;
            $esChat->chat_id = $item->chat_id;
            $esChat->dep_id = $item->dep_id;
            $esChat->status = $item->status;
            $esChat->phone = $item->phone;
            $esChat->date_from = $item->date_from;
            $esChat->date_to = $item->date_to;
            $esChat->call_uuid = $item->call_uuid;
            $esChat->recording_url = $item->recording_url;
            $esChat->waiting_time = $item->waiting_time;
            $esChat->talking_time = $item->talking_time;
            $esChat->wrapup_time = $item->wrapup_time;
            $esChat->status_call = $item->status_call;
            $esChat->status_outcome = $item->status_outcome;
            $esChat->direction = $item->direction;
            $esChat->msg_id = $item->msg_id;
            $esChat->exclude_autoasign = $item->exclude_autoasign;
            $esChat->email = $item->email;
            $esChat->call_variables = $item->call_variables;
            $esChat->nick = $item->nick;

            // Store hour as UTC for easier grouping
            $date_utc = new \DateTime('', new \DateTimeZone("UTC"));
            $date_utc->setTimestamp($item->created_at);
            $esChat->hour = $date_utc->format("H");

            // Extensions can append custom value
            \erLhcoreClassChatEventDispatcher::getInstance()->dispatch('elasticsearch.indexcall', array(
                'call' => & $esChat
            ));

            // Store hour as UTC for easier grouping
            $date_utc = new \DateTime('', new \DateTimeZone("UTC"));
            $date_utc->setTimestamp($item->created_at);
            $esChat->hour = $date_utc->format("H");

            $indexSave = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::$indexName . '-' . \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::$elasticType;

            if (isset($esChat->meta_data['index']) && $esChat->meta_data['index'] != '') {
                $indexSave = $esChat->meta_data['index'];
            } else if (isset($dataOptions['index_type'])) {
                if ($dataOptions['index_type'] == 'daily') {
                    $indexSave = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::$indexName . '-' .\LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::$elasticType . '-' . gmdate('Y.m.d',$item->created_at);
                } elseif ($dataOptions['index_type'] == 'yearly') {
                    $indexSave = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::$indexName . '-' .\LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::$elasticType . '-' . gmdate('Y',$item->created_at);
                } elseif ($dataOptions['index_type'] == 'monthly') {
                    $indexSave = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::$indexName . '-' .\LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::$elasticType . '-' . gmdate('Y.m',$item->created_at);
                }
            }

            $objectsSave[$indexSave][] = $esChat;
        }

        \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall::bulkSave($objectsSave, array('custom_index' => true, 'ignore_id' => true));
    }

}
