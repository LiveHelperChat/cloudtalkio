<?php

class erLhcoreClassExtensionCloudtalkio {

    public function __construct() {
        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();
        $dispatcher->listen('chat.genericbot_chat_command_dispatch_event', 'erLhcoreClassExtensionCloudtalkio::listenDispatchEvent');
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