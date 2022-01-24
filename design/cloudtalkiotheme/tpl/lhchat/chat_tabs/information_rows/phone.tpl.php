<?php if (isset($orderInformation['phone']['enabled']) && $orderInformation['phone']['enabled'] == true && !empty($chat->phone)) : ?>

    <div class="col-6 pb-1" ng-non-bindable>
        <a href="tel:<?php echo $chat->phone?>"><span class="material-icons" >dialpad</span><?php echo $chat->phone?></a>
        <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio','use_operator')) : ?>
        <br/><span class="text-primary action-image" id="chat-cloudtalk-direct-<?php echo $chat->id?>" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','We will start call directly')?>"><span class="material-icons" >phone</span> <?php echo htmlspecialchars($chat->phone)?></span>
        <?php endif; ?>
    </div>

    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio','use_operator')) : ?>
    <div class="col-6 pb-1" ng-non-bindable>
        <span class="text-primary action-image" id="chat-cloudtalk-invitation-btn-right-<?php echo $chat->id?>" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','We will send invite to call with a chat widget.')?>"><span class="material-icons" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/adminchat','Phone')?>">phone</span> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Invite to call')?></span>
    </div>
    <?php endif; ?>

<?php endif;?>