<div class="form-group phone-edit-field-<?php echo $chat->id?>">
    <div class="row pb-2">
        <div class="col-1 me-0 pe-0 pt-4 text-center">
            <svg style=" width: 20px;" class="PhoneInputCountryIconImg mt-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 75 50"><title>International</title><g class="PhoneInputInternationalIconGlobe" stroke="currentColor" fill="none" stroke-width="2" stroke-miterlimit="10"><path stroke-linecap="round" d="M47.2,36.1C48.1,36,49,36,50,36c7.4,0,14,1.7,18.5,4.3"></path><path d="M68.6,9.6C64.2,12.3,57.5,14,50,14c-7.4,0-14-1.7-18.5-4.3"></path><line x1="26" y1="25" x2="74" y2="25"></line><line x1="50" y1="1" x2="50" y2="49"></line><path stroke-linecap="round" d="M46.3,48.7c1.2,0.2,2.5,0.3,3.7,0.3c13.3,0,24-10.7,24-24S63.3,1,50,1S26,11.7,26,25c0,2,0.3,3.9,0.7,5.8"></path><path stroke-linecap="round" d="M46.8,48.2c1,0.6,2.1,0.8,3.2,0.8c6.6,0,12-10.7,12-24S56.6,1,50,1S38,11.7,38,25c0,1.4,0.1,2.7,0.2,4c0,0.1,0,0.2,0,0.2"></path></g><path class="PhoneInputInternationalIconPhone" stroke="none" fill="currentColor" d="M12.4,17.9c2.9-2.9,5.4-4.8,0.3-11.2S4.1,5.2,1.3,8.1C-2,11.4,1.1,23.5,13.1,35.6s24.3,15.2,27.5,11.9c2.8-2.8,7.8-6.3,1.4-11.5s-8.3-2.6-11.2,0.3c-2,2-7.2-2.2-11.7-6.7S10.4,19.9,12.4,17.9z"></path></svg>
            <img class="img-country-svg mx-auto mt-3" src="" style="display: none; width: 20px;" />
        </div>
        <div class="col-11">
            <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chat/modifychat','Phone');?></label>
            <input name="UserPhone" data-country-default="<?php echo strtoupper($chat->country_code)?>" value="<?php echo htmlspecialchars($chat->phone);?>" type="text" placeholder="+" class="form-control form-control-sm phone-field">
            <div class="valid-feedback fs12" data-default="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Ready to update!')?>">
                <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Ready to update!')?>
            </div>
            <div class="invalid-feedback fs12" data-default="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Complete your phone number...')?>">
                <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Complete phone number.')?>
            </div>
        </div>
    </div>
</div>
<script>ee.emitEvent('cloudtalk.init_phone_field', [<?php echo $chat->id?>,'<?php echo erLhcoreClassDesign::designJS('js/libphonenumber-js.js');?>']);</script>