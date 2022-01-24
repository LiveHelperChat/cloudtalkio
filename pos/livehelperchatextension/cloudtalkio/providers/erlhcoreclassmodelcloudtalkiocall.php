<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lhc_cloudtalkio_call";
$def->class = '\LiveHelperChatExtension\cloudtalkio\providers\erLhcoreClassModelCloudTalkIoCall';

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

foreach ([
             'cloudtalk_user_id',
             'user_id',
             'contact_id',
             'call_id',
             'chat_id',
             'status',
             'status_call',
             'contact_removed',
             'updated_at',
             'created_at',
             'answered_at',
             'date_from',
             'date_to',
             'waiting_time',
             'talking_time',
             'status_outcome',
             'wrapup_time',
             'direction',
             'msg_id',
         ] as $posAttr) {
    $def->properties[$posAttr] = new ezcPersistentObjectProperty();
    $def->properties[$posAttr]->columnName   = $posAttr;
    $def->properties[$posAttr]->propertyName = $posAttr;
    $def->properties[$posAttr]->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
}

foreach (['phone','call_uuid','recording_url'] as $posAttr) {
    $def->properties[$posAttr] = new ezcPersistentObjectProperty();
    $def->properties[$posAttr]->columnName   = $posAttr;
    $def->properties[$posAttr]->propertyName = $posAttr;
    $def->properties[$posAttr]->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
}

return $def;

?>