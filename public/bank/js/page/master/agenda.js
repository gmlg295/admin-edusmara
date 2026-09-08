
(function(){
'use strict';

const STORAGE_KEY = 'smantique_pengumuman';
let annData =[]; // JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
let currentDetailId = null;
let detailModalInst, deleteModalInst;

const categoryConfig = {
    pengumuman:   { icon:'fa-bullhorn', label:'Pengumuman' },
    pemberitahuan:{ icon:'fa-clipboard-list', label:'Pemberitahuan' },
    himbauan:     { icon:'fa-bullhorn', label:'Himbauan' },
    undangan:     { icon:'fa-envelope-open-text', label:'Undangan' },
    edaran:       { icon:'fa-file-lines', label:'Edaran' },
    lainnya:      { icon:'fa-pen-nib', label:'Lainnya' }
};

const targetLabels = { semua:'Semua', siswa:'Siswa', guru:'Guru & Staff', ortu:'Orang Tua', kelas12:'Kelas XII' };
const priorityLabels = { normal:'Normal', penting:'⭐ Penting', darurat:'🔴 Darurat' };
const ApiData =  new MyFetch('/agenda/getData', { method: 'GET'});
// Seed demo
    async function loadDataFromServer() {
        try {
            const result = await ApiData.fetchData();
                if(result.status){
                    annData = result.data;
                    setTimeout(() => {
                        renderList();
                        updateSummary();
                    }, 500);

                    const kategoriSelect = document.getElementById('inputKategori');
                    const kategori = result.kategori;
                                    kategoriSelect.innerHTML = '';
                                    kategori.forEach(kat => {
                                        const option = document.createElement('option');
                                        option.value = kat.id_kategori_informasi;
                                        option.textContent = kat.nama_kategori;
                                        kategoriSelect.appendChild(option);
                                    });
                };
                if (!result.status) throw new Error(`Device log gagal: ${result.status}`);
            
        } catch (error) {
            
            console.error(error);
        }
    }   

// if(annData.length===0){
//     annData = [
//         { id:1, judul:'Pemberitahuan Tentang Pelaksanaan Ujian Praktik Kelas XII Tahun 2026', kategori:'pemberitahuan', tanggal:'2026-02-08', penulis:'Wakil Kepala Sekolah Bidang Kurikulum', konten:'Assalamualaikum warahmatullahi wabarakatuh.\n\nKami informasikan bahwa Pelaksanaan Ujian Praktik Kelas XII TP 2025/2026 akan dilaksanakan pada:\n\n- Tanggal: 2 s.d. 13 Maret 2026\n- Pukul: 07:30 – 12:00 WIB\n- Tempat: Ruang Kelas & Laboratorium\n\nSiswa wajib hadir tepat waktu dan membawa peralatan praktik sesuai mata ujian.\n\n⚠️ PERHATIAN:\nKeterlambatan lebih dari 15 menit tanpa alasan sah akan dianggap tidak mengikuti ujian.\n\nWassalamualaikum warahmatullahi wabarakatuh.', target:'semua', prioritas:'penting', status:'published', createdAt:'2026-02-08T09:30:00Z' },
//         { id:2, judul:'Himbauan Kepada Orang Tua untuk Selalu Memberikan Pendampingan kepada Ananda', kategori:'himbauan', tanggal:'2025-11-11', penulis:'Kepala Sekolah', konten:'Assalamualaikum warahmatullahi wabarakatuh.\n\nAyah/Bunda, kami berharap kerjasamanya untuk terus memantau kegiatan ananda di rumah dalam proses pembelajaran.\n\nℹ️ INFORMASI:\nBeberapa hal yang perlu diperhatikan:\n1. Pastikan ananda belajar minimal 2 jam sehari\n2. Pantau penggunaan gadget\n3. Komunikasi aktif dengan wali kelas\n\nTerima kasih atas kerjasama Ayah/Bunda.', target:'ortu', prioritas:'normal', status:'published', createdAt:'2025-11-11T08:00:00Z' },
//         { id:3, judul:'Undangan Rapat Koordinasi Wali Murid Kelas XII', kategori:'undangan', tanggal:'2026-02-20', penulis:'Humas SMAN 3', konten:'Dengan hormat,\n\nMengharap kehadiran Bapak/Ibu Wali Murid Kelas XII pada:\n\n- Hari/Tanggal: Sabtu, 28 Februari 2026\n- Pukul: 09:00 – 11:00 WIB\n- Tempat: Aula SMAN 3 Bengkulu Tengah\n- Agenda: Sosialisasi Ujian Sekolah & Persiapan Kelulusan\n\nDemikian undangan ini kami sampaikan. Atas kehadirannya kami ucapkan terima kasih.', target:'ortu', prioritas:'penting', status:'published', createdAt:'2026-02-20T10:00:00Z' },
//         { id:4, judul:'Edaran Larangan Membawa Handphone Selama ASAS', kategori:'edaran', tanggal:'2025-05-24', penulis:'Kepala Sekolah', konten:'Assalamualaikum wr wb.\n\nSehubungan akan dilaksanakannya Assesmen Akhir Semester (ASAS) Genap TP 2024-2025, dengan ini diberitahukan bahwa:\n\n⚠️ PERHATIAN:\nSelama pelaksanaan ASAS, siswa DILARANG membawa handphone ke lingkungan sekolah.\n\nPelanggaran terhadap ketentuan ini akan dikenakan sanksi sesuai tata tertib sekolah.\n\nWassalamualaikum wr wb.', target:'siswa', prioritas:'darurat', status:'published', createdAt:'2025-05-24T14:00:00Z' },
//         { id:5, judul:'Pengumuman Libur Isra Mi\'raj Nabi Muhammad SAW 1448 H', kategori:'pengumuman', tanggal:'2026-08-18', penulis:'Admin', konten:'Diberitahukan kepada seluruh warga sekolah bahwa dalam rangka peringatan Isra Mi\'raj Nabi Muhammad SAW 1448 H, kegiatan belajar mengajar diliburkan pada:\n\n- Hari: Kamis, 20 Agustus 2026\n- Kegiatan masuk kembali: Jumat, 21 Agustus 2026\n\nDemikian disampaikan, terima kasih.', target:'semua', prioritas:'normal', status:'draft', createdAt:'2026-08-18T09:00:00Z' }
//     ];
//     //saveData();
// }

// ============ INIT ============
document.addEventListener('DOMContentLoaded',()=>{
    detailModalInst = new bootstrap.Modal(document.getElementById('detailModal'));
    deleteModalInst = new bootstrap.Modal(document.getElementById('deleteModal'));
    loadDataFromServer();
    initTabs();
    updateSummary();
    renderList();
    document.getElementById('inputTanggal').value = new Date().toISOString().split('T')[0];
});

function initTabs(){
    document.querySelectorAll('.page-tab').forEach(tab=>{
        tab.addEventListener('click',function(){
            document.querySelectorAll('.page-tab').forEach(t=>t.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
            document.getElementById(`tab-${this.dataset.tab}`).classList.add('active');
        });
    });
}

window.switchToForm = function(editId=null){
    document.querySelectorAll('.page-tab').forEach(t=>t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
    document.querySelector('[data-tab="form"]').classList.add('active');
    document.getElementById('tab-form').classList.add('active');
    if(editId) loadEditForm(editId);
    else resetForm();
};

// ============ RENDER LIST ============
window.renderList = function(){
    const list = document.getElementById('annList');
    const search = document.getElementById('searchAnn').value.toLowerCase().trim();
    const fCat = document.getElementById('filterCategory').value;
    const fStat = document.getElementById('filterStatus').value;

    let filtered = annData.filter(a=>{
        const ms = !search || a.judul.toLowerCase().includes(search);
        const mc = fCat==='all' || a.kategori===fCat;
        const mst = fStat==='all' || a.status===fStat;
        return ms && mc && mst;
    }).sort((a,b)=>new Date(b.tanggal)-new Date(a.tanggal));

    if(filtered.length===0){
        list.innerHTML = `<div class="empty-state"><i class="fa-solid fa-inbox"></i><h4>Tidak Ada Data</h4><p>${annData.length===0?'Belum ada pengumuman. Klik "Buat Pengumuman" untuk memulai.':'Tidak ada pengumuman yang cocok dengan filter.'}</p></div>`;
        return;
    }

    list.innerHTML = filtered.map((a,idx)=>{
        const cfg = categoryConfig[a.kategori.toLowerCase()] || categoryConfig.lainnya;
        return `
        <div class="ann-card cat-${a.kategori.toLowerCase()}" style="animation-delay:${idx*0.04}s" onclick="showDetail(${a.id_informasi})">
            <div class="ann-icon ${a.kategori.toLowerCase()}"><i class="fa-solid ${cfg.icon}"></i></div>
            <div class="ann-body">
                <div class="ann-top">
                    <span class="ann-category ${a.kategori}"><i class="fa-solid ${cfg.icon}"></i> ${cfg.label}</span>
                    <span class="ann-status ${a.status}">${a.status==='published'?'✅ Publikasi':'📝 Draft'}</span>
                </div>
                <div class="ann-title">${a.judul}</div>
                <div class="ann-excerpt">${a.konten.substring(0,120).replace(/\n/g,' ')}...</div>
                <div class="ann-meta">
                    <span><i class="fa-regular fa-calendar"></i> ${formatDate(a.tanggal_publikasi)}</span>
                    <span><i class="fa-solid fa-user-pen"></i> ${a.penulis||'Admin'}</span>
                    <span><i class="fa-solid fa-users"></i> ${targetLabels[a.target_pembaca]||'Semua'}</span>
                </div>
            </div>
            <div class="ann-actions" onclick="event.stopPropagation()">
                <button class="action-btn view" onclick="showDetail(${a.id_informasi})" title="Detail"><i class="fa-solid fa-eye"></i></button>
                <button class="action-btn edit" onclick="switchToForm(${a.id_informasi})" title="Edit"><i class="fa-solid fa-pen"></i></button>
                <button class="action-btn delete" onclick="openDeleteDirect(${a.id_informasi})" title="Hapus"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>`;
    }).join('');
};

document.getElementById('searchAnn').addEventListener('input',renderList);
document.getElementById('filterCategory').addEventListener('change',renderList);
document.getElementById('filterStatus').addEventListener('change',renderList);

// ============ SHOW DETAIL ============
window.showDetail = function(id){
    const a = annData.find(x=>x.id_informasi==id);
    if(!a) return;
    currentDetailId = a.id_informasi;
    const cfg = categoryConfig[a.kategori.toLowerCase()]||categoryConfig.lainnya;

    document.getElementById('mCategoryBadge').innerHTML = `<span class="ann-category ${a.kategori.toLowerCase()}" style="font-size:11px;padding:4px 12px;"><i class="fa-solid ${cfg.icon}"></i> ${cfg.label}</span>`;
    document.getElementById('mTitle').textContent = a.judul;
    document.getElementById('mDate').textContent = formatDateLong(a.tanggal_publikasi);
    document.getElementById('mCat').textContent = cfg.label;
    document.getElementById('mStatus').textContent = a.status==='published'?'✅ Dipublikasi':'📝 Draft';
    document.getElementById('mAuthor').textContent = a.penulis||'Admin';
    document.getElementById('mTarget').textContent = targetLabels[a.target_pembaca]||'Semua';
    document.getElementById('mPriority').textContent = priorityLabels[a.prioritas]||'Normal';
    document.getElementById('mCreated').textContent = a.created_at ? new Date(a.created_at).toLocaleString('id-ID') : '-';
    document.getElementById('mContent').textContent = a.konten;

    if(a.gambar_header){
        document.getElementById('mImage').src = `./public/bank/sampel/${a.gambar_header}`;
        document.getElementById('mImage').style.display = 'block';
    }else{
        document.getElementById('mImage').src = '#';
        document.getElementById('mImage').style.display = 'none';
    }

    detailModalInst.show();
};

window.openEditFromModal = function(){
    detailModalInst.hide();
    setTimeout(()=>switchToForm(currentDetailId),300);
};

const inputFiles = document.getElementById('inputFiles');
const imagePreview = document.getElementById('imagePreview');
const annForm = document.getElementById('annForm');

inputFiles.addEventListener('change',function(e){
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = function(e){
            imagePreview.src = e.target.result;
        }
        reader.readAsDataURL(file);
        imagePreview.style.display = 'block';
    }else{
        imagePreview.src = '#';
        imagePreview.style.display = 'none';
    }
});

// ============ FORM ============
function loadEditForm(id){
    const a = annData.find(x=>x.id_informasi==id);
    if(!a) return;
    document.getElementById('editId').value = a.id_informasi;
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Ubah Pengumuman';
    document.getElementById('formSubtitle').textContent = `Mengedit: ${a.judul}`;
    document.getElementById('inputJudul').value = a.judul;
    document.getElementById('inputKategori').value = a.kategori;
    document.getElementById('inputTanggal').value = a.tanggal_publikasi;
    document.getElementById('inputPenulis').value = a.penulis||'';
    document.getElementById('inputKonten').value = a.konten;
    document.getElementById('inputTarget').value = a.target_pembaca||'semua';
    document.getElementById('inputPrioritas').value = a.prioritas||'normal';
}

window.resetForm = function(){
    document.getElementById('annForm').reset();
    document.getElementById('editId').value = '';
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Buat Pengumuman Baru';
    document.getElementById('formSubtitle').textContent = 'Lengkapi informasi pengumuman sebelum dipublikasikan';
    document.getElementById('inputTanggal').value = new Date().toISOString().split('T')[0];
    document.getElementById('previewBox').classList.remove('visible');
};

// Submit = publish
document.getElementById('annForm').addEventListener('submit',function(e){
    e.preventDefault();
    saveAnnouncement('published');
});

window.saveAsDraft = function(){
    const judul = document.getElementById('inputJudul').value.trim();
    const konten = document.getElementById('inputKonten').value.trim();
    if(!judul||!konten){ showToast('error','Form Belum Lengkap','Judul dan konten wajib diisi'); return; }
    saveAnnouncement('draft');
};

async function saveAnnouncement(status){
    const editId = document.getElementById('editId').value;
    let files = document.getElementById('inputFiles').files[0];

      // Buat FormData object
    const formData = new FormData();
    formData.append('judul', document.getElementById('inputJudul').value.trim());
    formData.append('kategori', document.getElementById('inputKategori').value);
    formData.append('tanggal_publikasi', document.getElementById('inputTanggal').value);
    formData.append('konten', document.getElementById('inputKonten').value.trim());
    formData.append('target_pembaca', document.getElementById('inputTarget').value);
    formData.append('prioritas', document.getElementById('inputPrioritas').value);
    formData.append('status', status);

    formData.append('isUpdateFile', 'false');
    if (files) {
        formData.append('files', files);
    }


    const data = {
        judul: document.getElementById('inputJudul').value.trim(),
        kategori: document.getElementById('inputKategori').value,
        tanggal_publikasi: document.getElementById('inputTanggal').value,
        //penulis: document.getElementById('inputPenulis').value.trim(),
        konten: document.getElementById('inputKonten').value.trim(),
        target_pembaca: document.getElementById('inputTarget').value,
        prioritas: document.getElementById('inputPrioritas').value,
        status: status
    };

    const submitData =  new MyFetch('/agenda/add', 
            {
                method: 'POST',
                csrfHash: document.getElementById('csrf_edusmara').textContent,
                params : formData
            }
        );

    if(!data.judul||!data.kategori||!data.tanggal_publikasi||!data.konten){
        showToast('error','Form Belum Lengkap','Harap isi semua field wajib'); return;
    }

    if(editId !== '' && editId !== null && editId !== undefined){
        //resetMsgError();
        if (files) {
            formData.append('isUpdateFile', 'true');
        }
             const updateData =  new MyFetch('/agenda/update/' + editId, 
                                {
                                    method: 'POST',
                                    csrfHash: document.getElementById('csrf_edusmara').textContent,
                                    params : formData
                                }
                            );

            try {
                const result = await updateData.fetchData();
                if(result.status){
                    showToast('success', 'Berhasil!', `Data ${data.judul} berhasil diperbarui.`);
                    reloadData();
                }
            } catch (error) {
                if(error.status === 422) {
                    const errors = error.data.errors;
                    console.log('Validation Errors:', errors);
                    const errorMessages = Object.values(errors).flat().join('<br>');
                    showToast('error', 'Validasi Gagal!', errorMessages);

                    const errorFields = Object.keys(errors);
                    errorFields.forEach(field => {
                        const input = document.getElementsByName(`input-${field}`)[0];
                        if (input) {
                            input.classList.add('is-invalid');
                            input.nextElementSibling.textContent = `* ${errors[field]}`;
                        }
                    });


                } else {
                    showToast('error', 'Gagal!', `Terjadi kesalahan saat menambahkan data pengumuman.`);
                }

                return;
            }
    } else {

         //resetMsgError();
            try {
                const result = await submitData.fetchData();
                if(result.status){

                    showToast('success', 'Berhasil!', `Pengumuman ${data.judul} berhasil ditambahkan.`);
                    reloadData();
                }
            } catch (error) {
               // console.error(error);
                if(error.status === 422) {
                    const errors = error.data.errors;
                    console.log('Validation Errors:', errors);
                    const errorMessages = Object.values(errors).flat().join('<br>');
                    showToast('error', 'Validasi Gagal!', errorMessages);

                    const errorFields = Object.keys(errors);
                    errorFields.forEach(field => {
                        const input = document.getElementsByName(`input-${field}`)[0];
                        if (input) {
                            input.classList.add('is-invalid');
                            input.nextElementSibling.textContent = `* ${errors[field]}`;
                        }
                    });


                } else {
                    showToast('error', 'Gagal!', `Terjadi kesalahan saat menambahkan data pengumuman.`);
                }

               return;
            }
    }

    function reloadData() {
        loadDataFromServer();
        updateSummary();
        renderList();
        resetForm();

        // Kembali ke Tab List
        const listTab = document.querySelector('[data-tab="list"]');
        const listContent = document.getElementById('tab-list');
        if(listTab && listContent) {
            document.querySelectorAll('.page-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            listTab.classList.add('active');
            listContent.classList.add('active');
        }

    }
}

// ============ EDITOR TOOLBAR ============
window.insertFormat = function(before,after){
    const ta = document.getElementById('inputKonten');
    const start = ta.selectionStart, end = ta.selectionEnd;
    const selected = ta.value.substring(start,end);
    const replacement = before + (selected||'teks') + after;
    ta.setRangeText(replacement,start,end,'select');
    ta.focus();
};

window.insertPrefix = function(prefix){
    const ta = document.getElementById('inputKonten');
    const start = ta.selectionStart;
    const lineStart = ta.value.lastIndexOf('\n',start-1)+1;
    ta.setRangeText(prefix,lineStart,lineStart,'end');
    ta.focus();
};

window.insertBlock = function(text){
    const ta = document.getElementById('inputKonten');
    const pos = ta.selectionStart;
    const insert = '\n'+text+'\n';
    ta.setRangeText(insert,pos,pos,'end');
    ta.focus();
};

window.togglePreview = function(){
    const box = document.getElementById('previewBox');
    const content = document.getElementById('inputKonten').value;
    if(box.classList.contains('visible')){
        box.classList.remove('visible');
    } else {
        document.getElementById('previewContent').textContent = content || '(kosong)';
        box.classList.add('visible');
    }
};

// ============ DELETE ============
window.openDeleteConfirm = function(){
    const a = annData.find(x=>x.id_informasi==currentDetailId);
    alert('id: '+currentDetailId);
    if(!a) return;
    detailModalInst.hide();
    document.getElementById('deleteId').value = a.id_informasi;
    document.getElementById('deleteName').textContent = a.judul;
    setTimeout(()=>deleteModalInst.show(),300);
};

window.openDeleteDirect = function(id){
    const a = annData.find(x=>x.id_informasi==id);
    alert('id: '+id);
    if(!a) return;
    document.getElementById('deleteId').value = a.id_informasi;
    document.getElementById('deleteName').textContent = a.judul;
    deleteModalInst.show();
};

window.confirmDelete = function(){
    const id = parseInt(document.getElementById('deleteId').value);
    const a = annData.find(x=>x.id_informasi==id);
    if(!a) return;

        const removeData =  new MyFetch('/agenda/delete/' + id, {method: 'GET', csrfHash: document.getElementById('csrf_edusmara').textContent});

        try {
            removeData.fetchData().then(result => {
                console.log('Delete Result:', result);
                if(result.status){
                    showToast('warning', 'Dihapus', `Data pengumuman ${a.judul} telah dihapus permanen.`);
                    annData = annData.filter(x => x.id_informasi !== id);
                    loadDataFromServer();
                    updateSummary();
                    renderList();
                    deleteModalInst?.hide();
                }
            });

        } catch (error) {
            console.error(error);
            showToast('warning', 'Dihapus', error.message || `Terjadi kesalahan saat menghapus data kelas ${k.judul}.`);
        }
        
};

// ============ SUMMARY ============
function updateSummary(){
    document.getElementById('sumTotal').textContent = annData.length;
    document.getElementById('sumPublished').textContent = annData.filter(a=>a.status==='published').length;
    document.getElementById('sumDraft').textContent = annData.filter(a=>a.status==='draft').length;
    const cats = new Set(annData.map(a=>a.kategori));
    document.getElementById('sumCategories').textContent = cats.size;
}

// ============ HELPERS ============
//function saveData(){ localStorage.setItem(STORAGE_KEY,JSON.stringify(annData)); }
function formatDate(d){ return d?new Date(d).toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'}):'-'; }
function formatDateLong(d){ return d?new Date(d).toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'}):'-'; }

// ============ TOAST ============
window.showToast = function(type,title,msg){
    const container = document.getElementById('toastContainer');
    const icons = {success:'fa-circle-check',info:'fa-circle-info',warning:'fa-triangle-exclamation',error:'fa-circle-xmark'};
    const toast = document.createElement('div');
    toast.className = `toast-custom ${type}`;
    toast.innerHTML = `<i class="fa-solid ${icons[type]}"></i><div class="toast-content"><div class="toast-title">${title}</div><div class="toast-msg">${msg}</div></div><button class="toast-close"><i class="fa-solid fa-xmark"></i></button>`;
    container.appendChild(toast);
    toast.querySelector('.toast-close').addEventListener('click',()=>{toast.classList.add('hiding');setTimeout(()=>toast.remove(),300);});
    setTimeout(()=>{if(toast.parentNode){toast.classList.add('hiding');setTimeout(()=>toast.remove(),300);}},3500);
};

function resetMsgError() {
    const errorFields = document.querySelectorAll('.is-invalid');
    errorFields.forEach(field => field.classList.remove('is-invalid'));
    const errorNotes = document.querySelectorAll('.form-note');
    errorNotes.forEach(note => note.textContent = '');
}


})();