<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/modifychat','Phone');?></label>
    <input class="form-control form-control-sm" type="text" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/modifychat','Phone');?>" name="UserPhone" value="<?php echo htmlspecialchars($chat->phone);?>" />
</div>