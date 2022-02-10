<?php

namespace LiveHelperChatExtension\cloudtalkio\providers;

class erLhcoreClassModelCloudTalkIoESCall
{
    use \erLhcoreClassElasticTrait;

    public function getState()
    {
        $states = array(
            'id' => $this->id,
            'status' => $this->status,
            'time' => $this->time,
            'created_at' => $this->created_at,
            'dep_id' => $this->dep_id,
            'user_id' => $this->user_id,
            'chat_id' => $this->chat_id,
            'call_id' => $this->call_id,
            'contact_id' => $this->contact_id,
            'cloudtalk_user_id' => $this->cloudtalk_user_id,
            'status_call' => $this->status_call,
            'contact_removed' => $this->contact_removed,
            'exclude_autoasign' => $this->exclude_autoasign,
            'updated_at' => $this->updated_at,
            'answered_at' => $this->answered_at,
            'phone' => $this->phone,
            'waiting_time' => $this->waiting_time,
            'talking_time' => $this->talking_time,
            'wrapup_time' => $this->wrapup_time,
            'nick' => $this->nick,
            'call_uuid' => $this->call_uuid,
            'email' => $this->email,
            'direction' => $this->direction,
            'status_outcome' => $this->status_outcome,
            'msg_id' => $this->msg_id,
            'call_variables' => $this->call_variables,
            'recording_url' => $this->recording_url,
            'hour' => $this->hour,
        );

        \erLhcoreClassChatEventDispatcher::getInstance()->dispatch('elasticsearch.getstate_call', array(
            'state' => & $states,
            'call' => & $this
        ));

        return $states;
    }

    public function __get($var)
    {
        switch ($var) {

            case 'department':
                $this->department = false;
                if ($this->dep_id > 0) {
                    try {
                        $this->department = \erLhcoreClassModelDepartament::fetch($this->dep_id,true);
                    } catch (\Exception $e) {

                    }
                }
                return $this->department;

            case 'user':
                $this->user = false;
                if ($this->user_id > 0) {
                    try {
                        $this->user = \erLhcoreClassModelUser::fetch($this->user_id,true);
                    } catch (\Exception $e) {
                        $this->user = false;
                    }
                }
                return $this->user;


            default:
                break;
        }
    }

    public static $elasticType = 'lh_call';

    public $id = null;
    public $status = null;
    public $time = null;
    public $created_at = null;
    public $dep_id = null;
    public $user_id = null;
    public $chat_id = null;
    public $call_id = null;
    public $contact_id = null;
    public $cloudtalk_user_id = null;
    public $status_call = null;
    public $contact_removed = null;
    public $exclude_autoasign = null;
    public $updated_at = null;
    public $answered_at = null;
    public $phone = null;
    public $waiting_time = null;
    public $talking_time = null;
    public $wrapup_time = null;
    public $nick = null;
    public $call_uuid = null;
    public $email = null;
    public $direction = null;
    public $status_outcome = null;
    public $msg_id = null;
    public $call_variables = null;
    public $recording_url = null;

    // Dynamic attributes
    public $subject_id = null;
    public $hour = null;
}

?>