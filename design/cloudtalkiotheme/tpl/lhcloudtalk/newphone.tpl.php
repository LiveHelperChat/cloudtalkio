<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','New phone');?></h1>

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<form action="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/newphone')?>" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhcloudtalk/parts/form_phone.tpl.php'));?>
    
    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" class="btn btn-sm btn-secondary" name="Save_page" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save');?>"/>
        <input type="submit" class="btn btn-sm btn-secondary" name="Cancel_page" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Cancel');?>"/>
    </div>
</form>