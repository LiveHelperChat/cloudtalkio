<?php if (!(isset($metaMessage['mode']) && $metaMessage['mode'] == 'phone')) : ?>
<h6 class="text-center"><?php echo htmlspecialchars($msg['name_support'])?></h6>
<p class="text-center mb-2"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Start a call with an agent')?></p>
<p class="text-center text-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','No charges or software to install')?></p>
<?php else : ?>
<h6 class="text-center"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Update your phone number')?></h6>
<?php endif; ?>

<?php if (($metaMessage['status'] == 'updatephone') || (isset($metaMessage['mode']) && $metaMessage['mode'] == 'phone')) : ?>

<div class="row pb-2">
    <div class="col-2 mr-0 pr-0 pt-2">
        <svg id="international-phone-number-svg-<?php echo $msg['id']?>" class="PhoneInputCountryIconImg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 75 50"><title>International</title><g class="PhoneInputInternationalIconGlobe" stroke="currentColor" fill="none" stroke-width="2" stroke-miterlimit="10"><path stroke-linecap="round" d="M47.2,36.1C48.1,36,49,36,50,36c7.4,0,14,1.7,18.5,4.3"></path><path d="M68.6,9.6C64.2,12.3,57.5,14,50,14c-7.4,0-14-1.7-18.5-4.3"></path><line x1="26" y1="25" x2="74" y2="25"></line><line x1="50" y1="1" x2="50" y2="49"></line><path stroke-linecap="round" d="M46.3,48.7c1.2,0.2,2.5,0.3,3.7,0.3c13.3,0,24-10.7,24-24S63.3,1,50,1S26,11.7,26,25c0,2,0.3,3.9,0.7,5.8"></path><path stroke-linecap="round" d="M46.8,48.2c1,0.6,2.1,0.8,3.2,0.8c6.6,0,12-10.7,12-24S56.6,1,50,1S38,11.7,38,25c0,1.4,0.1,2.7,0.2,4c0,0.1,0,0.2,0,0.2"></path></g><path class="PhoneInputInternationalIconPhone" stroke="none" fill="currentColor" d="M12.4,17.9c2.9-2.9,5.4-4.8,0.3-11.2S4.1,5.2,1.3,8.1C-2,11.4,1.1,23.5,13.1,35.6s24.3,15.2,27.5,11.9c2.8-2.8,7.8-6.3,1.4-11.5s-8.3-2.6-11.2,0.3c-2,2-7.2-2.2-11.7-6.7S10.4,19.9,12.4,17.9z"></path></svg>
        <img data-ignore-load id="img-country-svg-<?php echo $msg['id']?>" src="" style="display: none" />
    </div>
    <div class="col-10">
        <input <?php if (isset($metaMessage['status_sub']) && $metaMessage['status_sub'] == 'updated_phone') : ?>disabled="disabled"<?php endif;?> data-phone-default="<?php echo htmlspecialchars($chat->phone)?>" data-country-default="<?php echo strtoupper($chat->country_code)?>" type="text" id="international-phone-number-<?php echo $msg['id']?>-val" placeholder="+" class="form-control form-control-sm<?php if (isset($metaMessage['status_sub']) && $metaMessage['status_sub'] == 'invalid_phone') : ?> is-invalid<?php elseif (isset($metaMessage['status_sub']) && $metaMessage['status_sub'] == 'updated_phone') : ?> is-valid<?php endif;?>">
        <div class="valid-feedback fs12" data-default="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Ready to update!')?>">
            <?php if (isset($metaMessage['status_sub']) && $metaMessage['status_sub'] == 'updated_phone') : ?>
                <b><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Phone updated!')?></b>
            <?php else : ?>
                <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Ready to update!')?>
            <?php endif; ?>
        </div>
        <div class="invalid-feedback fs12" data-default="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Complete your phone number...')?>">
            <?php if (isset($metaMessage['status_sub']) && $metaMessage['status_sub'] == 'invalid_phone') : ?>
                <b><?php echo htmlspecialchars($metaMessage['message_validation'])?></b>
            <?php else : ?>
                <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Complete phone number.')?>
            <?php endif; ?>
        </div>
    </div>
</div>

<input type="hidden" input="phone-type-<?php echo $msg['id']?>" >

<?php include(erLhcoreClassDesign::designtpl('lhgenericbot/message/content/phone_update_options.tpl.php'));?>

<div class="row">
    <div class="col-10 offset-2">

        <?php if (isset($metaMessage['status_sub']) && $metaMessage['status_sub'] == 'updated_phone') : ?>
            <button data-no-change="true" data-bot-action="execute-js" data-bot-args='{"method":"update_phone","msg_id":"<?php echo $msg['id']?>"}' data-bot-extension="cloudtalk-call" onclick="lhinst.executeJS()" id="cloudtalk-msg-update-phone-<?php echo $msg['id']?>" class="btn btn-sm btn-secondary">
                <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Edit phone')?>
            </button>
        <?php else : ?>
            <button type="button" id="update-phone-action-<?php echo $msg['id']?>" disabled="disabled" class="btn btn-primary btn-sm">
                <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Update')?>
            </button>
        <?php endif; ?>

        <?php if (!(isset($metaMessage['mode']) && $metaMessage['mode'] == 'phone')) : ?>
        <button type="button" id="cancel-phone-action-<?php echo $msg['id']?>" class="btn float-right btn-outline-secondary btn-sm" ><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Cancel')?></button>
        <?php endif; ?>
    </div>
</div>

<script data-bot-always="true" class="meta-message-<?php echo $msg['id']?>" data-bot-action="execute-js" data-bot-args='{"method":"init_phone_form","msg_id":"<?php echo $msg['id']?>"}' data-bot-extension="cloudtalk-call" ></script>

<?php else : ?>

<div>
    <?php include(erLhcoreClassDesign::designtpl('lhgenericbot/message/content/status_call.tpl.php'));?>
</div>
<?php endif; ?>