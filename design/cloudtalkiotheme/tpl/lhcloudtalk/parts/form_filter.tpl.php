<form action="<?php echo $input->form_action?>" method="get" class="mb-2" ng-non-bindable>
    <input type="hidden" name="doSearch" value="1">
    <input type="hidden" name="ds" value="1">
    <div class="row pb-2">

        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Phone (To)');?></label>
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="basic-addon1">+</span>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars((string)$input->phone)?>" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Call ID');?></label>
                <input type="text" name="call_external_id" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$input->call_external_id)?>" >
            </div>
        </div>

        <div class="col-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Agent');?></label>
                <?php echo erLhcoreClassRenderHelper::renderMultiDropdown( array (
                    'input_name'     => 'user_ids[]',
                    'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select agent'),
                    'selected_id'    => $input->user_ids,
                    'css_class'      => 'form-control',
                    'display_name'   => 'name_official',
                    'list_function_params' => erLhcoreClassGroupUser::getConditionalUserFilter(),
                    'list_function'  => 'erLhcoreClassModelUser::getUserList'
                )); ?>
            </div>
        </div>

        <div class="col-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Agent group');?></label>
                <?php echo erLhcoreClassRenderHelper::renderMultiDropdown( array (
                    'input_name'     => 'group_ids[]',
                    'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select group'),
                    'selected_id'    => $input->group_ids,
                    'css_class'      => 'form-control',
                    'display_name'   => 'name',
                    'list_function_params' => erLhcoreClassGroupUser::getConditionalUserFilter(false, true),
                    'list_function'  => 'erLhcoreClassModelGroup::getList'
                )); ?>
            </div>
        </div>

        <div class="col-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Department');?></label>
                <?php echo erLhcoreClassRenderHelper::renderMultiDropdown( array (
                    'input_name'     => 'department_ids[]',
                    'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Choose department'),
                    'selected_id'    => $input->department_ids,
                    'css_class'      => 'form-control',
                    'display_name'   => 'name',
                    'list_function_params' => erLhcoreClassUserDep::conditionalDepartmentFilter(),
                    'list_function'  => 'erLhcoreClassModelDepartament::getList'
                )); ?>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Department group');?></label>
                <?php echo erLhcoreClassRenderHelper::renderMultiDropdown( array (
                    'input_name'     => 'department_group_ids[]',
                    'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Choose department group'),
                    'selected_id'    => $input->department_group_ids,
                    'css_class'      => 'form-control',
                    'display_name'   => 'name',
                    'list_function_params' => erLhcoreClassUserDep::conditionalDepartmentGroupFilter(),
                    'list_function'  => 'erLhcoreClassModelDepartamentGroup::getList'
                )); ?>
            </div>
        </div>

        <div class="col-md-2">
            <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Sort by');?></label>
            <select name="sortby" class="form-control form-control-sm">
                <option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Choose');?></option>
                <option <?php if ($input->sortby == 'iddesc'|| $input->sortby == '') : ?>selected="selected"<?php endif; ?> value="iddesc">Newest first</option>
                <option <?php if ($input->sortby == 'idasc') : ?>selected="selected"<?php endif; ?> value="idasc">Oldest first</option>
            </select>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Date range from');?>
                    <span onclick="cbSchedulerSetTodays(true)" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Set filter to today');?>" class="action-image badge bg-secondary">Set todays filter</span>
                    <span onclick="cbSchedulerSetTodays(false)" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Set filter to today');?>" class="action-image badge bg-secondary">Reset</span>
                </label>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-sm" name="timefrom" id="id_timefrom" placeholder="E.g <?php echo date('Y-m-d',time()-7*24*3600)?>" value="<?php echo htmlspecialchars((string)$input->timefrom != '' ? $input->timefrom : (isset($default_date) ? $default_date : ''))?>" />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Hour and minute from');?></label>
                <div class="row">
                    <div class="col-md-6">
                        <select name="timefrom_hours" id="id_timefrom_hours" class="form-control form-control-sm">
                            <option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select hour');?></option>
                            <?php for ($i = 0; $i <= 23; $i++) : ?>
                                <option value="<?php echo $i?>" <?php if (isset($input->timefrom_hours) && $input->timefrom_hours === $i) : ?>selected="selected"<?php endif;?>><?php echo str_pad($i,2, '0', STR_PAD_LEFT);?> h.</option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="timefrom_minutes" id="id_timefrom_minutes" class="form-control form-control-sm">
                            <option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select minute');?></option>
                            <?php for ($i = 0; $i <= 59; $i++) : ?>
                                <option value="<?php echo $i?>" <?php if (isset($input->timefrom_minutes) && $input->timefrom_minutes === $i) : ?>selected="selected"<?php endif;?>><?php echo str_pad($i,2, '0', STR_PAD_LEFT);?> m.</option>
                            <?php endfor;?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Date range to');?></label>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-sm" name="timeto" id="id_timeto" placeholder="E.g <?php echo date('Y-m-d')?>" value="<?php echo htmlspecialchars((string)$input->timeto)?>" />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Hour and minute to');?></label>
                <div class="row">
                    <div class="col-md-6">
                        <select name="timeto_hours" id="id_timeto_hours" class="form-control form-control-sm">
                            <option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select hour');?></option>
                            <?php for ($i = 0; $i <= 23; $i++) : ?>
                                <option value="<?php echo $i?>" <?php if (isset($input->timeto_hours) && $input->timeto_hours === $i) : ?>selected="selected"<?php endif;?>><?php echo str_pad($i,2, '0', STR_PAD_LEFT);?> h.</option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="timeto_minutes" id="id_timeto_minutes" class="form-control form-control-sm">
                            <option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Select minute');?></option>
                            <?php for ($i = 0; $i <= 59; $i++) : ?>
                                <option value="<?php echo $i?>" <?php if (isset($input->timeto_minutes) && $input->timeto_minutes === $i) : ?>selected="selected"<?php endif;?>><?php echo str_pad($i,2, '0', STR_PAD_LEFT);?> m.</option>
                            <?php endfor;?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="row">
                <div class="col-6">
                    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Direction');?></label>
                    <select name="direction" class="form-control form-control-sm">
                        <option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Any');?></option>
                        <option <?php if ($input->direction === 0) : ?>selected="selected"<?php endif; ?> value="0"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Outbound');?></option>
                        <option <?php if ($input->direction === 1) : ?>selected="selected"<?php endif; ?> value="1"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Inbound');?></option>
                    </select>
                </div>
                <div class="col-6">
                    <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Outcome');?></label>
                    <select name="status_outcome" class="form-control form-control-sm">
                        <option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Any');?></option>
                        <option <?php if ($input->status_outcome === 0) : ?>selected="selected"<?php endif; ?> value="0"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Missed');?></option>
                        <option <?php if ($input->status_outcome === 1) : ?>selected="selected"<?php endif; ?> value="1"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Answered');?></option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Phone (From)');?></label>
            <?php echo erLhcoreClassRenderHelper::renderMultiDropdown( array (
                'input_name'     => 'phone_from_ids[]',
                'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Phone number'),
                'selected_id'    => $input->phone_from_ids,
                'css_class'      => 'form-control',
                'display_name'   => 'phone',
                'list_function_params' => ['limit' => false],
                'list_function'  => '\LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoPhoneNumber::getList'
            )); ?>
        </div>

        <div class="col-md-2">
            <label><input type="checkbox" name="chat_call" <?php if ($input->chat_call === true) : ?>checked="checked"<?php endif;?> value="on">&nbsp;<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Chat based call');?></label>
        </div>

    </div>
    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" name="doSearch" class="btn btn-sm btn-secondary" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Search');?>" />

        <?php if (isset($pages) && $pages->items_total > 0) : ?>
                <a href="<?php echo $pages->serverURL?>/(export)/1" class="btn btn-outline-secondary btn-sm"><span class="material-icons">file_download</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Export')?> (<?php echo $pages->items_total?> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','calls');?>)</a>
        <?php endif; ?>

        <a href="<?php echo $input->form_action?>" class="btn btn-sm btn-outline-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Reset');?></a>
    </div>
</form>

<script>
    function cbSchedulerSetTodays(filter){
        if (filter == true) {
            $('#id_timefrom').val('<?php echo date('Y-m-d')?>');
            $('#id_timeto').val('<?php echo date('Y-m-d',time()+24*3600)?>');
            $('#id_timefrom_hours, #id_timefrom_minutes, #id_timeto_hours, #id_timeto_minutes').val('');
        } else {
            $('#id_timefrom, #id_timefrom_hours, #id_timefrom_minutes, #id_timeto, #id_timeto_hours, #id_timeto_minutes').val('');
        }
    }
    $(function() {
        $('#id_timefrom,#id_timeto').fdatepicker({
            format: 'yyyy-mm-dd'
        });
        $('.btn-block-department').makeDropdown();
    });
</script>


