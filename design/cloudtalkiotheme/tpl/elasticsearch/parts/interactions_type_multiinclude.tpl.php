<?php if ($item instanceof LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoESCall) : ?>

    <i class="material-icons">phone</i>

    <?php echo htmlspecialchars($item->id)?>

    <?php include(erLhcoreClassDesign::designtpl('lhcloudtalk/parts/direction.tpl.php')); ?>

    <a href="tel:+<?php echo htmlspecialchars($item->phone)?>">+<?php echo htmlspecialchars($item->phone)?></a>

    <?php if ($item->chat_id > 0) : ?>
        <a onclick="lhc.previewChat(<?php echo $item->chat_id?>)"><i class="material-icons">info_outline</i></a>
        <a class="action-image material-icons" data-title="<?php echo htmlspecialchars($item->nick,ENT_QUOTES);?>" onclick="<?php if (isset($itemsArchive[$item->chat_id]) && $itemsArchive[$item->chat_id]['archive'] == true) : ?>lhinst.startChatNewWindowArchive('<?php echo $itemsArchive[$item->chat_id]['archive_id']?>','<?php echo $item->chat_id;?>',$(this).attr('data-title'))<?php else : ?>lhinst.startChatNewWindow('<?php echo $item->chat_id;?>',$(this).attr('data-title'))<?php endif;?>" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/pendingchats','Open in a new window');?>">open_in_new</a>
    <?php endif; ?>

    <?php if ($item->chat_id > 0) : ?>
    <a href="#!#Fchat-id-<?php echo $item->chat_id?>" ng-click="lhc.startChatByID(<?php echo $item->chat_id?>)"><span class="material-icons">chat</span><?php echo $item->chat_id?></a>
    <?php endif; ?>

<?php endif; ?>