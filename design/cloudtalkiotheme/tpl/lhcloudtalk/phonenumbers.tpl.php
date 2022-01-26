<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Phone Numbers');?></h1>

<?php if (isset($items)) : ?>
    <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%" ng-non-bindable>
        <thead>
        <tr>
            <th width="1%"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','ID');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Phone number');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Department');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Active');?></th>
            <th width="1%"></th>
        </tr>
        </thead>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td nowrap="">
                    <?php echo htmlspecialchars($item->id) ?>
                </td>
                <td>
                    <a href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/editphone')?>/<?php echo $item->id?>"><?php echo htmlspecialchars($item->phone)?></a>
                </td>
                <td>
                    <?php echo htmlspecialchars($item->department)?>
                </td>
                <td>
                    <?php echo htmlspecialchars($item->active)?>
                </td>
                <td>
                    <a class="material-icons text-danger csfr-required" onclick="return confirm('<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('kernel/messages','Are you sure?');?>')" href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/deletephone')?>/<?php echo $item->id?>">delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

    <?php if (isset($pages)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
    <?php endif;?>
<?php endif; ?>


<a href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/newphone')?>" class="btn btn-secondary btn-sm"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','New phone number');?></a>