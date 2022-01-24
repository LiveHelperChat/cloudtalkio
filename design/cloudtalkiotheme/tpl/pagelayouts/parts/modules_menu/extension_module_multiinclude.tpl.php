<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhcloudtalkio','use_admin')) : ?>
<li class="nav-item"><a class="nav-link" href="<?php echo erLhcoreClassDesign::baseurl('cloudtalkio/index')?>"><i class="material-icons">phone_callback</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('cloudtalkio/admin','CloudTalk');?></a></li>
<?php endif; ?>