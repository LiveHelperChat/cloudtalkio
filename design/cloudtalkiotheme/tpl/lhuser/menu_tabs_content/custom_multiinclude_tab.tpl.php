<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio','use_operator')) : ?>
<div role="tabpanel" class="tab-pane <?php if ($tab == 'tab_cloudtalkio') : ?>active<?php endif; ?>" id="cloudtalkio" ng-non-bindable="">
      <form action="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/assignaction')?>/<?php echo $user->id?>" method="post" onsubmit="return lhinst.submitModalForm($(this),'cloudtalkio')">
        <?php $cloudTalkRelation = \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoAgentNative::findOne(['filter' => ['email' => $user->email]]); ?>
        <?php if ($cloudTalkRelation instanceof \LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoAgentNative) : ?>
            <?php $cloudTalkAgent = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionCloudtalkio')->getApi()->getAgents(['id' => $cloudTalkRelation->cloudtalk_user_id]);?>
            <?php if (isset($cloudTalkAgent->responseData->itemsCount) && $cloudTalkAgent->responseData->itemsCount == 1) : ?>

                <h5><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','CloudTalk account details. Please verify before making any further changes.');?></h5>

                <ul>
                    <li>ID: <?php echo htmlspecialchars($cloudTalkAgent->responseData->data[0]->Agent->id)?></li>
                    <li><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Firstname');?>: <?php echo htmlspecialchars($cloudTalkAgent->responseData->data[0]->Agent->firstname)?></li>
                    <li><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Lastname');?>: <?php echo htmlspecialchars($cloudTalkAgent->responseData->data[0]->Agent->lastname)?></li>
                    <li><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','E-mail');?>: <?php echo htmlspecialchars($cloudTalkAgent->responseData->data[0]->Agent->email)?></li>
                    <li><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Availability');?>: <?php echo htmlspecialchars($cloudTalkAgent->responseData->data[0]->Agent->availability_status)?></li>
                    <li><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Associated numbers');?>: <?php echo htmlspecialchars(implode(', ',$cloudTalkAgent->responseData->data[0]->Agent->associated_numbers))?></li>
                </ul>

                <?php if ($cloudTalkRelation->user_id == $user->id) : ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','This account is already assigned to your account. No action required!');?>
                        <hr>
                        <input type="hidden" name="action" value="unassign">
                        <button class="btn btn-danger"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Unassigned! (you will lose access to make calls!)');?></button>
                    </div>
                <?php elseif ($cloudTalkRelation->user_id > 0 && $user->id) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','This account is already assigned to another operator');?> [<?php echo $cloudTalkRelation->user_id?>]. <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','Please contact about it with your manager.');?>
                    </div>
                <?php elseif ($cloudTalkRelation->user_id == 0) : ?>
                    <input type="hidden" name="action" value="assign">
                    <div class="alert alert-success" role="alert">
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','This account is not assigned to you yet. You can confirm that it is you!');?>
                        <hr>
                        <button class="btn btn-sm btn-primary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Yes, it is me! I confirm that this account is my account in CloudTalk');?></button>
                    </div>
                <?php endif; ?>

            <?php else : ?>
                <div class="alert alert-warning" role="alert">
                    <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Seems relevant account does not exist anymore in CloudTalk');?>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="alert alert-warning" role="alert">
                <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','We could not find a relevant account in CloudTalk with your e-mail.');?>
            </div>
        <?php endif; ?>
    </form>
</div>
<?php endif;?>