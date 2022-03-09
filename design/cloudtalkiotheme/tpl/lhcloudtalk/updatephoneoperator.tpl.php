<?php $modalHeaderTitle = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/blockedusers','Update phone')?>
<?php include(erLhcoreClassDesign::designtpl('lhkernel/modal_header.tpl.php'));?>
    <form action="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/updatephoneoperator')?>/<?php echo $chat->id?>" method="post" onsubmit="return lhinst.submitModalForm($(this))">

        <?php if (isset($errors)) : ?>
            <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
        <?php endif; ?>

        <?php if (isset($chat_updated) && $chat_updated == 'true') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('chat/modifychat','Chat information was updated'); ?>
            <script>
               lhinst.updateVoteStatus('<?php echo $chat->id?>');
            </script>
            <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
        <?php endif; ?>

        <?php include(erLhcoreClassDesign::designtpl('lhcloudtalk/parts/form_updatephoneoperator.tpl.php'));?>

        <button type="submit" class="btn btn-sm btn-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/blockedusers','Update')?></button>
    </form>
<?php include(erLhcoreClassDesign::designtpl('lhkernel/modal_footer.tpl.php'));?>