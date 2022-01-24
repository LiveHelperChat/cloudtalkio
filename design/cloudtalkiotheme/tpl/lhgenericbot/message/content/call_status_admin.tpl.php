<?php if ($metaMessage['status'] == 'invite') : // Invite to start a call from visitor ?>
    <button disabled type="button" id="cloudtalk-msg-<?php echo $msg['id']?>" class="btn d-block w-100 btn-sm btn-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Waiting for visitor to start a call')?></button>
<?php elseif ($metaMessage['status'] == 'start_sync') : // Invite to start a call from visitor ?>
    <div class="bg-primary rounded p-2 text-white fs14"><span class="material-icons">phone</span> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Calling to an operator...')?></div>
<?php elseif ($metaMessage['status'] == 'call_started') : // Invite to start a call from visitor ?>
    <div class="bg-primary rounded p-2 text-white fs14"><span class="material-icons">phone</span> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Calling to visitor...')?></div>
<?php elseif ($metaMessage['status'] == 'answered') : // Invite to start a call from visitor ?>
    <div class="bg-success rounded p-2 text-white fs14"><span class="material-icons">phone</span>
        <?php if (isset($metaMessage['answered_at'])) : ?>
            <?php echo erLhcoreClassChat::formatSeconds(max(time() - $metaMessage['answered_at'],1))?>
        <?php else : ?>
            <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','In a call...')?>
        <?php endif; ?>
    </div>
<?php elseif ($metaMessage['status'] == 'failure') : // Invite to start a call from visitor ?>
    <div class="bg-light rounded p-2 fs14">&#128533; <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call has failed...')?></div>
<?php elseif ($metaMessage['status'] == 'ended') : // Invite to start a call from visitor ?>
    <div class="bg-secondary rounded p-2 text-white fs14"><span class="material-icons">phone</span> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call has ended!')?> <?php if (isset($metaMessage['answered_at'])) : ?><?php echo erLhcoreClassChat::formatSeconds((isset($metaMessage['ended_at']) ? $metaMessage['ended_at'] :  time()) - $metaMessage['answered_at'])?><?php endif; ?></div>
<?php endif; ?>