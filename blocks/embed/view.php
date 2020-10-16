<?php defined('C5_EXECUTE') or die("Access Denied.");
/** @var \Embed\Adapters\Adapter $info */
$url = isset($source) ? $source : null;
if(isset($info)){
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="media">
            <div class="media-left">
                <a href="<?= h($info->getUrl()) ?>" style="width: 200px; display: block">
                    <img alt="" class="media-object" src="<?= h($info->getImage()) ?>"
                         width="<?= h($info->getImageWidth()) ?>" height="<?= h($info->getImageHeight()) ?>">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading"><?= h($info->getTitle()) ?></h4>
                <p><?= h($info->getDescription()) ?></p>
                <p><a href="<?= h($info->getUrl()) ?>"><?= h($info->getUrl()) ?></a></p>
            </div>
        </div>
    </div>
</div>
<?php
    }else{?>
    <a href="<?= h($url) ?>" style="width: 200px; display: block"><?= $url;?></a>
<?php
}
?>