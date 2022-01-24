<?php

namespace LiveHelperChatExtension\cloudtalkio\providers;

class CloudTalkLiveHelperChatClient {

    public static function makeDirectCall($params) {

        if (class_exists('\erLhcoreClassExtensionLhcphpresque')) {
            $inst_id = class_exists('\erLhcoreClassInstance') ? \erLhcoreClassInstance::$instanceChat->id : 0;
            \erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcphpresque')->enqueue('lhc_cloudtalk', '\LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient', array(
                'inst_id' => $inst_id,
                'chat_id' => $params['chat']->id,
                'init' => (isset($params['init']) ? $params['init'] : 'operator'),
                'msg_id' => (isset($params['msg']) ? $params['msg']->id : 0),
                'user_id' => (isset($params['params_dispatch']['caller_user_id']) ? $params['params_dispatch']['caller_user_id'] : 0),
            ));

            // Flag call as success while call gets executed
            $params['status'] = true;
        } else {
            self::makeDirectCallAPI($params);
        }
    }

    public function perform() {

        $chat = \erLhcoreClassModelChat::fetch($this->args['chat_id']);
        $status = false;

        $params = [
            'chat' => $chat,
            'init' => $this->args['init'],
            'status' => & $status,
            'params_dispatch' => [
                'caller_user_id' => $this->args['user_id'],
                'caller_user_class' => 'erLhcoreClassModelUser'
            ]
        ];

        if ($this->args['msg_id'] > 0) {
            $params['msg'] = \erLhcoreClassModelmsg::fetch($this->args['msg_id']);
        }

        self::makeDirectCallAPI($params);

        // Operations to update admin interface
        // Call failed update call status
        if ($params['status'] == false && isset($params['msg']) && is_object($params['msg'])) {
            \LiveHelperChatExtension\cloudtalkio\providers\CloudTalkLiveHelperChatClient::setMessageCallStatus($params['msg']->id, 'failure', $params['msg']);
        }

        if (isset($params['msg'])) {
            $chat->operation_admin = "lhinst.updateMessageRowAdmin({$chat->id},{$params['msg']->id});\n";
            $chat->updateThis(['update' => ['operation_admin']]);

            // For NodeJS
            \erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.message_updated', array('chat' => & $chat, 'msg' => $params['msg']));
        }

        // For operation
        \erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.added_operation', array('chat' => & $chat));
    }

    public static function inviteToCall($params) {

        $chat = $params['chat'];

        $msg = new \erLhcoreClassModelmsg();
        $msg->time = time();
        $msg->chat_id = $chat->id;

        if ($chat->phone != '') {
            $msg->meta_msg = json_encode(['content' => ['extension' => true, 'cloudtalk' => ['status' => 'invite']]]);
            $msg->msg = '';
            $msg->user_id = $params['params_dispatch']['caller_user_id'];
            $msg->name_support = \erLhcoreClassModelUser::fetch($params['params_dispatch']['caller_user_id'])->name_support;
            \erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.before_msg_admin_saved', array('msg' => & $msg, 'chat' => & $chat, 'user_id' => $msg->user_id));
        } else {
            $msg->meta_msg = '';
            $msg->msg = \htmlspecialchars_decode(\erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Please enter a visitor phone number before a call!'));
            $msg->user_id = -1;
        }

        $msg->saveThis();

        // Update chat structure
        $chat->last_msg_id = $msg->id;
        $chat->last_op_msg_time = time();
        $chat->has_unread_op_messages = 1;
        $chat->updateThis(['update' => ['last_msg_id','last_op_msg_time','has_unread_op_messages']]);
    }

    public static function createContactByChat($chat) {

        if (CloudTalkLiveHelperChatClient::TEST_MODE == true) {
            return 100;
        }

        $api = \erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->getApi();
        $response = $api->getContacts(['keyword' => $chat->phone]);

        $externalURL = array(
            array(
                'name' => \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Last chat') . ' | ' . $chat->id,
                'url' => \erLhcoreClassBBCode::getHost() . \erLhcoreClassDesign::baseurldirect('site_admin/chat/single') . '/' . $chat->id,
            )
        );

        if (class_exists('\erLhcoreClassExtensionElasticsearch')) {
            $externalURL[] = array(
                'name' => \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Interactions'),
                'url' => \erLhcoreClassBBCode::getHost() . \erLhcoreClassDesign::baseurldirect('site_admin/elasticsearch/interactions') . '/(attr)/email/(val)/' . rawurlencode($chat->email),
            );
        }

        if (is_object($response) && empty($response->responseData->data)) {

            $newContactData = array(
                'name' => $chat->nick,
                'ContactNumber' => array(
                    array(
                        'public_number' => $chat->phone
                    )
                ),
                'title' => $chat->department . ' | ' . $chat->nick . ' | ' . $chat->id,
                'ContactEmail' => array(
                    array(
                        'email' => $chat->email,
                    )
                ),
                'ExternalUrl' => $externalURL,
                'company' => (string)$chat->department,
                'website' => (string)$chat->referrer,
            );

            \erLhcoreClassChatEventDispatcher::getInstance()->dispatch('cloudtalk.new_contact',array('contact' => & $newContactData, 'chat' => & $chat));

            $response = $api->addContact($newContactData);

            if (is_object($response) && isset($response->responseData->status) && $response->responseData->status == 201) {
                return $response->responseData->data->id;
            } else {
                throw new \Exception('Failed adding contact information! '.json_encode($response));
            }

        } else {

            if (is_object($response)) {

                $editContactData = array(
                    'name' => $chat->nick,
                    'ExternalUrl' => $externalURL,
                );

                \erLhcoreClassChatEventDispatcher::getInstance()->dispatch('cloudtalk.edit_contact',array('contact' => & $editContactData, 'chat' => & $chat));

                $api->editContact($response->responseData->data[0]->Contact->id,$editContactData);

                return $response->responseData->data[0]->Contact->id;
            } else {
                throw new \Exception('Fetching contact details failed! '.json_encode($response));
            }
        }
    }


    private static function makeDirectCallAPI($params) {

        $phone = $params['chat']->phone;

        try {

            if (empty($phone)) {
                throw new \Exception('Chat is missing phone number!',CloudTalkLiveHelperChatClient::EXCEPTION_MISSING_PHONE);
            }

            if (isset($params['params_dispatch']['caller_user_id']) && $params['params_dispatch']['caller_user_class'] == 'erLhcoreClassModelUser') {
                // Find an agent who is making a call
                $agent = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoAgentNative::findOne(['filter' => ['user_id' => $params['params_dispatch']['caller_user_id']]]);

                if (is_object($agent)) {

                    // Creates a contact so agent will see visitor information
                    $contactId = self::createContactByChat($params['chat']);

                    $api = \erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->getApi();

                    if (CloudTalkLiveHelperChatClient::TEST_MODE == false) {
                        $response = $api->makeACall([
                            'agent_id' => $agent->cloudtalk_user_id,
                            'callee_number' => $phone,
                        ]);
                    } else {
                        $response = new \stdClass();
                        $response->responseData = new \stdClass();
                        $response->responseData->status = 200;
                    }

                    if (is_object($response) && $response->responseData->status == 200) {

                        // Mark previous calls as finished etc
                        foreach (\LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::getList(['filter' => ['chat_id' => $params['chat']->id, 'call_id' => 0]]) as $previousCall) {

                            // Consider call as missed
                            $previousCall->status_outcome = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_OUTCOME_MISSED;

                            // Consider call as ended
                            $previousCall->status = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_ENDED;

                            // Consider call set call_id
                            $previousCall->status_call = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_PENDING_CALL_SET;

                            $previousCall->updateThis(['update' => ['status_outcome','status','status_call']]);
                        }

                        // Create a call
                        $call = new \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall();
                        $call->contact_id = $contactId;
                        $call->cloudtalk_user_id = $agent->cloudtalk_user_id;
                        $call->user_id = $params['params_dispatch']['caller_user_id'];
                        $call->chat_id = $params['chat']->id;
                        $call->phone = $phone;
                        $call->saveThis();

                        if (isset($params['init']) && $params['init'] == 'visitor') {
                            $msg = $params['msg'];
                        } else {
                            $msg = new \erLhcoreClassModelmsg();
                            $msg->user_id = -1;
                            $msg->time = time();
                            $msg->msg = 'Please accept a call in your CloudTalk application now!';
                            $msg->meta_msg = json_encode(['content' => ['cloudcall' => ['content' => $call->id]]]);
                            $msg->chat_id = $params['chat']->id;
                            $msg->saveThis();
                        }

                        $call->msg_id = $msg->id;
                        $call->updateThis();

                        $params['status'] = true;

                    } else {
                        throw new \Exception('Call failed! ' . json_encode($response));
                    }

                } else {
                    throw new \Exception('CloudTalk agent could not be found for the user with ID: ' . $params['params_dispatch']['caller_user_id']);
                }

            } else {
                throw new \Exception('User ID not provided for call to happen: ' . $params['params_dispatch']['caller_user_id'].' erLhcoreClassModelUser');
            }

        } catch (\Exception $e) {

            $params['status'] = false;

            $msg = new \erLhcoreClassModelmsg();
            $msg->user_id = -1;
            $msg->time = time();
            $msg->msg = $e->getMessage();
            $msg->chat_id = $params['chat']->id;
            $msg->saveThis();
        }

        // Update last message ID
        if (isset($msg) && is_object($msg)) {
            $params['chat']->last_msg_id = $msg->id;
            $params['chat']->updateThis(['update' => ['last_msg_id']]);
        }
    }

    public static function setMessageCallStatus($msg_id, $status, $message = null) {
        if ($msg_id > 0 && ($message instanceof \erLhcoreClassModelmsg || (($message = \erLhcoreClassModelmsg::fetch($msg_id)) instanceof \erLhcoreClassModelmsg))) {
            $metaMessage = $message->meta_msg_array;

            if (is_array($status)) {
                foreach ($status as $attr => $value) {
                    $metaMessage['content']['cloudtalk'][$attr] = $value;
                }
            } else {
                $metaMessage['content']['cloudtalk']['status'] = $status;
            }

            $message->meta_msg_array = $metaMessage;
            $message->meta_msg = json_encode($message->meta_msg_array);
            $message->updateThis(['update' => ['meta_msg']]);
        }
    }

    const EXCEPTION_MISSING_PHONE = 1;
    const TEST_MODE = false;
}
