<?php

use oat\tao\helpers\Template;

?>
<link rel="stylesheet" href="<?= Template::css('css-sdk.css') ?>" />


<div class="main-container flex-container-main-form">
    <h1><?= __('Base64 Converter')?></h1>


    <div class="form-content">
        <div class="xhtml_form">
            <div id="base64-container" data-url="<?=_url('convert', 'Base64Converter');?>"></div>
        </div>
    </div>


</div>
<div class="data-container-wrapper flex-container-remaining base-64-converter">
    <h1><?= __('Base64 Data URI')?></h1>
    <textarea id="base64-code"></textarea>
</div>