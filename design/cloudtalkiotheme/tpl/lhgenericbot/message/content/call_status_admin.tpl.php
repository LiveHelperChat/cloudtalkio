<?php if ($metaMessage['status'] == 'invite') : // Invite to start a call from visitor ?>

    <?php if (isset($metaMessage['mode']) && $metaMessage['mode'] == 'phone') : ?>
        <?php if (isset($metaMessage['status_sub']) && $metaMessage['status_sub'] == 'updated_phone') : ?>
            <?php include(erLhcoreClassDesign::designtpl('lhcloudtalk/admin_status/phone_updated.tpl.php')); ?>
        <?php elseif (isset($metaMessage['status_sub']) && $metaMessage['status_sub'] == 'invalid_phone') : ?>
            <p class="text-center text-danger"><?php echo htmlspecialchars($metaMessage['message_validation'])?></p>
        <?php else : ?>
            <p class="text-center"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Visitor updating phone')?></p>
        <?php endif; ?>
    <?php else : ?>
        <p class="text-center"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Waiting for visitor to start a call')?></p>
        <button type="button" onclick="ee.emitEvent('cloudtalk.cancel_call', [<?php echo $msg['id']?>]);" class="btn d-block w-100 btn-sm btn-warning">
            <span class="material-icons">phone_disabled</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Cancel an invitation')?>
            <?php if (isset($metaMessage['phone'])) : ?><?php echo htmlspecialchars($metaMessage['phone'])?><?php endif;?>
        </button>
    <?php endif; ?>

<?php elseif ($metaMessage['status'] == 'updatephone') : ?>

    <p class="text-center text-info"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Visitor updating phone')?></p>

<?php elseif ($metaMessage['status'] == 'start_sync') : // Invite to start a call from visitor ?>
    <p class="text-center"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Calling to an operator...')?></p>
    <button type="button" onclick="ee.emitEvent('cloudtalk.cancel_call', [<?php echo $msg['id']?>]);" class="btn d-block w-100 btn-sm btn-warning">
        <span class="material-icons">phone_disabled</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Cancel a call')?>
    </button>
<?php elseif ($metaMessage['status'] == 'call_started') : // Invite to start a call from visitor ?>
    <div class="bg-primary rounded p-2 text-white fs14"><span class="material-icons">phone</span> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Calling to visitor...')?></div>
<?php elseif ($metaMessage['status'] == 'canceled') : ?>
    <p class="text-center text-danger"><span class="material-icons">phone_disabled</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call was canceled by operator')?></p>
<?php elseif ($metaMessage['status'] == 'answered') : // Invite to start a call from visitor ?>
    <div class="bg-success rounded p-2 text-white fs14"><span class="material-icons">phone</span>
        <?php if (isset($metaMessage['answered_at'])) : ?>
            <?php echo erLhcoreClassChat::formatSeconds(max(time() - $metaMessage['answered_at'],1))?>
        <?php else : ?>
            <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','In a call...')?>
        <?php endif; ?>
    </div>
<?php elseif ($metaMessage['status'] == 'failure') : // Invite to start a call from visitor ?>
    <div class="bg-light rounded p-2 fs14">&#128533; <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call has failed...')?>
        <?php if (isset($metaMessage['failure_reason'])) : ?>
            <br/><?php echo htmlspecialchars($metaMessage['failure_reason']); ?>
        <?php endif; ?>
    </div>
<?php elseif ($metaMessage['status'] == 'ended') : // Invite to start a call from visitor ?>
    <div class="bg-secondary rounded p-2 text-white fs14"><span class="material-icons">phone</span> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call has ended!')?> <?php if (isset($metaMessage['answered_at'])) : ?><?php echo erLhcoreClassChat::formatSeconds((isset($metaMessage['ended_at']) ? $metaMessage['ended_at'] :  time()) - $metaMessage['answered_at'])?><?php endif; ?></div>
<?php endif; ?>