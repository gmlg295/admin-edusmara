
<?= $this->include('layout/head') ?>
<div id="<?= csrf_token() ?>" style="display:none;"><?= csrf_hash() ?></div>
<?php
if (isset($enable_menu)) {
    if ($enable_menu) {
        echo $this->include('layout/side');
    }
}else {
    echo $this->include('layout/side');
}


?>

<div id="loaderPreloader"></div>
<?= $this->renderSection('content') ?>
<div class="panel-space"></div>

<!-- ============ TOAST CONTAINER ============ -->
<div class="toast-container" id="toastContainer"></div>

<!-- ============ SPINNER ============ -->
<div class="spinner-overlay hidden" id="spinnerOverlay">
    <div class="spinner-sm"></div>
</div>
<?= $this->include('layout/js') ?>

