<?php

use oat\tao\helpers\Template;

?>
<link rel="stylesheet" href="<?= Template::css('css-sdk.css') ?>" />

<div class="main-container flex-container-main-form css-sdk">
    <h1><?= __('CSS Development Kit')?></h1>

    <a class="btn-info small" target="dwl" href="https://github.com/oat-sa/tao-css-sdk/archive/master.zip">
        <span class="icon-download"></span>
        <?= __('Download CSS Development Kit')?>
    </a>
    <iframe name="dwl" class="viewport-hidden"></iframe>

    <div class="form-content">
        <div class="xhtml_form">
            <h2><?= __('Upload and apply the finished CSS file')?></h2>
            <div id="css-container" data-url="<?=_url('apply', 'CssManager');?>"></div>
        </div>
    </div>
</div>
