(function() {

    function chatLoaded(chat_id) {
        $('#chat-cloudtalk-invitation-btn-'+chat_id+',#chat-cloudtalk-invitation-btn-right-'+chat_id+',#main-user-info-tab-'+chat_id+' .invite-call-action-'+chat_id).click(function(event){
            event.preventDefault();
            event.stopPropagation();
            lhinst.addmsgadmin(chat_id,'!cloudtalk --silent --arg ' + $(this).attr('data-phone'));
        });

        $('#chat-cloudtalk-updatenumber-btn-right-'+chat_id).click(function(event){
            event.preventDefault();
            event.stopPropagation();
            lhinst.addmsgadmin(chat_id,'!cloudtalk --silent --arg ' + $(this).attr('data-phone') + ' --arg updatephone');
        });

        $('#chat-cloudtalk-direct-'+chat_id).click(function(event){
            event.preventDefault();
            event.stopPropagation();
            lhinst.addmsgadmin(chat_id,'!cloudtalkdirect --silent --arg ' + $(this).attr('data-phone'));
        });

        $('#chat-cloudtalk-editphone-btn-right-'+chat_id).click(function(event){
            event.preventDefault();
            event.stopPropagation();
            lhc.revealModal({'url':WWW_DIR_JAVASCRIPT+'cloudtalkio/updatephoneoperator/'+chat_id});
        });
    }

    function callStarted(msg_id) {
        $.getJSON(WWW_DIR_JAVASCRIPT + 'cloudtalkio/monitorcall/' + msg_id, function(data) {
            $('#status-call-'+msg_id).html(data.content);
            if (
                data.status == 'start_sync' ||
                data.status == 'call_started' ||
                data.status == 'answered'
            ) {
                setTimeout(function() {
                    callStarted(msg_id);
                },2000);
            }
        })
    }

    function cancelCall(msg_id) {
        $.getJSON(WWW_DIR_JAVASCRIPT + 'cloudtalkio/cancelcall/' + msg_id, function(data) {
            $('#status-call-'+msg_id).html(data.content);
        })
    }

    var cloudTalkPhoneEditorLoaded = false;

    function renderPhone(phoneInput, intIcon, countryIcon)
    {
        if (phoneInput.value != '' && phoneInput.value[0] != '+') {
            phoneInput.value = "+" + phoneInput.value;
        }

        var phonenumber = new libphonenumber.AsYouType('US');
        phoneInput.value = phonenumber.input(phoneInput.value);
        var parsedNumber = libphonenumber.parsePhoneNumberFromString(phoneInput.value);

        if (parsedNumber && parsedNumber['country']) {
            intIcon.style.display = "none";
            countryIcon.src = 'https://catamphetamine.gitlab.io/country-flag-icons/3x2/' + parsedNumber['country'] + '.svg';
            countryIcon.style.display = "block";
            if (parsedNumber.isValid()) {
                phoneInput.classList.add("is-valid");
                phoneInput.classList.remove("is-invalid");
            } else {
                phoneInput.classList.add("is-invalid");
                phoneInput.classList.remove("is-valid");
            }
        } else {
            phoneInput.classList.remove("is-invalid");
            phoneInput.classList.remove("is-valid");
        }
    }

    function initEditPhone(chat_id)
    {
        var phones = document.getElementsByClassName('phone-edit-field-'+chat_id);

        for (var i = 0; i < phones.length; i++) {
            var phoneInput = phones[i].getElementsByClassName('phone-field')[0];
            var intIcon = phones[i].getElementsByClassName('PhoneInputCountryIconImg')[0];
            var countryIcon = phones[i].getElementsByClassName('img-country-svg')[0];
            renderPhone(phoneInput, intIcon, countryIcon);
            phoneInput.addEventListener('keyup', function(){
                renderPhone(phoneInput, intIcon, countryIcon);
            });
        }
    }

    function initPhoneField(chat_id, src) {
        if (cloudTalkPhoneEditorLoaded === false) {
            var th = document.getElementsByTagName('head')[0];
            var s = document.createElement('script');
            s.setAttribute('type','text/javascript');
            s.setAttribute('src',src);
            th.appendChild(s);
            s.onreadystatechange = s.onload = function() {
                initEditPhone(chat_id);
                cloudTalkPhoneEditorLoaded = true;
            };
        } else {
            initEditPhone(chat_id);
        }
    }

    ee.addListener('chatTabInfoReload', chatLoaded);
    ee.addListener('adminChatLoaded', chatLoaded);
    ee.addListener('cloudtalk.monitor_call', callStarted);
    ee.addListener('cloudtalk.cancel_call', cancelCall);
    ee.addListener('cloudtalk.init_phone_field', initPhoneField);

})();

