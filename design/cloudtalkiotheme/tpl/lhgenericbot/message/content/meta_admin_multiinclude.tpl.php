<?php if ($type == 'cloudtalk') : ?>
<div class="msg-body text-dark bg-white rounded p-3 shadow border me-2" style="width: 250px">

    <h6 class="text-center">
        <?php if (isset($metaMessage['mode']) && $metaMessage['mode'] == 'phone') : ?>
            <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Phone number update')?>
        <?php else : ?>
            <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call with')?> - <?php echo htmlspecialchars($msg['name_support'])?>
        <?php endif; ?>
    </h6>

    <div id="status-call-<?php echo $msg['id']?>">
        <?php include(erLhcoreClassDesign::designtpl('lhgenericbot/message/content/call_status_admin.tpl.php'));?>
    </div>

    <?php if (in_array($metaMessage['status'],['start_sync','call_started','answered']) && (!isset($async_call) || $async_call == false)) : ?>
        <script>ee.emitEvent('cloudtalk.monitor_call', [<?php echo $msg['id']?>]);</script>
    <?php endif;?>

</div>
<?php endif; ?>
