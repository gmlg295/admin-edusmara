(function() {
    'use strict';

    let sidebar = null;

    // ============ INIT ============
    document.addEventListener('DOMContentLoaded', () => {
        initSidebar();
        initHeaderActions();
        initStatsCounter();
        initGreeting();
        updateDate();
        animateBars();
    });

    // ============ SIDEBAR (Auto-Close) ============
    function initSidebar() {
        const sidebarEl = document.getElementById('sidebarMenu');
        sidebar = new bootstrap.Offcanvas(sidebarEl, {
            backdrop: true,
            keyboard: true,
            scroll: false
        });

        // Toggle button
        document.getElementById('btnMenu').addEventListener('click', (e) => {
            e.preventDefault();
            sidebar.show();
        });

        // Menu links - auto close after click
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', function() {
                const menu = this.dataset.menu;
                const label = this.querySelector('span').textContent;
                
                // Update active state
                document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Navigate (demo)
                navigateTo(menu, label);
                
                // Auto close sidebar
                setTimeout(() => sidebar.hide(), 300);
            });
        });

        // Logout
        document.getElementById('btnLogout').addEventListener('click', () => {
            if (confirm('Yakin ingin keluar dari Dashboard Admin?')) {
                showToast('warning', 'Logout', 'Anda telah keluar dari sistem');
                setTimeout(() => window.location.href = '/auth/logout', 500);
            }
        });

        // Close on outside click (handled by Bootstrap backdrop)
    }

    // ============ HEADER ACTIONS ============
    function initHeaderActions() {
        document.getElementById('btnSearch').addEventListener('click', () => {
            showToast('info', 'Pencarian', 'Fitur pencarian global akan segera hadir');
        });

        document.getElementById('btnNotif').addEventListener('click', function() {
            const badge = this.querySelector('.badge-dot');
            if (badge) badge.remove();
            showToast('success', 'Notifikasi', 'Semua notifikasi telah dibaca');
        });

        document.getElementById('btnProfile').addEventListener('click', () => {
            showToast('info', 'Profil Admin', 'Administrator - admin@smantig.sch.id');
        });
    }

    // ============ NAVIGATE ============
    window.navigateTo = function(page, label) {
        const pageNames = {
            'dashboard': 'Dashboard',
            'siswa': 'Data Siswa',
            'prestasi': 'Data Prestasi',
            'agenda': 'Agenda Sekolah',
            'pengumuman': 'Pengumuman',
            'nilai': 'Nilai & Raport',
            'absensi': 'Absensi',
            'kelas': 'Data Kelas',
            'laporan': 'Laporan',
            'pengaturan': 'Pengaturan'
        };

        const title = label || pageNames[page] || page;
        showToast('info', 'Navigasi', `Menuju halaman ${title}`);

        setTimeout(() => {
            window.location.href = `/${page}`;
        }, 500);
        
        // Di production, gunakan: window.location.href = `/admin/${page}`;
    };

    // ============ STATS COUNTER ============
    function initStatsCounter() {
        const stats = document.querySelectorAll('.stat-value[data-count]');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        });
        stats.forEach(stat => observer.observe(stat));
    }

    function animateCounter(el) {
        const target = parseInt(el.dataset.count);
        const duration = 1500;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            el.textContent = Math.floor(current).toLocaleString('id-ID');
        }, 16);
    }

    // ============ GREETING ============
    function initGreeting() {
        const hour = new Date().getHours();
        let greeting = '';
        let emoji = '';

        if (hour >= 5 && hour < 11) { greeting = 'Selamat Pagi'; emoji = '☀️'; }
        else if (hour >= 11 && hour < 15) { greeting = 'Selamat Siang'; emoji = '🌤️'; }
        else if (hour >= 15 && hour < 18) { greeting = 'Selamat Sore'; emoji = '🌅'; }
        else { greeting = 'Selamat Malam'; emoji = '🌙'; }

        document.getElementById('greetingText').textContent = `${greeting}, Admin! ${emoji}`;
    }

    function updateDate() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
    }

    // ============ ANIMATE BARS ============
    function animateBars() {
        setTimeout(() => {
            document.querySelectorAll('.bar-fill').forEach(bar => {
                const height = bar.style.height;
                bar.style.height = '0%';
                setTimeout(() => {
                    bar.style.height = height;
                }, 100);
            });
        }, 300);
    }

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

})();