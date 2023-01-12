<span class="material-icons action-image text-primary" onclick="lhc.revealModal({'url':WWW_DIR_JAVASCRIPT+'cloudtalkio/assignagent/<?php echo $item->id?>'});" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cbscheduler','Assign operator');?>">sync_alt</span>

<?php if ($item->user instanceof \erLhcoreClassModelUser) : ?>
    <a title="<?php echo $item->user_id?>" href="<?php echo erLhcoreClassDesign::baseurl('user/edit')?>/<?php echo $item->user_id?>">
        <span class="material-icons">account_box</span>
        <?php echo htmlspecialchars($item->user->username)?><?php echo htmlspecialchars($item->user->chat_nickname !== '' ? ' ('. $item->user->chat_nickname .')' : '')?>
    </a>
<?php elseif ($item->user_id > 0) : ?>
    <span class="badge bg-danger"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Operator not found')?> [<?php echo $item->user_id?>]</span>
<?php else : ?>
    <span class="badge bg-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','No operator assigned')?></span>
<?php endif; ?>