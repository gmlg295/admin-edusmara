<?php
$this->extend('layout/main');
$this->section('content');
?>
<div class="custom-container">
    <div class="hero-banner">
        <div class="hero-content">
            <h1 class="hero-title">
                <i class="fa-solid fa-arrow-left"></i>
                Menu Pengaturan</h1>
            <p class="hero-subtitle">Pengaturan Menu, Pengaturan Pelanggan, Pengaturan User, Pengaturan Aplikasi</p>
        </div>
    </div>

    <div id="pengaturanList">
        <div class="leaderboard-card" onclick="navigateToPage('menu', 'Pengaturan Menu')">
            <div class="student-avatar avatar-gradient-2">
                PM
            </div>
            <div class="student-info">
                <div class="student-name">Pengaturan Menu</div>
                <div class="student-class">Pengaturan Menu dan submenu setiap user aplikasi yang terdaftar</div>
            </div>
            <div class="student-stats">
                <div class="points-display">
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header-custom">
                <div style="position:relative;z-index:2;">
                    <div id="mCategoryBadge" style="margin-bottom:8px;">
                        <span class="ann-category pengumuman" style="font-size:11px;padding:4px 12px;">
                            <i class="fa-solid fa-cogs"></i>
                            Pengaturan Menu</span></div>
                    <h5 class="modal-title-custom" id="mTitle">Silahkan pilih pelanggan aplikasi di bawah ini
                    </h5>
                </div>
                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    style="position:absolute;top:20px;right:20px;z-index:3;"></button>
            </div>
            <div class="modal-body-custom">
                <div class="detail-meta-grid" id="listApp">
                    <div class="detail-meta-item">
                        <i class="fa-solid fa-tag"></i>
                        <div>
                            <div class="detail-meta-label">Kosong</div>
                            <div class="detail-meta-value" id="mCat">Tidak ada data</div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>