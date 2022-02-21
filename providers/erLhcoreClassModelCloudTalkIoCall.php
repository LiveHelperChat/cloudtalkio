<?php

namespace LiveHelperChatExtension\cloudtalkio\providers;

class erLhcoreClassModelCloudTalkIoCall
{
    use \erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_cloudtalkio_call';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionCloudtalkio::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
            'cloudtalk_user_id' => $this->cloudtalk_user_id,
            'user_id' => $this->user_id,
            'call_id' => $this->call_id,
            'contact_id' => $this->contact_id,
            'contact_removed' => $this->contact_removed,
            'chat_id' => $this->chat_id,
            'dep_id' => $this->dep_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'answered_at' => $this->answered_at,
            'phone' => $this->phone,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'call_uuid' => $this->call_uuid,
            'recording_url' => $this->recording_url,
            'waiting_time' => $this->waiting_time,
            'talking_time' => $this->talking_time,
            'wrapup_time' => $this->wrapup_time,
            'status_call' => $this->status_call,
            'status_outcome' => $this->status_outcome,
            'direction' => $this->direction,
            'msg_id' => $this->msg_id,
            'exclude_autoasign' => $this->exclude_autoasign,
            'email' => $this->email,
            'call_variables' => $this->call_variables,
            'nick' => $this->nick
        );
    }

    public function beforeSave($params = array())
    {
        if ($this->created_at == 0) {
            $this->created_at = time();
        }

        $this->updated_at = time();
        $this->phone = str_replace('+','',$this->phone);
    }

    public function __toString()
    {
        return $this->phone;
    }

    public function __get($var)
    {
        switch ($var) {

            case 'updated_at_ago':
                $this->updated_at_ago = \erLhcoreClassChat::formatSeconds(time() - $this->updated_at);
                return $this->updated_at_ago;

            case 'user':
                $this->user = \erLhcoreClassModelUser::fetch($this->user_id);
                return $this->user;

            case 'cloudtalk_user':
                $this->cloudtalk_user = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoAgentNative::findOne(['filter' => [
                    'cloudtalk_user_id' => $this->cloudtalk_user_id
                ]]);
                return $this->cloudtalk_user;

            case 'department':
                $this->department = null;
                if ($this->dep_id > 0) {
                    try {
                        $this->department = \erLhcoreClassModelDepartament::fetch($this->dep_id,true);
                    } catch (\Exception $e) {

                    }
                }
                return $this->department;

            case 'call_variables_array':
                if (!empty($this->call_variables)) {
                    $jsonData = json_decode($this->call_variables,true);
                    if ($jsonData !== null) {
                        $this->call_variables_array = $jsonData;
                    } else {
                        $this->call_variables_array = $this->call_variables;
                    }
                } else {
                    $this->call_variables_array = array();
                }
                return $this->call_variables_array;

            default:
                ;
                break;
        }
    }

    public function afterSave($params = array())
    {
        \erLhcoreClassChatEventDispatcher::getInstance()->dispatch('cloudtalk.call.after_save',array(
            'call' => & $this
        ));
    }

    public function afterUpdate($params = array())
    {
        \erLhcoreClassChatEventDispatcher::getInstance()->dispatch('cloudtalk.call.after_update',array(
            'call' => & $this
        ));
    }

    const STATUS_PENDING = 0;
    const STATUS_STARTED = 1;
    const STATUS_RINGING_AGENT = 2;
    const STATUS_ANSWERED = 3;
    const STATUS_ENDED = 4;

    // Pending call ID
    const STATUS_PENDING_CALL_ID = 0;
    const STATUS_PENDING_CALL_SET = 1;

    const STATUS_OUTCOME_MISSED = 0;
    const STATUS_OUTCOME_ANSWERED = 1;

    const DIRECTION_OUTBOUND = 0;
    const DIRECTION_INCOMMING = 1;

    public $id = null;
    public $cloudtalk_user_id = 0;
    public $user_id = 0;
    public $chat_id = 0;
    public $date_from = 0;
    public $date_to = 0;
    public $contact_id = 0;
    public $contact_removed = 0;
    public $status = self::STATUS_PENDING;
    public $status_call = self::STATUS_PENDING_CALL_ID;
    public $created_at = 0;
    public $updated_at = 0;
    public $call_id = 0;
    public $phone = '';
    public $waiting_time = 0;
    public $wrapup_time = 0;
    public $talking_time = 0;
    public $answered_at = 0;
    public $msg_id = 0;
    public $dep_id = 0;
    public $exclude_autoasign = 0;
    public $status_outcome = self::STATUS_OUTCOME_MISSED;

    public $direction = self::DIRECTION_OUTBOUND;

    public $call_uuid = '';
    public $recording_url = '';
    public $email = '';
    public $call_variables = '';
    public $nick = '';
}

?>