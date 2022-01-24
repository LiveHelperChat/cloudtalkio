<h6 class="text-center"><?php echo htmlspecialchars($msg['name_support'])?></h6>
<p class="text-center mb-2"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','Start a call with an agent')?></p>
<p class="text-center text-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','No charges or software to install')?></p>
<div>
    <?php include(erLhcoreClassDesign::designtpl('lhgenericbot/message/content/status_call.tpl.php'));?>
</div>