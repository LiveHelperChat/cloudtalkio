<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Phone without + sign.');?>*</label>
    <div class="input-group input-group-sm mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">+</span>
        </div>
        <input type="text" maxlength="100" class="form-control" name="phone" value="<?php echo htmlspecialchars($item->phone)?>" />
    </div>
</div>

<div class="form-group">
    <label><input type="checkbox" name="active" value="on" <?php $item->active == 1 ? print ' checked="checked" ' : ''?> > <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Active');?></label>
</div>

<div class="form-group">
    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger','Department');?>*</label>
    <?php echo erLhcoreClassRenderHelper::renderCombobox(array(
        'input_name'     => 'dep_id',
        'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select department'),
        'selected_id'    => $item->dep_id,
        'css_class'      => 'form-control form-control-sm',
        'list_function'  => 'erLhcoreClassModelDepartament::getList',
        'list_function_params'  => array('limit' => false),
    )); ?>
</div>