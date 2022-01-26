<?php

namespace LiveHelperChatExtension\cloudtalkio\providers;

class CloudTalkLiveHelperChatValidator{

    public static function validatePhone($item) {
        $definition = array(
            'phone' => new \ezcInputFormDefinitionElement(
                \ezcInputFormDefinitionElement::OPTIONAL, 'int'
            ),
            'active' => new \ezcInputFormDefinitionElement(
                \ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
            ),
            'dep_id' => new \ezcInputFormDefinitionElement(
                \ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
            )
        );

        $form = new \ezcInputForm( INPUT_POST, $definition );
        $Errors = array();

        if ( $form->hasValidData( 'phone' ) && $form->phone != '') {
            $item->phone = $form->phone;
        } else {
            $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter a phone!');
        }

        if ( $form->hasValidData( 'dep_id' )) {
            $item->dep_id = $form->dep_id;
        } else {
            $Errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please choose a department!');
        }

        if ( $form->hasValidData( 'active' ) && $form->active == true)
        {
            $item->active = 1;
        } else {
            $item->active = 0;
        }

        return $Errors;
    }

}
