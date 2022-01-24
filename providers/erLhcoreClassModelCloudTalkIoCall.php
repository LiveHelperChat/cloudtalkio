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

            default:
                ;
                break;
        }
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
    public $cloudtalk_user_id = null;
    public $user_id = null;
    public $chat_id = null;
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
    public $status_outcome = self::STATUS_OUTCOME_MISSED;

    public $direction = self::DIRECTION_OUTBOUND;

    public $call_uuid = '';
    public $recording_url = '';
}

?>