<?php

$Module = array( "name" => "CloudTalk" );

$ViewList = array();

$ViewList['index'] = array(
    'params' => array(),
    'functions' => array('use_admin'),
);

$ViewList['agents'] = array(
    'params' => array(),
    'functions' => array('use_admin'),
);

$ViewList['history'] = array(
    'params' => array(),
    'functions' => array('use_admin'),
);

$ViewList['agentsnative'] = array(
    'params' => array(),
    'functions' => array('use_admin'),
);

$ViewList['assignaction'] = array(
    'params' => array('id'),
    'functions' => array('use_operator'),
);

$ViewList['assignagent'] = array(
    'params' => array('id'),
    'uparams' => array('user'),
    'functions' => array('use_admin'),
);

$ViewList['monitorcall'] = array(
    'params' => array('id'),
    'functions' => array('use_operator'),
);

$ViewList['callback'] = array(
    'params' => array(),
);

$ViewList['startacall'] = array(
    'params' => array('chat_id','hash','msg_id'),
);

$ViewList['checkcallstatus'] = array(
    'params' => array('chat_id','hash','msg_id'),
);

$FunctionList['use_admin'] = array('explain' => 'Allow operator to configure CloudTalk');
$FunctionList['use_operator'] = array('explain' => 'Allow operator to use CloudTalk');
