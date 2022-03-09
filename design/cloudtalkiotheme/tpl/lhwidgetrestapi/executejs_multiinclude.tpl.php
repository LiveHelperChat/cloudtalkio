<?php
// Change to your Script identifier
if ($ext == 'cloudtalk-call') : ?>
(function () {
    window.lhcCloudTalk = {};
    var cloudTalkPhoneEditorLoaded = false;

    window.lhcCloudTalk.startMonitorCall = function(messageId, dispatch, getState, updateMessage) {
        setTimeout(function(){

            var state = getState();
            var chat_id = state.chatwidget.getIn(['chatData', 'id']);
            var hash = state.chatwidget.getIn(['chatData', 'hash']);

            window.lhcAxios.post('<?php echo '//' . $_SERVER['HTTP_HOST'] . erLhcoreClassDesign::baseurl('cloudtalkio/checkcallstatus')?>/' + chat_id + '/' + hash + '/' + messageId, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function(response){
                (response.data.status == 'start_sync' || response.data.status == 'call_started' || response.data.status == 'answered') && window.lhcCloudTalk.startMonitorCall(messageId, dispatch, getState, updateMessage);
                updateMessage({'msg_id' : messageId ,'id' : chat_id, 'hash' : hash})(dispatch, getState);
            })
        },1000);
    };

    window.lhcHelperfunctions.eventEmitter.addListener('cloudtalk-call.init', function (params, dispatch, getState, updateMessage) {

        if (params.action) {
            window.lhcCloudTalk.startMonitorCall(params['msg_id'], dispatch, getState, updateMessage);
            return;
        }

        var state = getState();
        var chat_id = state.chatwidget.getIn(['chatData', 'id']);
        var hash = state.chatwidget.getIn(['chatData', 'hash']);

        <?php include(erLhcoreClassDesign::designtpl('lhwidgetrestapi/cloudtalk_executejs_multiinclude.tpl.php'));?>

        if (params['method'] && params['method'] == 'init_phone_form') {
            var loadcbCloudTalk = function() {
                var phoneInput = document.getElementById('international-phone-number-'+params['msg_id']+'-val');
                var intIcon =  document.getElementById('international-phone-number-svg-'+params['msg_id']);
                var countryIcon =  document.getElementById('img-country-svg-'+params['msg_id']);

                if (phoneInput) {

                    if (phoneInput.getAttribute('data-country-default') != '') {
                        var countryCode = libphonenumber.getCountryCallingCode(phoneInput.getAttribute('data-country-default'));
                        if (phoneInput.value == '') {
                            phoneInput.value = '+'+countryCode;
                            intIcon.style.display = "none";
                            countryIcon.src = 'https://catamphetamine.gitlab.io/country-flag-icons/3x2/'+phoneInput.getAttribute('data-country-default')+'.svg';
                            countryIcon.style.display = "block";
                        }
                    }

                    if (phoneInput.getAttribute('data-phone-default') != '') {
                        phoneInput.value = phoneInput.getAttribute('data-phone-default');
                        var phonenumber = new libphonenumber.AsYouType('US');
                        phoneInput.value = phonenumber.input(phoneInput.value);
                        var parsedNumber = libphonenumber.parsePhoneNumberFromString(phoneInput.value);

                        if (parsedNumber && parsedNumber['country']) {
                            intIcon.style.display = "none";
                            countryIcon.src = 'https://catamphetamine.gitlab.io/country-flag-icons/3x2/'+parsedNumber['country']+'.svg';
                            countryIcon.style.display = "block";
                            if (parsedNumber.isValid()) {
                                var elm = document.getElementById('update-phone-action-'+params['msg_id']);
                                elm && (elm.disabled = false);
                            }
                        }
                    }

                    phoneInput.addEventListener('keyup', function(){

                        if (phoneInput.value[0] != '+') {
                            phoneInput.value = "+" + phoneInput.value;
                        }

                        var phonenumber = new libphonenumber.AsYouType('US');
                        phoneInput.value = phonenumber.input(phoneInput.value);
                        var parsedNumber = libphonenumber.parsePhoneNumberFromString(phoneInput.value);

                        if (parsedNumber && parsedNumber['country']) {
                            intIcon.style.display = "none";
                            countryIcon.src = 'https://catamphetamine.gitlab.io/country-flag-icons/3x2/'+parsedNumber['country']+'.svg';
                            countryIcon.style.display = "block";
                            if (parsedNumber.isValid()) {
                                phoneInput.classList.add("is-valid");
                                phoneInput.classList.remove("is-invalid");
                                document.getElementById('update-phone-action-'+params['msg_id']).disabled = false;
                            } else {
                                phoneInput.classList.add("is-invalid");
                                phoneInput.classList.remove("is-valid");
                                document.getElementById('update-phone-action-'+params['msg_id']).disabled = true;
                            }
                        } else {
                            phoneInput.classList.remove("is-invalid");
                            phoneInput.classList.remove("is-valid");
                        }
                    });

                    var updateElm = document.getElementById('update-phone-action-'+params['msg_id']);
                    updateElm && updateElm.addEventListener('click', function(){
                        var payload = {'phone': phoneInput.value};
                        <?php include(erLhcoreClassDesign::designtpl('lhwidgetrestapi/cloudtalk_updatephone_payload_multiinclude.tpl.php'));?>
                        window.lhcAxios.post('<?php echo '//' . $_SERVER['HTTP_HOST'] . erLhcoreClassDesign::baseurl('cloudtalkio/updatephone')?>/' + chat_id + '/' + hash + '/' + params['msg_id'] + '/(mode)/updatephone', payload, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function(response) {
                            updateMessage({'msg_id' : params['msg_id'] ,'id' : chat_id, 'hash' : hash})(dispatch, getState);
                        });
                    });

                    var updateElm = document.getElementById('cancel-phone-action-'+params['msg_id']);
                    updateElm && document.getElementById('cancel-phone-action-'+params['msg_id']).addEventListener('click', function(){
                        window.lhcAxios.post('<?php echo '//' . $_SERVER['HTTP_HOST'] . erLhcoreClassDesign::baseurl('cloudtalkio/updatephone')?>/' + chat_id + '/' + hash + '/' + params['msg_id'] + '/(mode)/cancelphone', {headers : {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function(response) {
                            updateMessage({'msg_id' : params['msg_id'] ,'id' : chat_id, 'hash' : hash})(dispatch, getState);
                        });
                    });

                    <?php include(erLhcoreClassDesign::designtpl('lhwidgetrestapi/cloudtalk_updatephone_executejs_multiinclude.tpl.php'));?>

                }
            };

            if (cloudTalkPhoneEditorLoaded === false) {
                var th = document.getElementsByTagName('head')[0];
                // Insert JS
                var src = window.lhcChat['staticJS']['chunk_js'].replace('/design/defaulttheme/js/widgetv2','') + '/extension/cloudtalkio/design/cloudtalkiotheme/js/libphonenumber-js.js?v=1';
                var s = document.createElement('script');
                s.setAttribute('type','text/javascript');
                s.setAttribute('src',src);
                th.appendChild(s);
                s.onreadystatechange = s.onload = function() {
                    loadcbCloudTalk();
                    cloudTalkPhoneEditorLoaded = true;
                };
            } else {
                loadcbCloudTalk();
            }

            return;
            }

        if (params['method'] && params['method'] == 'update_phone') {
            var payload = {};
            <?php include(erLhcoreClassDesign::designtpl('lhwidgetrestapi/cloudtalk_updatephone_payload_multiinclude.tpl.php'));?>
            window.lhcAxios.post('<?php echo '//' . $_SERVER['HTTP_HOST'] . erLhcoreClassDesign::baseurl('cloudtalkio/updatephone')?>/' + chat_id + '/' + hash + '/' + params['msg_id'] + '/(mode)/editphone', payload, {headers : {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function(response) {
                updateMessage({'msg_id' : params['msg_id'] ,'id' : chat_id, 'hash' : hash})(dispatch, getState);
            });
            return;
        }

        window.lhcAxios.post('<?php echo '//' . $_SERVER['HTTP_HOST'] . erLhcoreClassDesign::baseurl('cloudtalkio/startacall')?>/' + chat_id + '/' + hash + '/' + params['msg_id'], {headers : {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function(response) {
            if (
                response.data.status == 'start_sync' ||
                response.data.status == 'call_started' ||
                response.data.status == 'answered' ||
                response.data.status == 'failure'
            ) { // Waiting for operator to accept a call
                window.lhcCloudTalk.startMonitorCall(params['msg_id'], dispatch, getState, updateMessage);
                // Update a widget
                updateMessage({'msg_id' : params['msg_id'] ,'id' : chat_id, 'hash' : hash})(dispatch, getState);
            } else if (response.data.status == 'missing_phone') {
                alert(<?php echo json_encode(erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Seems you have not provided your phone number yet!'))?>);
            }
        })

    });

})();
<?php $extHandled = true; endif; ?>