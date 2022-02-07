<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','CloudTalk');?></h1>

<ul>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/agentsnative')?>"><span class="material-icons">support_agent</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Agents in Live Helper Chat');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/phonenumbers')?>"><span class="material-icons">add_ic_call</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Phone numbers');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/agents')?>"><span class="material-icons">support_agent</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Live Agent List');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/history')?>"><span class="material-icons">call</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call history');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('abstract/list')?>/Audit?object_id=&category=cloudtalk&source=cloudtalk&doSearch=Search"><span class="material-icons">error</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Error log');?></a></li>
</ul>