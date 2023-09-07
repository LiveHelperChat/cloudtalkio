<?php if (isset($orderInformation['phone']['enabled']) && $orderInformation['phone']['enabled'] == true) : ?>

    <?php if (!empty($chat->phone)) : ?>
    <div class="col-6 pb-1" ng-non-bindable>
        <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio','use_phone')) : ?>
            <a href="tel:<?php echo $chat->phone?>"><span class="material-icons" >dialpad</span>
            <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio','use_unhidden_phone')) : ?>
                <?php echo htmlspecialchars($chat->phone)?>
            <?php else : ?>
                <?php echo htmlspecialchars(LiveHelperChat\Helpers\Anonymizer::maskPhone($chat->phone))?>
            <?php endif; ?>
            </a>
        <?php else : ?>
            <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio','use_unhidden_phone')) : ?>
                <?php echo htmlspecialchars($chat->phone)?>
            <?php else : ?>
                <?php echo htmlspecialchars(LiveHelperChat\Helpers\Anonymizer::maskPhone($chat->phone))?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio','use_direct')) : ?>
        <br/><span class="text-primary action-image" data-phone="<?php echo htmlspecialchars($chat->phone)?>" id="chat-cloudtalk-direct-<?php echo $chat->id?>" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','We will start call directly')?>"><span class="material-icons" >phone</span>
                <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio','use_unhidden_phone')) : ?>
                    <?php echo htmlspecialchars($chat->phone)?>
                <?php else : ?>
                    <?php echo htmlspecialchars(LiveHelperChat\Helpers\Anonymizer::maskPhone($chat->phone))?>
                <?php endif; ?>
            </span>
        <?php endif; ?>
    </div>
    <?php endif;?>

    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio','use_operator')) : ?>
    <div class="col-6 pb-1" ng-non-bindable>
        <?php if (!empty($chat->phone)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhcloudtalk/chat_tabs/information_rows/phone.tpl.php')); ?>
        <?php endif; ?>
        <span class="text-primary action-image d-block" data-phone="<?php echo htmlspecialchars($chat->phone)?>" id="chat-cloudtalk-updatenumber-btn-right-<?php echo $chat->id?>" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','We will send a widget to update phone number.')?>"><span class="material-icons" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/adminchat','Phone')?>">phone</span> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Invite to update number')?></span>
        <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio','edit_visitor_phone')) : ?>
            <span class="text-primary action-image d-block" id="chat-cloudtalk-editphone-btn-right-<?php echo $chat->id?>" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Click to update phone number')?>"><span class="material-icons" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/adminchat','Edit')?>">edit</span> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Edit phone')?></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

<?php endif;?>