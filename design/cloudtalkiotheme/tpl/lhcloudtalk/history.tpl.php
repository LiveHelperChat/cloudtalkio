<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call history');?></h1>

<?php if (isset($items)) : ?>
    <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%" ng-non-bindable>
        <thead>
        <tr>
            <th width="1%"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','ID');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Chat agent');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','CloudTalk agent');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Live status');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call status');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Waiting time');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Talking time');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Wrap up time');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call ID');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Chat ID');?></th>
        </tr>
        </thead>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td><?php echo htmlspecialchars($item->id) ?></td>
                <td>
                    <?php echo htmlspecialchars($item->user)?>
                </td>
                <td>
                    <?php echo htmlspecialchars($item->cloudtalk_user)?>
                </td>
                <td>
                    <?php if ($item->status == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_PENDING) : ?>
                        <span class="badge badge-warning"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Pending')?></span>
                    <?php endif; ?>

                    <?php if ($item->status == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_STARTED) : ?>
                        <span class="badge badge-primary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Started')?></span>
                    <?php endif; ?>

                    <?php if ($item->status == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_RINGING_AGENT) : ?>
                        <span class="badge badge-primary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Ringing agent')?></span>
                    <?php endif; ?>

                    <?php if ($item->status == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_ANSWERED) : ?>
                        <span class="badge badge-success"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Answered')?></span>
                    <?php endif; ?>

                    <?php if ($item->status == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_ENDED) : ?>
                        <span class="badge badge-success"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Ended')?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($item->status_call == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_PENDING_CALL_ID) : ?>
                        <span class="badge badge-warning"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Pending call history')?></span>
                    <?php else : ?>
                        <span title="<?php echo $item->direction == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::DIRECTION_OUTBOUND ? erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Outbound') : erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Inbound')?>" class="material-icons<?php ($item->status_outcome == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_OUTCOME_ANSWERED) ? print ' text-success' : print ' text-danger'?>">
                            <?php if ($item->direction == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::DIRECTION_OUTBOUND) : ?>north_east<?php else : ?>north_east<?php endif; ?>
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $item->waiting_time?> s.
                </td>
                <td>
                    <?php echo $item->talking_time?> s.
                </td>
                <td>
                    <?php echo $item->wrapup_time?> s.
                </td>
                <td title="<?php echo htmlspecialchars($item->call_uuid)?>">
                    <?php echo $item->call_id?>
                    <?php if ($item->recording_url != '') : ?>
                    <a href="<?php echo $item->recording_url?>" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Download a record')?>" target="_blank" class="material-icons">play_arrow</a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $item->chat_id?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

    <?php if (isset($pages)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
    <?php endif;?>
<?php endif; ?>