<?php

namespace LiveHelperChatExtension\cloudtalkio\providers {

    require_once 'extension/cloudtalkio/providers/CloudTalk/CTApiClient.php';

    class CloudTalkLiveHelperChat extends \CTApiClient{

        protected function getConstant($const){
            return constant(get_class($this)."::".$const);
        }

        public function makeACall($data){
            if(!class_exists('\Requests')) {
                trigger_error("Unable to load Requests class", E_USER_WARNING);
                return false;
            }
            \Requests::register_autoloader();

            $response = \Requests::post($this->getConstant('API_URL').'/calls/create.json', array(), $data, $this->options);
            $response_data = json_decode($response->body);

            return $response_data;
        }
    }
}