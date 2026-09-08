
(function() {
    'use strict';

    // ============ DATA STORAGE ============
    const STORAGE_KEY = 'smantique_siswa';
    let studentsData = []; //JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    let currentKelas = '10';
    let currentStudentId = null;
    let detailModal, editModal, transferModal, deleteModal;

    const ApiData  =  new MyFetch('/siswa/getData', { method: 'GET'});
    async function loadDataFromServer() {
        try {
            const result = await ApiData.fetchData();
                if(result.status){
                    studentsData = result.data;
                    setTimeout(() => {
                        updateAllCounts();
                        renderList('10');
                    }, 500);

                };
                if (!result.status) throw new Error(`Device log gagal: ${result.status}`);
            
        } catch (error) {
            
            console.error(error);
        }
    }   

    // ============ INIT MODALS ============
    document.addEventListener('DOMContentLoaded', () => {
        detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        editModal = new bootstrap.Modal(document.getElementById('editModal'));
        transferModal = new bootstrap.Modal(document.getElementById('transferModal'));
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        loadDataFromServer();
        updateAllCounts();
        renderList('10');
        document.getElementById('inputTglMasuk').value = new Date().toISOString().split('T')[0];
        document.getElementById('transferDate').value = new Date().toISOString().split('T')[0];

        // Toggle alasan lainnya
        document.getElementById('transferReason').addEventListener('change', function() {
            const otherGroup = document.getElementById('otherReasonGroup');
            otherGroup.style.display = this.value === 'Lainnya' ? 'block' : 'none';
        });

        // Dynamic rombel for edit modal
        document.getElementById('editKelas').addEventListener('change', function() {
            populateRombelOptions(document.getElementById('editNamaKelas'), this.value);
        });
    });

    // ============ TAB SWITCHING ============
    document.querySelectorAll('.class-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            currentKelas = this.dataset.kelas;
            document.querySelectorAll('.class-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.class-content').forEach(c => c.classList.remove('active'));
            document.getElementById(`content-${currentKelas}`).classList.add('active');
            renderList(currentKelas);
        });
    });

    window.switchToForm = function() {
        document.querySelectorAll('.class-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.class-content').forEach(c => c.classList.remove('active'));
        document.getElementById('content-form').classList.add('active');
        showToast('info', 'Mode Input', 'Silakan isi form tambah siswa baru');
    };

    // ============ DYNAMIC ROMBEL ============
    document.getElementById('inputKelas').addEventListener('change', function() {
        populateRombelOptions(document.getElementById('inputNamaKelas'), this.value);
    });

    function populateRombelOptions(select, kelas) {
        select.innerHTML = '<option value="">Pilih Rombel</option>';
        if (kelas) {
            for (let i = 1; i <= 5; i++) {
                const romawi = ['I', 'II', 'III', 'IV', 'V'][i-1];
                const option = document.createElement('option');
                option.value = `${kelas}.${romawi}`;
                option.textContent = `${kelas}.${romawi}`;
                select.appendChild(option);
            }
        }
    }

    // ============ FORM SUBMIT (Tambah Siswa) ============
    document.getElementById('studentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const newStudent = {
            id: Date.now(),
            nama_lengkap: document.getElementById('inputNama').value.trim(),
            nisn: document.getElementById('inputNisn').value.trim(),
            jenis_kelamin: document.getElementById('inputGender').value,
            tanggal_lahir: document.getElementById('inputTglLahir').value,
            alamat_lengkap: document.getElementById('inputAlamat').value.trim(),
            nomor_hp: document.getElementById('inputHp').value.trim(),
            kelas: document.getElementById('inputKelas').value,
            nama_kelas: document.getElementById('inputNamaKelas').value,
            diterima_dari_kelas: document.getElementById('inputDiterimaDari').value,
            tanggal_masuk: document.getElementById('inputTglMasuk').value,
            status: 'aktif',
            wali: {
                nama: document.getElementById('inputNamaWali').value.trim(),
                alamat: document.getElementById('inputAlamatWali').value.trim(),
                nomor_hp: document.getElementById('inputHpWali').value.trim(),
                hubungan: document.getElementById('inputHubungan').value
            },
            createdAt: new Date().toISOString()
        };

        if (studentsData.some(s => s.nisn === newStudent.nisn)) {
            showToast('error', 'NISN Duplikat', 'NISN sudah terdaftar di sistem');
            return;
        }

        studentsData.push(newStudent);
        saveData();
        resetStudentForm();
        updateAllCounts();
        
        currentKelas = newStudent.kelas;
        document.querySelectorAll('.class-tab').forEach(t => {
            t.classList.toggle('active', t.dataset.kelas === currentKelas);
        });
        document.querySelectorAll('.class-content').forEach(c => c.classList.remove('active'));
        document.getElementById(`content-${currentKelas}`).classList.add('active');
        renderList(currentKelas);

        showToast('success', 'Siswa Tersimpan!', `${newStudent.nama} berhasil ditambahkan ke ${newStudent.nama_kelas}`);
    });

    window.resetStudentForm = function() {
        document.getElementById('studentForm').reset();
        document.getElementById('inputNamaKelas').innerHTML = '<option value="">Pilih Rombel</option>';
    };

    // ============ RENDER LIST ============
    window.renderList = function(kelas) {
        const list = document.getElementById(`list${kelas}`);
        const searchTerm = document.getElementById(`search${kelas}`).value.toLowerCase().trim();
        const filterGender = document.getElementById(`filterGender${kelas}`).value;
        const filterRombel = document.getElementById(`filterRombel${kelas}`).value;

        let filtered = studentsData.filter(s => {
            const matchKelas = s.kelas == kelas;
            const matchSearch = !searchTerm || 
                s.nama.toLowerCase().includes(searchTerm) || 
                s.nisn.includes(searchTerm);
            const matchGender = filterGender === 'all' || s.jenis_kelamin === filterGender;
            const matchRombel = filterRombel === 'all' || s.nama_kelas === filterRombel;
            return matchKelas && matchSearch && matchGender && matchRombel;
        });
        
        console.log('Filtered:', filtered);
        if (filtered.length === 0) {
            list.innerHTML = `
                <div class="empty-state">
                    <i class="fa-solid fa-user-slash"></i>
                    <h4>Tidak Ada Data</h4>
                    <p>${studentsData.filter(s => s.kelas === kelas).length === 0 ? 'Belum ada siswa di kelas ini. Klik "Tambah Siswa" untuk menambahkan.' : 'Tidak ada siswa yang cocok dengan filter'}</p>
                </div>
            `;
            return;
        }

        list.innerHTML = filtered.map((student, index) => {
            const initials = student.nama_lengkap.split(' ').map(n => n[0]).join('').substring(0, 2);
            const avatarClass = student.jenis_kelamin === 'L' ? 'avatar-male' : 'avatar-female';
            const genderIcon = student.jenis_kelamin === 'L' ? '👨' : '👩';
            const isNew = isStudentNew(student.tanggal_masuk);
            const classBadge = `class-${student.kelas}`;
            const isInactive = student.status !== 'aktif';

            return `
                <div class="student-card ${isInactive ? 'inactive' : ''}" style="animation-delay:${index * 0.05}s" onclick="showDetail(${student.id})">
                    <div class="student-avatar ${avatarClass}">
                        ${initials}
                        <div class="gender-badge">${genderIcon}</div>
                    </div>
                    <div class="student-info">
                        <div class="student-name">${student.nama_lengkap}</div>
                        <div class="student-nisn">NISN: ${student.nisn}</div>
                        <div class="student-meta">
                            <span class="meta-badge ${classBadge}">
                                <i class="fa-solid fa-chalkboard"></i> ${student.nama_kelas}
                            </span>
                            ${isInactive ? 
                                `<span class="meta-badge status-pindah"><i class="fa-solid fa-right-from-bracket"></i> ${student.status === 'pindah' ? 'Pindah' : student.status}</span>` :
                                `<span class="meta-badge status-aktif"><i class="fa-solid fa-circle-check"></i> Aktif</span>`
                            }
                            ${isNew && !isInactive ? '<span class="meta-badge new-student"><i class="fa-solid fa-star"></i> Siswa Baru</span>' : ''}
                        </div>
                    </div>
                    <div class="student-actions" onclick="event.stopPropagation()">
                        <button class="action-btn view" onclick="showDetail(${student.id})" title="Lihat Detail">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        ${!isInactive ? `
                        <button class="action-btn call" onclick="callWali(${student.id})" title="Hubungi Wali">
                            <i class="fa-solid fa-phone"></i>
                        </button>
                        ` : ''}
                    </div>
                </div>
            `;
        }).join('');
    };

    // ============ SHOW DETAIL ============
    window.showDetail = function(id) {
        const student = studentsData.find(s => s.id == id);
        if (!student) return;
        currentStudentId = id;

        const initials = student.nama_lengkap.split(' ').map(n => n[0]).join('').substring(0, 2);
        document.getElementById('modalAvatar').textContent = initials;
        document.getElementById('modalName').textContent = student.nama_lengkap;
        document.getElementById('modalClass').textContent = `${student.nama_kelas} • Kelas ${student.kelas}`;
        document.getElementById('modalNisn').textContent = student.nisn;
        document.getElementById('modalGender').textContent = student.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
        document.getElementById('modalBirth').textContent = formatDateLong(student.tanggal_lahir);
        document.getElementById('modalPhone').textContent = student.nomor_hp;
        document.getElementById('modalAddress').textContent = student.alamat;
        document.getElementById('modalClassName').textContent = student.nama_kelas;
        document.getElementById('modalEntry').textContent = formatDateLong(student.tanggal_masuk);
        document.getElementById('modalEntryFrom').textContent = getEntryLabel(student.diterima_dari_kelas);
        document.getElementById('modalWaliName').textContent = student.wali.nama;
        document.getElementById('modalRelation').textContent = student.wali.hubungan;
        document.getElementById('modalWaliPhone').textContent = student.wali.nomor_hp;
        document.getElementById('modalWaliAddress').textContent = student.wali.alamat;

        detailModal.show();
    };

    // ============ EDIT MODAL ============
    window.openEditModal = function() {
        const student = studentsData.find(s => s.id === currentStudentId);
        if (!student) return;

        detailModal.hide();

        document.getElementById('editStudentId').value = student.id;
        document.getElementById('editModalSubtitle').textContent = `Perbarui data ${student.nama_lengkap}`;
        document.getElementById('editNama').value = student.nama_lengkap;
        document.getElementById('editNisn').value = student.nisn;
        document.getElementById('editGender').value = student.jenis_kelamin;
        document.getElementById('editTglLahir').value = student.tanggal_lahir;
        document.getElementById('editHp').value = student.nomor_hp;
        document.getElementById('editAlamat').value = student.alamat;
        document.getElementById('editKelas').value = student.kelas;
        populateRombelOptions(document.getElementById('editNamaKelas'), student.kelas);
        document.getElementById('editNamaKelas').value = student.nama_kelas;
        document.getElementById('editTglMasuk').value = student.tanggal_masuk;
        document.getElementById('editNamaWali').value = student.wali.nama;
        document.getElementById('editHpWali').value = student.wali.nomor_hp;
        document.getElementById('editAlamatWali').value = student.wali.alamat;
        document.getElementById('editHubungan').value = student.wali.hubungan;

        setTimeout(() => editModal.show(), 300);
    };

    window.saveEditStudent = function() {
        const id = parseInt(document.getElementById('editStudentId').value);
        const student = studentsData.find(s => s.id === id);
        if (!student) return;

        // Validate NISN unik (kecuali NISN sendiri)
        const newNisn = document.getElementById('editNisn').value.trim();
        if (studentsData.some(s => s.nisn === newNisn && s.id !== id)) {
            showToast('error', 'NISN Duplikat', 'NISN sudah digunakan siswa lain');
            return;
        }

        student.nama = document.getElementById('editNama').value.trim();
        student.nisn = newNisn;
        student.jenis_kelamin = document.getElementById('editGender').value;
        student.tanggal_lahir = document.getElementById('editTglLahir').value;
        student.nomor_hp = document.getElementById('editHp').value.trim();
        student.alamat = document.getElementById('editAlamat').value.trim();
        student.kelas = document.getElementById('editKelas').value;
        student.nama_kelas = document.getElementById('editNamaKelas').value;
        student.tanggal_masuk = document.getElementById('editTglMasuk').value;
        student.wali.nama = document.getElementById('editNamaWali').value.trim();
        student.wali.nomor_hp = document.getElementById('editHpWali').value.trim();
        student.wali.alamat = document.getElementById('editAlamatWali').value.trim();
        student.wali.hubungan = document.getElementById('editHubungan').value;

        saveData();
        updateAllCounts();
        renderList(student.kelas);
        editModal.hide();
        showToast('success', 'Data Diperbarui!', `Perubahan untuk ${student.nama} telah disimpan`);
    };

    // ============ TRANSFER MODAL ============
    window.openTransferModal = function() {
        const student = studentsData.find(s => s.id == currentStudentId);
        if (!student) return;

        detailModal.hide();

        const initials = student.nama_lengkap.split(' ').map(n => n[0]).join('').substring(0, 2);
        const avatarClass = student.jenis_kelamin === 'L' ? 'avatar-male' : 'avatar-female';
        
        document.getElementById('transferStudentId').value = student.id;
        document.getElementById('transferAvatar').textContent = initials;
        document.getElementById('transferAvatar').className = `student-avatar ${avatarClass}`;
        document.getElementById('transferName').textContent = student.nama_lengkap;
        document.getElementById('transferClass').textContent = `${student.nama_kelas} • Kelas ${student.kelas}`;
        document.getElementById('transferNisn').textContent = student.nisn;
        
        // Reset form
        document.getElementById('transferSchool').value = '';
        document.getElementById('transferReason').value = '';
        document.getElementById('transferOtherReason').value = '';
        document.getElementById('transferLetterNo').value = '';
        document.getElementById('transferDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('transferNotes').value = '';
        document.getElementById('otherReasonGroup').style.display = 'none';

        setTimeout(() => transferModal.show(), 300);
    };

    window.processTransfer = function() {
        const id = parseInt(document.getElementById('transferStudentId').value);
        const student = studentsData.find(s => s.id === id);
        if (!student) return;

        const school = document.getElementById('transferSchool').value.trim();
        const reason = document.getElementById('transferReason').value;
        const otherReason = document.getElementById('transferOtherReason').value.trim();
        const letterNo = document.getElementById('transferLetterNo').value.trim();
        const transferDate = document.getElementById('transferDate').value;

        // Validation
        if (!school || !reason || !letterNo || !transferDate) {
            showToast('error', 'Form Belum Lengkap', 'Harap isi semua field yang wajib');
            return;
        }

        if (reason === 'Lainnya' && !otherReason) {
            showToast('error', 'Alasan Belum Lengkap', 'Harap isi alasan lainnya');
            return;
        }

        // Update student status
        student.status = 'pindah';
        student.transferData = {
            sekolahTujuan: school,
            alasan: reason === 'Lainnya' ? otherReason : reason,
            nomorSurat: letterNo,
            tanggalPindah: transferDate,
            catatan: document.getElementById('transferNotes').value.trim(),
            processedAt: new Date().toISOString()
        };

        saveData();
        updateAllCounts();
        renderList(student.kelas);
        transferModal.hide();

        showToast('success', 'Kepindahan Diproses!', `${student.nama} telah dipindahkan ke ${school}`);
    };

    // ============ DELETE MODAL ============
    window.openDeleteConfirm = function() {
        const student = studentsData.find(s => s.id === currentStudentId);
        if (!student) return;

        detailModal.hide();
        document.getElementById('deleteStudentId').value = student.id;
        document.getElementById('deleteStudentName').textContent = student.nama;

        setTimeout(() => deleteModal.show(), 300);
    };

    window.confirmDelete = function() {
        const id = parseInt(document.getElementById('deleteStudentId').value);
        const student = studentsData.find(s => s.id === id);
        if (!student) return;

        studentsData = studentsData.filter(s => s.id !== id);
        saveData();
        updateAllCounts();
        renderList(student.kelas);
        deleteModal.hide();

        showToast('warning', 'Siswa Dihapus', `Data ${student.nama} telah dihapus permanen`);
    };

    // ============ CALL WALI ============
    window.callWali = function(id) {
        const student = studentsData.find(s => s.id === id);
        if (!student) return;
        showToast('info', 'Menghubungi Wali', `Menelpon ${student.wali.nama} (${student.wali.nomor_hp})`);
    };

    // ============ SEARCH & FILTER ============
    ['10', '11', '12'].forEach(kelas => {
        document.getElementById(`search${kelas}`).addEventListener('input', () => renderList(kelas));
        document.getElementById(`filterGender${kelas}`).addEventListener('change', () => renderList(kelas));
        document.getElementById(`filterRombel${kelas}`).addEventListener('change', () => renderList(kelas));
    });

    // ============ UPDATE COUNTS ============
    function updateAllCounts() {
        ['10', '11', '12'].forEach(kelas => {
            const students = studentsData.filter(s => s.kelas == kelas && s.status === 'aktif');
            const males = students.filter(s => s.jenis_kelamin === 'L').length;
            const females = students.filter(s => s.jenis_kelamin === 'P').length;
            const rombels = new Set(students.map(s => s.nama_kelas)).size;

            document.getElementById(`count${kelas}`).textContent = students.length;
            document.getElementById(`total${kelas}`).textContent = students.length;
            document.getElementById(`male${kelas}`).textContent = males;
            document.getElementById(`female${kelas}`).textContent = females;
            document.getElementById(`rombel${kelas}`).textContent = rombels;
        });
    }

    // ============ HELPERS ============
    function saveData() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(studentsData));
    }

    function isStudentNew(tanggalMasuk) {
        if (!tanggalMasuk) return false;
        const entry = new Date(tanggalMasuk);
        const now = new Date();
        const diffDays = (now - entry) / (1000 * 60 * 60 * 24);
        return diffDays <= 30;
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const options = { day: 'numeric', month: 'short', year: 'numeric' };
        return new Date(dateStr).toLocaleDateString('id-ID', options);
    }

    function formatDateLong(dateStr) {
        if (!dateStr) return '-';
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        return new Date(dateStr).toLocaleDateString('id-ID', options);
    }

    function getEntryLabel(entryClass) {
        const labels = {
            '10': 'Kelas 10 (Siswa Baru)',
            '11': 'Kelas 11 (Pindahan)',
            '12': 'Kelas 12 (Pindahan)'
        };
        return labels[entryClass] || '-';
    }


})();