<?php $modalHeaderTitle = erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Assign an agent to CloudTalk operator') . ' - ' . htmlspecialchars($native_user->firstname. ' '.$native_user->lastname) ?>
<?php include(erLhcoreClassDesign::designtpl('lhkernel/modal_header.tpl.php'));?>

    <div id="validation-output"></div>

    <input class="form-control mb-2 form-control-sm" id="search-assign-operator" type="text" placeholder="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Search for a user. First 50 users are shown.')?>" />
    <div class="form-group" id="search-changeowner-result" ng-non-bindable>
        <?php echo erLhcoreClassRenderHelper::renderCombobox( array (
            'input_name'     => 'new_user_id',
            'selected_id'    => $native_user->user_id,
            'css_class'      => 'form-control form-control-sm',
            'display_name'   => function($item){return $item->name_official . ($item->chat_nickname != '' ? ' | '.$item->chat_nickname : '');},
            'size' => 10,
            'list_function'  => 'erLhcoreClassModelUser::getUserList',
            'list_function_params'  => array('limit' => 50)
        )); ?>
    </div>

    <div class="btn-group" role="group" aria-label="...">
        <input type="button" id="cloudtalk-assign-operator" class="btn btn-sm btn-primary" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Assign');?>">
        <input type="button" id="cloudtalk-un-assign-operator" class="btn btn-sm btn-warning" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Un-assign');?>">
    </div>

    <script>
        (function(){

            $('#search-assign-operator').on('keyup',function(){
                var value = $(this).val();
                $.getJSON(WWW_DIR_JAVASCRIPT+ 'chat/searchprovider/users?q='+escape(value), function(result) {
                    var resultHTML = '';
                    result.items.forEach(function(item){
                        var selected = <?php echo $native_user->user_id?> == item.id ? ' selected="selected" ' : '';
                        resultHTML += "<option " + selected + " value=\""+item.id+"\">" + $("<div>").text(item.name + (item.nick != "" ? " | " + item.nick : '')).html() + "</option>";
                    });
                    $('#id_new_user_id').html(resultHTML);
                });
            });

            function assignAction(user_id) {
                $.postJSON(WWW_DIR_JAVASCRIPT + 'cloudtalkio/assignagent/<?php echo $native_user->id?>/(user)/' + user_id, function(result) {
                    $('#validation-output').html(result.validation);
                    if (!result.error) {
                        $('#native-agent-cell-'+<?php echo $native_user->id?>).html(result.operator_cell);
                    }
                });
            }

            $('#cloudtalk-assign-operator').on('click', function() {
                assignAction($('#id_new_user_id').val());
            });

            $('#cloudtalk-un-assign-operator').on('click', function() {
                assignAction(-1);
                $('#id_new_user_id').val('');
            });

        })();
    </script>
<?php include(erLhcoreClassDesign::designtpl('lhkernel/modal_footer.tpl.php'));?>