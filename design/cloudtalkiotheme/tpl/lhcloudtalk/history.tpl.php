<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call history');?></h1>

<?php include(erLhcoreClassDesign::designtpl('lhcloudtalk/parts/form_filter.tpl.php'));?>

<?php if (isset($items)) : ?>
    <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%" ng-non-bindable>
        <thead>
        <tr>
            <th width="1%"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','ID');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Department');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Visitor');?></th>
            <?php include(erLhcoreClassDesign::designtpl('lhcloudtalk/extensions/visitor_column_multiinclude.tpl.php')); ?>
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
                <td nowrap="" title="<?php echo date(erLhcoreClassModule::$dateDateHourFormat,$item->created_at);?>">
                    <?php echo htmlspecialchars($item->id) ?><a class="material-icons" onclick="lhc.revealModal({'url':WWW_DIR_JAVASCRIPT+'cloudtalkio/rawjson/<?php echo $item->id?>'})">info_outline</a><span title="<?php if ($item->contact_removed == 1) : ?><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Contact removed');?><?php else : ?><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Contact exists in CloudTalk');?><?php endif; ?>" class="ms-1 material-icons <?php if ($item->contact_removed == 1) : ?>text-danger<?php else : ?>text-success<?php endif; ?>">contact_phone</span>
                </td>
                <td title="<?php echo htmlspecialchars((string)$item->phone_from)?>">
                    <?php echo htmlspecialchars((string)$item->department)?>
                </td>
                <td>
                    <a class="mx-1" href="tel:+<?php echo htmlspecialchars($item->phone)?>">+<?php echo htmlspecialchars($item->phone)?></a>
                    <?php if ($item->email != '') : ?>
                        <br/><a class="mx-1" href="mailto:<?php echo htmlspecialchars($item->email) ?>"><?php echo htmlspecialchars($item->email) ?></a>
                    <?php endif; ?>
                    <?php if ($item->nick != '') : ?>
                        <?php include(erLhcoreClassDesign::designtpl('lhcloudtalk/extensions/visitor_nick.tpl.php')); ?>
                    <?php endif; ?>
                </td>
                <?php include(erLhcoreClassDesign::designtpl('lhcloudtalk/extensions/visitor_column_data_multiinclude.tpl.php')); ?>
                <td>
                    <?php echo htmlspecialchars($item->user)?>
                </td>
                <td>
                    <?php echo htmlspecialchars($item->cloudtalk_user)?>
                </td>
                <td>
                    <?php if ($item->status == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_PENDING) : ?>
                        <span class="badge bg-warning"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Pending')?></span>
                    <?php endif; ?>

                    <?php if ($item->status == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_STARTED) : ?>
                        <span class="badge bg-primary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Started')?></span>
                    <?php endif; ?>

                    <?php if ($item->status == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_RINGING_AGENT) : ?>
                        <span class="badge bg-primary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Ringing agent')?></span>
                    <?php endif; ?>

                    <?php if ($item->status == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_ANSWERED) : ?>
                        <span class="badge bg-success"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Answered')?></span>
                    <?php endif; ?>

                    <?php if ($item->status == LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall::STATUS_ENDED) : ?>
                        <span class="badge bg-success"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Ended')?></span>
                    <?php endif; ?>
                    
                    <?php if ($item->exclude_autoasign == 1) : ?>
                        <span title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','Auto assignment is turned off while call is happening');?>" class="material-icons text-danger">assignment_turned_in</span>
                    <?php endif;?>

                </td>
                <td>
                    <?php include(erLhcoreClassDesign::designtpl('lhcloudtalk/parts/direction.tpl.php')); ?>
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