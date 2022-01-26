<?php

namespace LiveHelperChatExtension\cloudtalkio\providers;

class erLhcoreClassModelCloudTalkIoPhoneNumber
{
    use \erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_cloudtalkio_phone_number';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionCloudtalkio::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
            'phone' => $this->phone,
            'dep_id' => $this->dep_id,
            'active' => $this->active,
        );
    }

    public function __toString()
    {
        return $this->phone;
    }

    public function __get($var)
    {
        switch ($var) {

            case 'department':
                $this->department = null;
                if ($this->dep_id > 0) {
                    try {
                        $this->department = \erLhcoreClassModelDepartament::fetch($this->dep_id,true);
                    } catch (\Exception $e) {

                    }
                }
                return $this->department;

            default:
                ;
                break;
        }
    }

    public $id = null;
    public $phone = '';
    public $dep_id = 0;
    public $active = 1;

}

?>