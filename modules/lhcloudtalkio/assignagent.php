<?php

use \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoAgentNative;

$nativeUser = erLhcoreClassModelCloudTalkIoAgentNative::fetch($Params['user_parameters']['id']);

if (is_numeric($Params['user_parameters_unordered']['user'])) {

    \erLhcoreClassRestAPIHandler::setHeaders();

    $output = [];

    try {

        if ($Params['user_parameters_unordered']['user'] == -1) {
            $nativeUser->user_id = 0;
            $nativeUser->updateThis(['update' => ['user_id']]);
            $tpl = \erLhcoreClassTemplate::getInstance('lhcloudtalk/assignagent_action.tpl.php');
            $tpl->set('updated', true);
        } else {

            $user = \erLhcoreClassModelUser::fetch($Params['user_parameters_unordered']['user']);

            if (!($user instanceof \erLhcoreClassModelUser)) {
                throw new \Exception(erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','Operator could not be found!'));
            }

            // Verify that user does not have any other CloudTalk agents assigned.
            $presentAssignment = erLhcoreClassModelCloudTalkIoAgentNative::getList(['filter' => ['user_id' => $user->id]]);

            foreach ($presentAssignment as $presentOperator) {
                if ($nativeUser->id != $presentOperator->id) {
                    throw new \Exception(erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','This operator has already CloudTalk agent assigned!'));
                }
            }

            $nativeUser->user_id = $user->id;
            $nativeUser->updateThis(['update' => ['user_id']]);
            $tpl = \erLhcoreClassTemplate::getInstance('lhcloudtalk/assignagent_action.tpl.php');
            $tpl->set('updated', true);
            $output['error'] = false;
        }

        $tplCell = new \erLhcoreClassTemplate();
        $tplCell->set('item',$nativeUser);
        $output['operator_cell'] = $tplCell->fetch('lhcloudtalk/parts/operator_cell.tpl.php');

    } catch (\Exception $e) {
        $tpl = \erLhcoreClassTemplate::getInstance('lhcloudtalk/assignagent_action.tpl.php');
        $tpl->set('errors', [$e->getMessage()]);
        $output['error'] = true;
    }

    $output['validation'] = $tpl->fetch();

    echo json_encode($output);
    exit;
}

$tpl = \erLhcoreClassTemplate::getInstance('lhcloudtalk/assignagent.tpl.php');
$tpl->set('native_user',$nativeUser);

print $tpl->fetch();
exit;

?>