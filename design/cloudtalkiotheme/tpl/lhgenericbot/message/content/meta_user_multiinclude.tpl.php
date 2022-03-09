<?php if ($type == 'cloudtalk') : ?>
    <div class="msg-body text-dark msg-body-widget bg-white rounded p-2 m-0 my-3 shadow border mr-0" id="cloudtalk-widget-status-<?php echo $msg['id']?>">
        <?php include(erLhcoreClassDesign::designtpl('lhgenericbot/message/content/call_widget.tpl.php'));?>
    </div>
<?php endif; ?>
