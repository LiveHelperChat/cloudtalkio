<?php if ($type == 'cloudtalk') : ?>
<div class="msg-body text-dark bg-white rounded p-3 shadow border mr-0" style="width: 250px">

    <h6 class="text-center"><?php echo htmlspecialchars($msg['name_support'])?></h6>

    <div id="status-call-<?php echo $msg['id']?>">
        <?php include(erLhcoreClassDesign::designtpl('lhgenericbot/message/content/call_status_admin.tpl.php'));?>
    </div>

    <?php if (in_array($metaMessage['status'],['start_sync','call_started','answered']) && (!isset($async_call) || $async_call == false)) : ?>
        <script>ee.emitEvent('cloudtalk.monitor_call', [<?php echo $msg['id']?>]);</script>
    <?php endif;?>

</div>
<?php endif; ?>
