<?php

namespace LiveHelperChatExtension\cloudtalkio\providers;
#[\AllowDynamicProperties]
class erLhcoreClassModelCloudTalkIoAgentNative
{
    use \erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_cloudtalkio_agent_native';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionCloudtalkio::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
            'user_id' => $this->user_id,
            'cloudtalk_user_id' => $this->cloudtalk_user_id,
            'in_sync' => $this->in_sync,
            'updated_at' => $this->updated_at,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'availability_status' => $this->availability_status
        );
    }

    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
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

            default:
                ;
                break;
        }
    }

    public $id = null;
    public $user_id = 0;
    public $cloudtalk_user_id = 0;
    public $in_sync = 0;
    public $updated_at = 0;
    public $firstname = 0;
    public $lastname = 0;
    public $email = 0;
    public $availability_status = 0;
}

?>