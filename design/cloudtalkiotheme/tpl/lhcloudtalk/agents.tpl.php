<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','Live Agent List');?></h1>

<?php if (isset($items)) : ?>
    <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%" ng-non-bindable>
        <thead>
        <tr>
            <th width="1%"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','ID');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','Name');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','Status');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','E-mail');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/cloudtalk','Associated');?></th>
        </tr>
        </thead>
        <?php foreach ($items->responseData->data as $itemObj) : $item = $itemObj->Agent?>
            <tr>
                <td><?php echo htmlspecialchars($item->id) ?></td>
                <td>
                    <?php echo htmlspecialchars($item->firstname)?> <?php echo htmlspecialchars($item->lastname)?>
                </td>
                <td>
                    <span class="badge <?php if ($item->availability_status == 'offline') : ?>badge-danger<?php elseif ($item->availability_status == 'idle') : ?> badge-warning<?php else : ?>badge-success<?php endif;?>">
                        <?php echo htmlspecialchars($item->availability_status)?>
                    </span>
                </td>
                <td>
                    <?php echo htmlspecialchars($item->email)?>
                </td>
                <td>
                    <span class="badge badge-secondary" title="<?php echo htmlspecialchars(implode(', ',$item->associated_numbers))?>"> <?php echo erLhcoreClassDesign::shrt(implode(', ',$item->associated_numbers),20,'...',30,ENT_QUOTES)?> </span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

    <?php if (isset($pages)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
    <?php endif;?>
<?php endif; ?>