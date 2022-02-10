<?php if ($item->status_call == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_PENDING_CALL_ID) : ?>
    <span class="material-icons">
                            <?php if ($item->direction == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::DIRECTION_OUTBOUND) : ?>north_east<?php else : ?>call_received<?php endif; ?>
                        </span>
    <span class="badge badge-warning"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Pending call history')?></span>
<?php else : ?>
    <span title="<?php echo $item->direction == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::DIRECTION_OUTBOUND ? erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Outbound') : erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Inbound')?> | <?php ($item->status_outcome == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_OUTCOME_ANSWERED) ? print erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','answered') : print erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','not answered')?>" class="material-icons<?php ($item->status_outcome == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_OUTCOME_ANSWERED) ? print ' text-success' : print ' text-danger'?>">
                            <?php if ($item->direction == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::DIRECTION_OUTBOUND) : ?>north_east<?php else : ?>call_received<?php endif; ?>
                        </span>
<?php endif; ?>