<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','CloudTalk');?></h1>

<ul>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/agentsnative')?>"><i class="material-icons">support_agent</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Agents in Live Helper Chat');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/phonenumbers')?>"><i class="material-icons">add_ic_call</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Phone numbers');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/agents')?>"><i class="material-icons">support_agent</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Live Agent List');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/history')?>"><i class="material-icons">call</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Call history');?></a></li>
</ul>