<?php if (!empty($chat->phone) && erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio', 'use_operator')) : ?>
<a href="#" class="w-100 btn btn-outline-secondary" data-trans="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Are you sure?')?>" id="chat-cloudtalk-invitation-btn-<?php echo $chat->id?>" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Send invitation to a call')?>">
    <i class="material-icons mr-0">phone</i>
</a>
<?php endif; ?>