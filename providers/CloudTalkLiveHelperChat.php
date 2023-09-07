<?php

namespace LiveHelperChatExtension\cloudtalkio\providers {

    require_once 'extension/cloudtalkio/providers/CloudTalk/CTApiClient.php';
    #[\AllowDynamicProperties]
    class CloudTalkLiveHelperChat extends \CTApiClient{

        protected function getConstant($const){
            return constant(get_class($this)."::".$const);
        }

        public function makeACall($data) {
            if (!class_exists('\Requests')) {
                trigger_error("Unable to load Requests class", E_USER_WARNING);
                return false;
            }
            \Requests::register_autoloader();

            $response = \Requests::post($this->getConstant('API_URL').'/calls/create.json', array('Content-Type' => 'application/json'), json_encode($data), array_merge($this->options, ['verify' => false]));

            $response_data = json_decode($response->body);

            return $response_data;
        }

        public function cueCards($data) {

            if(!class_exists('\Requests')) {
                trigger_error("Unable to load Requests class", E_USER_WARNING);
                return false;
            }
            \Requests::register_autoloader();

            $response = \Requests::post( 'https://platform-api.cloudtalk.io/api/cuecards', array('Content-Type' => 'application/json'), json_encode($data), array_merge($this->options, ['verify' => false]));

            return json_decode($response->body);
        }
    }
}