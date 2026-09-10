
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="<?= base_url('public/bank/js/scripts/DynamicFetch.js') ?>"></script>
<?php if (isset($load_js)): ?>
    <?php 
    $jsFiles = is_array($load_js) ? $load_js : [$load_js];
    
    foreach ($jsFiles as $jsFile): 
    ?>
        <script type="text/javascript" src="<?= base_url('public/bank/js/' . $jsFile) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<script type="text/javascript">
    const baseUrl = '<?= base_url() ?>';
     // ============ TOAST ============
    window.showToast = function(type, title, msg) {
        const container = document.getElementById('toastContainer');
        const iconMap = {
            'success': 'fa-circle-check',
            'info': 'fa-circle-info',
            'warning': 'fa-triangle-exclamation',
            'error': 'fa-circle-xmark'
        };

        const toast = document.createElement('div');
        toast.className = `toast-custom ${type}`;
        toast.innerHTML = `
            <i class="fa-solid ${iconMap[type] || iconMap.info}"></i>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-msg">${msg}</div>
            </div>
            <button class="toast-close"><i class="fa-solid fa-xmark"></i></button>
        `;
        container.appendChild(toast);
        toast.querySelector('.toast-close').addEventListener('click', () => removeToast(toast));
        setTimeout(() => removeToast(toast), 3500);
    };

    function removeToast(toast) {
        if (!toast || !toast.parentNode) return;
        toast.classList.add('hiding');
        setTimeout(() => toast.remove(), 300);
    }
</script>

</body>
</html>