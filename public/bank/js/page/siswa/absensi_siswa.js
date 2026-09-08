
(function() {
    'use strict';

    // ============ STATE ============
    const STORAGE_KEY = 'smantique_absensi';
    let attendanceHistory = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    let currentMode = 'qrcode';
    let qrSession = null;
    let qrTimerInterval = null;
    let qrTimeLeft = 0;
    let manualSession = null;

    // Demo students data per kelas
    const demoStudents = {
        '10': [
            { id: 1, nama: 'Ahmad Fauzi', nisn: '0051234567', jk: 'L' },
            { id: 2, nama: 'Siti Nurhaliza', nisn: '0052345678', jk: 'P' },
            { id: 3, nama: 'Budi Santoso', nisn: '0053456789', jk: 'L' },
            { id: 4, nama: 'Dewi Anggraini', nisn: '0054567890', jk: 'P' },
            { id: 5, nama: 'Rizky Pratama', nisn: '0055678901', jk: 'L' },
            { id: 6, nama: 'Lestari Wulandari', nisn: '0056789012', jk: 'P' },
            { id: 7, nama: 'Muhammad Iqbal', nisn: '0057890123', jk: 'L' },
            { id: 8, nama: 'Putri Ayu', nisn: '0058901234', jk: 'P' },
            { id: 9, nama: 'Andi Saputra', nisn: '0059012345', jk: 'L' },
            { id: 10, nama: 'Rina Marlina', nisn: '0050123456', jk: 'P' }
        ],
        '11': [
            { id: 11, nama: 'Fajar Nugroho', nisn: '0041234567', jk: 'L' },
            { id: 12, nama: 'Intan Permatasari', nisn: '0042345678', jk: 'P' },
            { id: 13, nama: 'Doni Prasetyo', nisn: '0043456789', jk: 'L' },
            { id: 14, nama: 'Maya Sari', nisn: '0044567890', jk: 'P' },
            { id: 15, nama: 'Yoga Aditya', nisn: '0045678901', jk: 'L' }
        ],
        '12': [
            { id: 16, nama: 'Aisyah Putri', nisn: '0031234567', jk: 'P' },
            { id: 17, nama: 'Rendi Kurniawan', nisn: '0032345678', jk: 'L' },
            { id: 18, nama: 'Nabila Zahra', nisn: '0033456789', jk: 'P' },
            { id: 19, nama: 'Hendra Wijaya', nisn: '0034567890', jk: 'L' },
            { id: 20, nama: 'Fitri Handayani', nisn: '0035678901', jk: 'P' }
        ]
    };

    // Seed history demo
    if (attendanceHistory.length === 0) {
        attendanceHistory = [
            {
                id: 1, mode: 'qrcode', kelas: '10', namaKelas: '10.I', mapel: 'Matematika',
                materi: 'Persamaan Kuadrat', tanggal: '2026-08-15', jam: '07:30',
                hadir: 28, izin: 1, sakit: 1, alpa: 0, total: 30
            },
            {
                id: 2, mode: 'manual', kelas: '11', namaKelas: '11.I', mapel: 'Fisika',
                materi: 'Hukum Newton', tanggal: '2026-08-14', jam: '09:00',
                hadir: 25, izin: 2, sakit: 1, alpa: 2, total: 30
            }
        ];
        saveHistory();
    }

    // ============ INIT ============
    document.addEventListener('DOMContentLoaded', () => {
        initModeTabs();
        initDynamicRombel();
        setDefaultTime();
        renderHistory();
    });

    // ============ MODE TABS ============
    function initModeTabs() {
        document.querySelectorAll('.mode-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                currentMode = this.dataset.mode;
                document.querySelectorAll('.mode-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.mode-content').forEach(c => c.classList.remove('active'));
                document.getElementById(`content-${currentMode}`).classList.add('active');

                if (currentMode === 'history') renderHistory();
            });
        });
    }

    // ============ DYNAMIC ROMBEL ============
    function initDynamicRombel() {
        ['qr', 'manual'].forEach(prefix => {
            document.getElementById(`${prefix}Kelas`).addEventListener('change', function() {
                const select = document.getElementById(`${prefix}NamaKelas`);
                select.innerHTML = '<option value="">Pilih Rombel</option>';
                if (this.value) {
                    for (let i = 1; i <= 5; i++) {
                        const romawi = ['I', 'II', 'III', 'IV', 'V'][i-1];
                        const option = document.createElement('option');
                        option.value = `${this.value}.${romawi}`;
                        option.textContent = `${this.value}.${romawi}`;
                        select.appendChild(option);
                    }
                }
            });
        });
    }

    function setDefaultTime() {
        const now = new Date();
        const timeStr = now.toTimeString().substring(0, 5);
        document.getElementById('qrJamMulai').value = timeStr;
    }

    // ============ QR CODE SESSION ============
    window.startQRSession = function() {
        const kelas = document.getElementById('qrKelas').value;
        const namaKelas = document.getElementById('qrNamaKelas').value;
        const mapel = document.getElementById('qrMapel').value;
        const materi = document.getElementById('qrMateri').value.trim();
        const durasi = parseInt(document.getElementById('qrDurasi').value);
        const jamMulai = document.getElementById('qrJamMulai').value;

        if (!kelas || !namaKelas || !mapel || !materi) {
            showToast('error', 'Form Belum Lengkap', 'Harap isi semua field yang wajib');
            return;
        }

        const sessionCode = generateSessionCode();
        qrSession = {
            code: sessionCode,
            kelas, namaKelas, mapel, materi, durasi, jamMulai,
            tanggal: new Date().toISOString().split('T')[0],
            attendees: [],
            totalStudents: demoStudents[kelas] ? demoStudents[kelas].length : 30
        };

        // Update UI
        document.getElementById('qrSetupCard').classList.add('hidden');
        document.getElementById('qrActiveSession').classList.remove('hidden');
        document.getElementById('sessionKelas').textContent = `${namaKelas} • Kelas ${kelas}`;
        document.getElementById('sessionMapel').textContent = mapel;
        document.getElementById('sessionMateri').textContent = materi;
        document.getElementById('sessionCode').textContent = sessionCode;
        document.getElementById('countTotalQR').textContent = qrSession.totalStudents;

        // Generate QR
        generateQR(sessionCode);

        // Start timer
        qrTimeLeft = durasi * 60;
        updateTimerDisplay();
        qrTimerInterval = setInterval(() => {
            qrTimeLeft--;
            updateTimerDisplay();
            if (qrTimeLeft <= 0) {
                clearInterval(qrTimerInterval);
                endQRSession(true);
            }
        }, 1000);

        showToast('success', 'Sesi Dimulai!', `Absensi QR untuk ${namaKelas} aktif selama ${durasi} menit`);
    };

    function generateQR(sessionCode) {
        const qrData = JSON.stringify({
            app: 'SMANTIQUE',
            type: 'absensi',
            session: sessionCode,
            timestamp: Date.now()
        });
        const encoded = encodeURIComponent(qrData);
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=${encoded}`;
        document.getElementById('qrCodeImage').src = qrUrl;
    }

    window.refreshQR = function() {
        if (!qrSession) return;
        qrSession.code = generateSessionCode();
        generateQR(qrSession.code);
        document.getElementById('sessionCode').textContent = qrSession.code;
        showToast('info', 'QR Diperbarui', 'Kode QR baru telah dibuat');
    };

    function generateSessionCode() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        let code = 'SMN-';
        for (let i = 0; i < 6; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return code;
    }

    function updateTimerDisplay() {
        const minutes = Math.floor(qrTimeLeft / 60);
        const seconds = qrTimeLeft % 60;
        const display = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        document.getElementById('qrTimer').textContent = display;
        
        // Warning color when < 2 minutes
        const timerEl = document.getElementById('qrTimer');
        if (qrTimeLeft < 120) {
            timerEl.style.color = 'var(--danger)';
        } else {
            timerEl.style.color = 'var(--text-primary)';
        }
    }

    window.simulateScan = function() {
        if (!qrSession) return;
        
        const students = demoStudents[qrSession.kelas] || [];
        const available = students.filter(s => !qrSession.attendees.find(a => a.id === s.id));
        
        if (available.length === 0) {
            showToast('warning', 'Semua Sudah Absen', 'Tidak ada siswa tersisa untuk simulasi');
            return;
        }

        const randomStudent = available[Math.floor(Math.random() * available.length)];
        const now = new Date();
        const timeStr = now.toTimeString().substring(0, 5);

        qrSession.attendees.push({
            ...randomStudent,
            waktu: timeStr
        });

        updateQRCounters();
        showToast('success', 'Absen Masuk!', `${randomStudent.nama} berhasil absen via QR`);
    };

    function updateQRCounters() {
        const count = qrSession.attendees.length;
        const total = qrSession.totalStudents;
        const percent = Math.round((count / total) * 100);

        document.getElementById('countHadirQR').textContent = count;
        document.getElementById('progressPercent').textContent = percent + '%';
        document.getElementById('progressBar').style.width = percent + '%';
        document.getElementById('lastUpdate').textContent = 'Update: ' + new Date().toTimeString().substring(0, 8);

        // Render attendee list
        const listEl = document.getElementById('attendeeList');
        if (qrSession.attendees.length === 0) {
            listEl.innerHTML = '<div class="empty-state" style="padding:20px;"><i class="fa-solid fa-user-clock" style="font-size:32px;"></i><p>Belum ada siswa yang absen</p></div>';
        } else {
            listEl.innerHTML = qrSession.attendees.map(s => {
                const initials = s.nama.split(' ').map(n => n[0]).join('').substring(0, 2);
                return `
                    <div class="attendee-item">
                        <div class="attendee-avatar">${initials}</div>
                        <div class="attendee-name">${s.nama}</div>
                        <div class="attendee-time">${s.waktu}</div>
                        <i class="fa-solid fa-circle-check attendee-check"></i>
                    </div>
                `;
            }).join('');
        }
    }

    window.endQRSession = function(auto = false) {
        if (!qrSession) return;
        
        clearInterval(qrTimerInterval);
        
        const record = {
            id: Date.now(),
            mode: 'qrcode',
            kelas: qrSession.kelas,
            namaKelas: qrSession.namaKelas,
            mapel: qrSession.mapel,
            materi: qrSession.materi,
            tanggal: qrSession.tanggal,
            jam: qrSession.jamMulai,
            hadir: qrSession.attendees.length,
            izin: 0,
            sakit: 0,
            alpa: qrSession.totalStudents - qrSession.attendees.length,
            total: qrSession.totalStudents
        };

        attendanceHistory.unshift(record);
        saveHistory();

        // Reset UI
        document.getElementById('qrSetupCard').classList.remove('hidden');
        document.getElementById('qrActiveSession').classList.add('hidden');
        qrSession = null;

        if (auto) {
            showToast('warning', 'Waktu Habis!', 'Sesi absensi QR telah berakhir otomatis');
        } else {
            showToast('success', 'Sesi Selesai', `Absensi tersimpan: ${record.hadir} hadir, ${record.alpa} alpa`);
        }
    };

    // ============ MANUAL SESSION ============
    window.startManualSession = function() {
        const kelas = document.getElementById('manualKelas').value;
        const namaKelas = document.getElementById('manualNamaKelas').value;
        const mapel = document.getElementById('manualMapel').value;
        const materi = document.getElementById('manualMateri').value.trim();

        if (!kelas || !namaKelas || !mapel || !materi) {
            showToast('error', 'Form Belum Lengkap', 'Harap isi semua field yang wajib');
            return;
        }

        manualSession = {
            kelas, namaKelas, mapel, materi,
            tanggal: new Date().toISOString().split('T')[0],
            jam: new Date().toTimeString().substring(0, 5),
            students: (demoStudents[kelas] || []).map(s => ({ ...s, status: null }))
        };

        document.getElementById('manualSetupCard').classList.add('hidden');
        document.getElementById('manualActiveSession').classList.remove('hidden');
        document.getElementById('manualSessionInfo').textContent = `${namaKelas} • ${mapel}`;
        document.getElementById('manualSessionMateri').textContent = `Materi: ${materi}`;

        renderManualList();
        showToast('success', 'Absensi Manual Dimulai', `Silakan absen ${manualSession.students.length} siswa ${namaKelas}`);
    };

    function renderManualList(filter = '') {
        const listEl = document.getElementById('manualStudentList');
        const filtered = manualSession.students.filter(s => 
            s.nama.toLowerCase().includes(filter.toLowerCase()) ||
            s.nisn.includes(filter)
        );

        if (filtered.length === 0) {
            listEl.innerHTML = '<div class="empty-state"><i class="fa-solid fa-user-slash"></i><h4>Tidak Ditemukan</h4><p>Tidak ada siswa yang cocok</p></div>';
            return;
        }

        listEl.innerHTML = filtered.map((student, idx) => {
            const initials = student.nama.split(' ').map(n => n[0]).join('').substring(0, 2);
            const avatarClass = student.jk === 'L' ? 'avatar-m' : 'avatar-f';
            return `
                <div class="attendance-item" data-id="${student.id}">
                    <div class="student-num">${idx + 1}</div>
                    <div class="student-avatar-mini ${avatarClass}">${initials}</div>
                    <div class="student-detail">
                        <div class="student-name-text">${student.nama}</div>
                        <div class="student-nisn-text">NISN: ${student.nisn}</div>
                    </div>
                    <div class="status-buttons">
                        <button class="status-btn hadir ${student.status === 'H' ? 'active' : ''}" onclick="setStatus(${student.id}, 'H', this)" title="Hadir">H</button>
                        <button class="status-btn izin ${student.status === 'I' ? 'active' : ''}" onclick="setStatus(${student.id}, 'I', this)" title="Izin">I</button>
                        <button class="status-btn sakit ${student.status === 'S' ? 'active' : ''}" onclick="setStatus(${student.id}, 'S', this)" title="Sakit">S</button>
                        <button class="status-btn alpa ${student.status === 'A' ? 'active' : ''}" onclick="setStatus(${student.id}, 'A', this)" title="Alpa">A</button>
                    </div>
                </div>
            `;
        }).join('');
    }

    window.setStatus = function(studentId, status, btn) {
        const student = manualSession.students.find(s => s.id === studentId);
        if (!student) return;

        student.status = status;

        // Update button UI
        const container = btn.parentElement;
        container.querySelectorAll('.status-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        updateManualCounters();
    };

    window.markAllHadir = function() {
        manualSession.students.forEach(s => s.status = 'H');
        renderManualList(document.getElementById('searchStudent').value);
        updateManualCounters();
        showToast('success', 'Semua Hadir', 'Semua siswa ditandai hadir');
    };

    function updateManualCounters() {
        const counts = { H: 0, I: 0, S: 0, A: 0 };
        manualSession.students.forEach(s => {
            if (s.status) counts[s.status]++;
        });

        document.getElementById('countH').textContent = counts.H;
        document.getElementById('countI').textContent = counts.I;
        document.getElementById('countS').textContent = counts.S;
        document.getElementById('countA').textContent = counts.A;

        const marked = counts.H + counts.I + counts.S + counts.A;
        const total = manualSession.students.length;
        const percent = Math.round((marked / total) * 100);

        document.getElementById('manualProgress').textContent = `${marked}/${total}`;
        document.getElementById('manualProgressBar').style.width = percent + '%';
    }

    window.saveManualAbsensi = function() {
        const counts = { H: 0, I: 0, S: 0, A: 0 };
        manualSession.students.forEach(s => {
            if (s.status) counts[s.status]++;
        });

        const marked = counts.H + counts.I + counts.S + counts.A;
        if (marked < manualSession.students.length) {
            showToast('warning', 'Belum Lengkap', `${manualSession.students.length - marked} siswa belum diabsen`);
            return;
        }

        const record = {
            id: Date.now(),
            mode: 'manual',
            kelas: manualSession.kelas,
            namaKelas: manualSession.namaKelas,
            mapel: manualSession.mapel,
            materi: manualSession.materi,
            tanggal: manualSession.tanggal,
            jam: manualSession.jam,
            hadir: counts.H,
            izin: counts.I,
            sakit: counts.S,
            alpa: counts.A,
            total: manualSession.students.length
        };

        attendanceHistory.unshift(record);
        saveHistory();
        cancelManualSession();
        showToast('success', 'Absensi Tersimpan!', `H: ${counts.H}, I: ${counts.I}, S: ${counts.S}, A: ${counts.A}`);
    };

    window.cancelManualSession = function() {
        manualSession = null;
        document.getElementById('manualSetupCard').classList.remove('hidden');
        document.getElementById('manualActiveSession').classList.add('hidden');
    };

    // Search manual
    document.getElementById('searchStudent').addEventListener('input', function() {
        if (manualSession) renderManualList(this.value);
    });

    // ============ HISTORY ============
    function renderHistory() {
        const listEl = document.getElementById('historyList');
        const filterKelas = document.getElementById('filterHistoryKelas').value;
        const filterMapel = document.getElementById('filterHistoryMapel').value;
        const filterDate = document.getElementById('filterHistoryDate').value;

        let filtered = attendanceHistory.filter(item => {
            const matchKelas = filterKelas === 'all' || item.kelas === filterKelas;
            const matchMapel = filterMapel === 'all' || item.mapel === filterMapel;
            const matchDate = !filterDate || item.tanggal === filterDate;
            return matchKelas && matchMapel && matchDate;
        });

        if (filtered.length === 0) {
            listEl.innerHTML = '<div class="empty-state"><i class="fa-solid fa-inbox"></i><h4>Belum Ada Riwayat</h4><p>Riwayat absensi akan muncul di sini</p></div>';
            return;
        }

        listEl.innerHTML = filtered.map(item => {
            const date = new Date(item.tanggal);
            const day = date.getDate();
            const month = date.toLocaleDateString('id-ID', { month: 'short' });
            const modeIcon = item.mode === 'qrcode' ? 'fa-qrcode' : 'fa-clipboard-user';
            const modeLabel = item.mode === 'qrcode' ? 'QR Code' : 'Manual';

            return `
                <div class="history-item" onclick="showToast('info', 'Detail Absensi', '${item.mapel} - ${item.namaKelas}')">
                    <div class="history-date-box">
                        <div class="day">${day}</div>
                        <div class="month">${month}</div>
                    </div>
                    <div class="history-info">
                        <div class="history-title">${item.mapel} - ${item.namaKelas}</div>
                        <div class="history-meta">
                            <span><i class="fa-solid ${modeIcon}"></i> ${modeLabel}</span>
                            <span><i class="fa-regular fa-clock"></i> ${item.jam}</span>
                            <span><i class="fa-solid fa-book"></i> ${item.materi}</span>
                        </div>
                    </div>
                    <div class="history-stats">
                        <span class="history-stat hadir">H: ${item.hadir}</span>
                        <span class="history-stat izin">I: ${item.izin}</span>
                        <span class="history-stat sakit">S: ${item.sakit}</span>
                        <span class="history-stat alpa">A: ${item.alpa}</span>
                    </div>
                </div>
            `;
        }).join('');
    }

    document.getElementById('filterHistoryKelas').addEventListener('change', renderHistory);
    document.getElementById('filterHistoryMapel').addEventListener('change', renderHistory);
    document.getElementById('filterHistoryDate').addEventListener('change', renderHistory);

    // ============ HELPERS ============
    function saveHistory() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(attendanceHistory));
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