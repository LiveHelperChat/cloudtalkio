<?php
#[\AllowDynamicProperties]
class erLhcoreClassExtensionCloudtalkio {

    public function __construct() {
        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();
        $dispatcher->listen('chat.genericbot_chat_command_dispatch_event', 'erLhcoreClassExtensionCloudtalkio::listenDispatchEvent');

        // Elastic search related event listeners
        if (class_exists('\erLhcoreClassExtensionElasticsearch')) { // Check that this extension exists
            // Records indexing
            $dispatcher->listen('cloudtalk.call.after_update', 'erLhcoreClassExtensionCloudtalkio::callIndex');
            $dispatcher->listen('cloudtalk.call.after_save', 'erLhcoreClassExtensionCloudtalkio::callIndex');

            // Elastic search structure
            $dispatcher->listen('system.getelasticstructure_core', '\LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatESIndex::elasticSearchStructure');
            $dispatcher->listen('system.elastic_search.index_objects', '\LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatESIndex::doCallIndex');
            $dispatcher->listen('elasticsearch.interactions_index', '\LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatESIndex::interactionsIndex');
            $dispatcher->listen('elasticsearch.interactions_class', '\LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatESIndex::interactionsClass');
        }
    }

    public static function callIndex($params) {
        $db = ezcDbInstance::get();
        
        try {
            $stmt = $db->prepare('INSERT IGNORE INTO lhc_lhesctcall_index (`call_id`) VALUES (:call_id)');
            $stmt->bindValue(':call_id', $params['call']->id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            // Ignore
        }

        // Schedule background worker for instant indexing
        if (class_exists('erLhcoreClassExtensionLhcphpresque')) {
            erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcphpresque')->enqueue('lhc_elastic_queue', 'erLhcoreClassElasticSearchWorker', array());
        }
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

    public function encryptPhone($phone) {
        $secretKey = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'secrethash' );
        
        $cipher = "aes-256-cbc";
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        
        // Hash the secret key to get a proper 32-byte key for AES-256
        $key = hash('sha256', $secretKey, true);
        
        // Encrypt the phone number
        $encrypted = openssl_encrypt($phone, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        
        // Combine IV and encrypted data, then base64 encode for storage
        return base64_encode($iv . $encrypted);
    }

    public function decryptPhone($phone) {
        $secretKey = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'secrethash' );
        
        $cipher = "aes-256-cbc";
        $ivLength = openssl_cipher_iv_length($cipher);
        
        // Hash the secret key to get a proper 32-byte key for AES-256
        $key = hash('sha256', $secretKey, true);
        
        // Decode the base64 encoded data
        $data = base64_decode($phone);
        
        // Extract IV and encrypted data
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        
        // Decrypt the phone number
        return openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv);
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