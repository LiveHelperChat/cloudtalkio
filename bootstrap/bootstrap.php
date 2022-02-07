<?php

class erLhcoreClassExtensionCloudtalkio {

    public function __construct() {
        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();
        $dispatcher->listen('chat.genericbot_chat_command_dispatch_event', 'erLhcoreClassExtensionCloudtalkio::listenDispatchEvent');

        // Elastic search
        $dispatcher->listen('system.getelasticstructure_core', 'erLhcoreClassExtensionCloudtalkio::elasticSearchStructure');

        // Conversations
        $dispatcher->listen('cloudtalk.call.after_update', 'erLhcoreClassExtensionCloudtalkio::callIndex');
        $dispatcher->listen('cloudtalk.call.after_save', 'erLhcoreClassExtensionCloudtalkio::callIndex');
        $dispatcher->listen('system.elastic_search.index_objects', 'erLhcoreClassExtensionCloudtalkio::doCallIndex');
    }

    public static function doCallIndex()
    {
        // @todo do call index
    }


    public static function callIndex($params) {
        $db = ezcDbInstance::get();
        $stmt = $db->prepare('INSERT IGNORE INTO lhc_lhesctcall_index (`call_id`) VALUES (:call_id)');
        $stmt->bindValue(':call_id', $params['call']->id, PDO::PARAM_INT);
        $stmt->execute();

        // Schedule background worker for instant indexing
        if (class_exists('erLhcoreClassExtensionLhcphpresque')) {
            erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcphpresque')->enqueue('lhc_elastic_queue', 'erLhcoreClassElasticSearchWorker', array());
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
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['msg_id'] = array('type' => 'long');
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['call_variables'] = array('type' => 'text', 'index' => false);
        $params['structure'][(isset($params['index_new']) ? $params['index_new'] : 'chat')]['types']['lh_call']['recording_url'] = array('type' => 'text', 'index' => false);
    }

    // Continue here
    public static function listenDispatchEvent($params) {
        if ($params['action']['content']['payload'] == 'cloudtalk.direct_call') {
            LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::makeDirectCall($params);
        } else if ($params['action']['content']['payload'] == 'cloudtalk.invite_to_call') {
            LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::inviteToCall($params);
        }
    }

    public function run() {

    }

    public static function getSession() {
        if (! isset ( self::$persistentSession )) {
            self::$persistentSession = new ezcPersistentSession ( ezcDbInstance::get (), new ezcPersistentCodeManager ( './extension/cloudtalkio/pos' ) );
        }
        return self::$persistentSession;
    }

    public function __get($var) {
        switch ($var) {

            case 'settings' :
                $this->settings = include ('extension/cloudtalkio/settings/settings.ini.php');
                return $this->settings;

            default :
                ;
                break;
        }
    }

    public static function getApi() {
        $settings = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->settings;
        return new \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChat($settings['ACCESS_KEY_ID'], $settings['ACCESS_KEY_SECRET']);
    }
    
    private static $persistentSession;
}

?>