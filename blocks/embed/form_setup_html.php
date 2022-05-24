<?php
defined('C5_EXECUTE') or die("Access Denied.");
/** @var \Concrete\Core\Form\Service\Form $form */
$source = isset($source) ? $source : '';
?>
<div class="row">
    <div class="form-group row py-sm-3 mb-0">
        <?= $form->label('url', t('URL')); ?>
        <?= $form->url('source', $source); ?>
    </div>
    <div class="form-group">
        <?= $form->label('page_selector', t('Choose Page:')); ?>
        <?= $form->button('page_selector', 'Sitemap',array(),'ccm-item-selector btn-secondary'); ?>
    </div>
</div>

<script>

    $(".ccm-item-selector").click(function(){

        jQuery.fn.dialog.open({
            width: '90%',
            height: '70%',
            modal: false,
            title: ccmi18n_sitemap.choosePage,
            href: CCM_DISPATCHER_FILENAME + '/ccm/system/dialogs/page/sitemap_selector'
        });
        ConcreteEvent.unsubscribe('SitemapSelectPage');
        ConcreteEvent.subscribe('SitemapSelectPage', function(e, data) {
            jQuery.fn.dialog.closeTop();
            $("#source").val(`${CCM_APPLICATION_URL}/index.php?cID=${data.cID}`);
        });
    });

</script>
