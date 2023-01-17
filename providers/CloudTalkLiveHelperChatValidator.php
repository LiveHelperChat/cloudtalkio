<?php

namespace LiveHelperChatExtension\cloudtalkio\providers;

class CloudTalkLiveHelperChatValidator{

    public static function validatePhone($item) {
        $definition = array(
            'phone' => new \ezcInputFormDefinitionElement(
                \ezcInputFormDefinitionElement::OPTIONAL, 'int'
            ),
            'active' => new \ezcInputFormDefinitionElement(
                \ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
            ),
            'dep_id' => new \ezcInputFormDefinitionElement(
                \ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
            )
        );

        $form = new \ezcInputForm( INPUT_POST, $definition );
        $Errors = array();

        if ( $form->hasValidData( 'phone' ) && $form->phone != '') {
            $item->phone = $form->phone;
        } else {
            $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter a phone!');
        }

        if ( $form->hasValidData( 'dep_id' )) {
            $item->dep_id = $form->dep_id;
        } else {
            $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please choose a department!');
        }

        if ( $form->hasValidData( 'active' ) && $form->active == true)
        {
            $item->active = 1;
        } else {
            $item->active = 0;
        }

        return $Errors;
    }

    public static function callListExport($chats, $params = array())
    {

        $chatArray = array(
            array(
                'ID',
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Department'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Phone'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Nick'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'E-mail'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Chat agent'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Chat agent id'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'CloudTalk agent'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Live status'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Inbound/Outbound'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Call status'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Waiting time'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Talking time'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Wrap up time'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Call ID'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Call UUID'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Record URL'),
                \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Chat ID')
            )
        );

        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename=call-report.csv");
        header("Content-Transfer-Encoding: binary");

        $df = fopen("php://output", 'w');

        // First row
        fputcsv($df, $chatArray[0]);

        $statusTranslated = [
            \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_PENDING => \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Pending'),
            \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_STARTED => \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Started'),
            \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_RINGING_AGENT => \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Ringing agent'),
            \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_ANSWERED => \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Answered'),
            \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_ENDED => \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Ended'),
        ];

        foreach ($chats as $item) {
            $itemObject = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::fetch($item->id);

            $statusTextInbound = $itemObject->direction == \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::DIRECTION_OUTBOUND ? \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'uutbound') : \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'inbound');

            if ($itemObject->status_call == \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_PENDING_CALL_ID) {
                $statusText = (\erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'Pending call history'));
            } else {
                $statusText = (($itemObject->status_outcome == \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_OUTCOME_ANSWERED) ? \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'answered') : \erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin', 'not answered'));
            }

            fputcsv($df, [
                $itemObject->id,
                (string)$itemObject->department,
                $itemObject->phone,
                $itemObject->nick,
                $itemObject->email,
                (string)$itemObject->user,
                $itemObject->user_id,
                (string)$itemObject->cloudtalk_user,
                $statusTranslated[$itemObject->status],
                $statusTextInbound,
                $statusText,
                $itemObject->waiting_time,
                $itemObject->talking_time,
                $itemObject->wrapup_time,
                $itemObject->call_id,
                $itemObject->call_uuid,
                $itemObject->recording_url,
                $itemObject->chat_id,
            ]);
        }

        fclose($df);
    }

}
