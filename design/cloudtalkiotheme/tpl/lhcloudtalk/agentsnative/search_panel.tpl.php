<form action="<?php echo $input->form_action?>" method="get" name="SearchFormRight" class="mb-2" ng-non-bindable>
    <input type="hidden" name="doSearch" value="1">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Operator username');?></label>
                <input type="text" class="form-control form-control-sm" name="username" value="<?php echo htmlspecialchars($input->username)?>" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Firstname');?></label>
                <input type="text" class="form-control form-control-sm" name="firstname" value="<?php echo htmlspecialchars($input->firstname)?>" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Lastname');?></label>
                <input type="text" class="form-control form-control-sm" name="lastname" value="<?php echo htmlspecialchars((string)$input->lastname)?>" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','E-mail');?></label>
                <input type="text" class="form-control form-control-sm" name="email" value="<?php echo htmlspecialchars($input->email)?>" />
            </div>
        </div>
    </div>
    <div class="btn-group" role="group" aria-label="...">
        <input type="submit" name="doSearch" class="btn btn-sm btn-secondary" value="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Search');?>" />
        <a class="btn btn-outline-secondary btn-sm" href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/agentsnative')?>"><span class="material-icons">refresh</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/lists/search_panel','Reset');?></a>
    </div>
</form>
