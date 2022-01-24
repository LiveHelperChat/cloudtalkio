<?php if ($type == 'cloudtalk') : ?>
    <div class="msg-body text-dark bg-white rounded p-3 m-4 shadow border mr-0" id="cloudtalk-widget-status-<?php echo $msg['id']?>">
        <?php include(erLhcoreClassDesign::designtpl('lhgenericbot/message/content/call_widget.tpl.php'));?>
    </div>
<?php endif; ?>
