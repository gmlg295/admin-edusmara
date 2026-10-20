
(function(){
'use strict';

const CAT_CONFIG = {
    akademik:{label:'Akademik',icon:'fa-book-open',color:'#1e40af'},
    ujian:{label:'Ujian',icon:'fa-file-pen',color:'#ef4444'},
    ekskul:{label:'Ekstrakurikuler',icon:'fa-futbol',color:'#10b981'},
    rapat:{label:'Rapat',icon:'fa-users',color:'#06b6d4'},
    libur:{label:'Libur',icon:'fa-umbrella-beach',color:'#8b5cf6'}
};
const STATUS_LABELS = {mendatang:'Mendatang',berlangsung:'Berlangsung',selesai:'Selesai',dibatalkan:'Dibatalkan',draft:'Draft', published:'Dipublikasi'};
const PRIORITY_LABELS = {normal:'Normal',tinggi:'⭐ Tinggi',darurat:'🔴 Darurat'};
const TARGET_LABELS = {semua:'Semua Warga Sekolah',siswa:'Siswa',guru:'Guru & Staff',ortu:'Orang Tua / Wali',kelas12:'Kelas XII',undangan:'Tamu Undangan'};
let agendaData = [];
// // Demo data matching frontend agenda
// let agendaData = [
//     {id:1,title:'Rapat Koordinasi Panitia 17 Agustus',category:'rapat',dateStart:'2026-08-16',dateEnd:'',timeStart:'14:00',timeEnd:'16:00',fullDay:false,location:'Ruang Guru',target:'guru',estPeserta:12,participants:'Kepala Sekolah, Wakasek, Panitia HUT RI',desc:'Rapat koordinasi final persiapan upacara HUT RI ke-81. Membahas rundown acara, pembagian tugas, dan pengecekan peralatan.',priority:'tinggi',countdown:false,reminder:true,status:'upcoming',createdAt:'2026-08-10T09:00:00Z'},
//     {id:2,title:'Latihan Paskibra - Persiapan HUT RI',category:'ekskul',dateStart:'2026-08-16',dateEnd:'',timeStart:'15:30',timeEnd:'17:30',fullDay:false,location:'Lapangan Utama',target:'siswa',estPeserta:48,participants:'Anggota Paskibra, Pelatih',desc:'Gladi bersih terakhir sebelum upacara HUT RI ke-81. Fokus pada formasi dan sinkronisasi gerakan.',priority:'tinggi',countdown:false,reminder:false,status:'upcoming',createdAt:'2026-08-10T09:00:00Z'},
//     {id:3,title:'Upacara HUT Kemerdekaan RI ke-81',category:'akademik',dateStart:'2026-08-17',dateEnd:'',timeStart:'07:00',timeEnd:'09:00',fullDay:false,location:'Lapangan Utama',target:'semua',estPeserta:800,participants:'Seluruh Siswa, Guru, Staf, Tamu Undangan',desc:'Upacara bendera memperingati HUT Kemerdekaan RI ke-81. Dilanjutkan dengan lomba-lomba tradisional antar kelas.',priority:'tinggi',countdown:true,reminder:true,status:'upcoming',createdAt:'2026-08-01T08:00:00Z'},
//     {id:4,title:'Lomba Tradisional Antar Kelas',category:'ekskul',dateStart:'2026-08-17',dateEnd:'',timeStart:'09:30',timeEnd:'12:00',fullDay:false,location:'Lapangan Utama & Halaman Sekolah',target:'siswa',estPeserta:800,participants:'Seluruh Kelas X, XI, XII',desc:'Lomba tradisional: balap karung, tarik tambang, makan kerupuk, panjat pinang. Pemenang mendapat trophy dan hadiah menarik.',priority:'normal',countdown:false,reminder:false,status:'upcoming',createdAt:'2026-08-01T08:00:00Z'},
//     {id:5,title:'Libur Isra Mi\'raj Nabi Muhammad SAW',category:'libur',dateStart:'2026-08-20',dateEnd:'',timeStart:'',timeEnd:'',fullDay:true,location:'Seluruh Wilayah Indonesia',target:'semua',estPeserta:0,participants:'Seluruh warga sekolah',desc:'Libur nasional peringatan Isra Mi\'raj Nabi Muhammad SAW 1448 H. Kegiatan belajar mengajar diliburkan.',priority:'normal',countdown:false,reminder:false,status:'upcoming',createdAt:'2026-07-15T08:00:00Z'},
//     {id:6,title:'Asesmen Sumatif Tengah Semester (ASTS) Ganjil',category:'ujian',dateStart:'2026-08-25',dateEnd:'2026-09-05',timeStart:'07:30',timeEnd:'12:00',fullDay:false,location:'Ruang Kelas Masing-masing',target:'siswa',estPeserta:800,participants:'Seluruh Siswa Kelas X, XI, XII',desc:'Pelaksanaan ASTS Semester Ganjil TP 2026/2027. Jadwal mata ujian akan dibagikan oleh wali kelas masing-masing.',priority:'tinggi',countdown:true,reminder:true,status:'upcoming',createdAt:'2026-07-20T08:00:00Z'},
//     {id:7,title:'Sosialisasi Kurikulum Merdeka Belajar',category:'akademik',dateStart:'2026-08-25',dateEnd:'',timeStart:'13:00',timeEnd:'15:00',fullDay:false,location:'Aula Sekolah',target:'guru',estPeserta:45,participants:'Guru Mata Pelajaran',desc:'Sosialisasi implementasi Kurikulum Merdeka Belajar untuk semester ganjil oleh narasumber dari Dinas Pendidikan.',priority:'normal',countdown:false,reminder:true,status:'upcoming',createdAt:'2026-08-05T08:00:00Z'},
// ];
let nextId = 100;
let detailModalInst, deleteModalInst;
let currentDetailId = null;

const ApiData =  new MyFetch('/sms/agenda/getData', { method: 'GET'});
// Seed demo
    async function loadDataFromServer() {
        try {
            const result = await ApiData.fetchData();
                if(result.status){
                    agendaData = result.data;
                    setTimeout(() => {
                        renderList();
                        updateSummary();
                    }, 500);

                    const kategoriSelect = document.getElementById('fCategory');
                    const kategori = result.kategori;
                                    kategoriSelect.innerHTML = '';
                                    kategori.forEach(kat => {
                                        const option = document.createElement('option');
                                        option.value = kat.id_jenis_agenda;
                                        option.textContent = kat.nama_jenis;
                                        kategoriSelect.appendChild(option);
                                    });
                };
                if (!result.status) throw new Error(`Device log gagal: ${result.status}`);
            
        } catch (error) {
            
            console.error(error);
        }
    }   

// ============ INIT ============
document.addEventListener('DOMContentLoaded',()=>{
    detailModalInst = new bootstrap.Modal(document.getElementById('detailModal'));
    deleteModalInst = new bootstrap.Modal(document.getElementById('deleteModal'));
    loadDataFromServer();
    initTabs();
    renderList();
    updateSummary();
    document.getElementById('fDateStart').value = new Date().toISOString().split('T')[0];
    document.getElementById('searchInput').addEventListener('input',renderList);
    document.getElementById('filterCat').addEventListener('change',renderList);
    document.getElementById('filterStatus').addEventListener('change',renderList);
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

// ============ RENDER LIST ============
function renderList(){
    const container = document.getElementById('agendaList');
    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const fCat = document.getElementById('filterCat').value;
    const fStat = document.getElementById('filterStatus').value;

    let filtered = agendaData.filter(a=>{
        const ms = !search || a.title.toLowerCase().includes(search);
        const mc = fCat==='all' || a.category===fCat;
        const mst = fStat==='all' || a.status===fStat;
        return ms && mc && mst;
    }).sort((a,b)=>new Date(a.dateStart)-new Date(b.dateStart));

    if(filtered.length===0){
        container.innerHTML=`<div class="empty-state"><i class="fa-solid fa-calendar-xmark"></i><h4>Tidak Ada Agenda</h4><p>${agendaData.length===0?'Belum ada agenda. Klik "Tambah Agenda" untuk memulai.':'Tidak ada agenda yang cocok dengan filter.'}</p></div>`;
        return;
    }

    container.innerHTML = filtered.map((a,idx)=>{
        const d = new Date(a.dateStart);
        const day = d.getDate();
        const month = d.toLocaleDateString('id-ID',{month:'short'});
        const catCfg = CAT_CONFIG[a.category]||CAT_CONFIG.akademik;
        const dateBoxClass = a.category==='libur'?'libur':a.category==='ujian'?'ujian':'';

        return `<div class="ag-card cat-${a.category}" style="animation-delay:${idx*0.04}s" onclick="showDetail(${a.id})">
            <div class="ag-date-box ${dateBoxClass}">
                <div class="day">${day}</div>
                <div class="month">${month}</div>
            </div>
            <div class="ag-body">
                <div class="ag-top">
                    <span class="ag-badge ${a.category}"><i class="fa-solid ${catCfg.icon}"></i> ${catCfg.label}</span>
                    <span class="ag-status ${a.status}">${STATUS_LABELS[a.status]||a.status}</span>
                </div>
                <div class="ag-title">${a.title}</div>
                <div class="ag-meta">
                    <span><i class="fa-solid fa-location-dot"></i> ${a.location}</span>
                    ${a.timeStart?`<span><i class="fa-regular fa-clock"></i> ${a.timeStart}${a.timeEnd?' - '+a.timeEnd:''}</span>`:'<span><i class="fa-solid fa-sun"></i> Full Day</span>'}
                    ${a.priority!=='normal'?`<span><i class="fa-solid fa-flag"></i> ${PRIORITY_LABELS[a.priority]||a.priority}</span>`:''}
                </div>
            </div>
            <div class="ag-actions" onclick="event.stopPropagation()">
                <button class="act-btn view" onclick="showDetail(${a.id})" title="Detail"><i class="fa-solid fa-eye"></i></button>
                <button class="act-btn edit" onclick="openEditForm(${a.id})" title="Edit"><i class="fa-solid fa-pen"></i></button>
                <button class="act-btn delete" onclick="openDeleteConfirm(${a.id})" title="Hapus"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>`;
    }).join('');
}

// ============ SUMMARY ============
function updateSummary(){
    document.getElementById('sumTotal').textContent = agendaData.length;
    document.getElementById('sumUpcoming').textContent = agendaData.filter(a=>a.status==='mendatang').length;
    document.getElementById('sumOngoing').textContent = agendaData.filter(a=>a.status==='berlangsung').length;
    document.getElementById('sumDone').textContent = agendaData.filter(a=>a.status==='selesai').length;
}

// ============ SHOW DETAIL ============
window.showDetail = function(id){
    const a = agendaData.find(x=>x.id===id);
    if(!a) return;
    currentDetailId = id;
    const catCfg = CAT_CONFIG[a.category]||CAT_CONFIG.akademik;

    document.getElementById('mBadge').innerHTML = `<span class="ag-badge ${a.category}" style="font-size:11px;padding:4px 12px"><i class="fa-solid ${catCfg.icon}"></i> ${catCfg.label}</span>`;
    document.getElementById('mTitle').textContent = a.title;
    document.getElementById('mDate').textContent = formatDateRange(a.dateStart,a.dateEnd);
    document.getElementById('mTime').textContent = a.fullDay ? 'Seharian Penuh' : `${a.timeStart||'-'} - ${a.timeEnd||'-'}`;
    document.getElementById('mLoc').textContent = a.location;
    document.getElementById('mTarget').textContent = TARGET_LABELS[a.target]||a.target;
    document.getElementById('mPriority').textContent = PRIORITY_LABELS[a.priority]||a.priority;
    document.getElementById('mStatus').textContent = STATUS_LABELS[a.status]||a.status;
    document.getElementById('mEst').textContent = a.estPeserta ? `${a.estPeserta} orang` : '-';
    document.getElementById('mDesc').textContent = a.desc;

    const partRow = document.getElementById('mPartRow');
    if(a.participants){partRow.style.display='flex';document.getElementById('mPart').textContent=a.participants;}
    else{partRow.style.display='none';}

    detailModalInst.show();
};

window.editFromModal = function(){
    detailModalInst.hide();
    setTimeout(()=>openEditForm(currentDetailId),300);
};

window.deleteFromModal = function(){
    detailModalInst.hide();
    setTimeout(()=>openDeleteConfirm(currentDetailId),300);
};

// ============ FORM: ADD ============
window.openAddForm = function(){
    resetForm();
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-calendar-plus"></i> Tambah Agenda Baru';
    document.getElementById('formSubtitle').textContent = 'Lengkapi informasi agenda sebelum dipublikasikan';
    switchTab('form');
};

// ============ FORM: EDIT ============
window.openEditForm = function(id){
    const a = agendaData.find(x=>x.id===id);
    if(!a) return;
    resetForm();
    document.getElementById('editId').value = a.id;
    document.getElementById('formTitle').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Agenda';
    document.getElementById('formSubtitle').textContent = `Mengedit: ${a.title}`;
    document.getElementById('fTitle').value = a.title;
    document.getElementById('fCategory').value = a.category;
    document.getElementById('fDateStart').value = a.dateStart;
    document.getElementById('fDateEnd').value = a.dateEnd||'';
    document.getElementById('fTimeStart').value = a.timeStart||'';
    document.getElementById('fTimeEnd').value = a.timeEnd||'';
    document.getElementById('fFullDay').value = a.fullDay?'1':'0';
    document.getElementById('fLocation').value = a.location;
    document.getElementById('fTarget').value = a.target;
    document.getElementById('fEstPeserta').value = a.estPeserta||'';
    document.getElementById('fParticipants').value = a.participants||'';
    document.getElementById('fDesc').value = a.desc;
    document.getElementById('fPriority').value = a.priority||'normal';
    document.getElementById('fCountdown').value = a.countdown?'1':'0';
    document.getElementById('fReminder').value = a.reminder?'1':'0';
    switchTab('form');
};

function resetForm(){
    document.getElementById('agendaForm').reset();
    document.getElementById('editId').value = '';
    document.getElementById('fDateStart').value = new Date().toISOString().split('T')[0];
}

function switchTab(tab){
    document.querySelectorAll('.page-tab').forEach(t=>t.classList.remove('active'));
    document.querySelector(`[data-tab="${tab}"]`).classList.add('active');
    document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
    document.getElementById(`tab-${tab}`).classList.add('active');
}

// ============ SAVE ============
document.getElementById('agendaForm').addEventListener('submit',function(e){
    e.preventDefault();
    saveAgenda('mendatang');
});

window.saveDraft = function(){ saveAgenda('draft'); };

async function saveAgenda(status){
    const editId = document.getElementById('editId').value;
    const title = document.getElementById('fTitle').value.trim();
    const category = document.getElementById('fCategory').value;
    const dateStart = document.getElementById('fDateStart').value;
    const location = document.getElementById('fLocation').value.trim();
    const target = document.getElementById('fTarget').value;
    const desc = document.getElementById('fDesc').value.trim();

    if(!title||!category||!dateStart||!location||!target||!desc){
        showToast('error','Form Belum Lengkap','Harap isi semua field yang wajib (*)');return;
    }

    const entry = {
        judul: title, 
        id_jenis_agenda: category,
        tanggal_mulai : dateStart,
        dateEnd: document.getElementById('fDateEnd').value||'',
        timeStart: document.getElementById('fTimeStart').value||'',
        timeEnd: document.getElementById('fTimeEnd').value||'',
        fullDay: document.getElementById('fFullDay').value==='1',
        lokasi : location,
        target_peserta: target,
        estimasi_peserta: parseInt(document.getElementById('fEstPeserta').value)||0,
        participants: document.getElementById('fParticipants').value.trim(),
        deskripsi: desc,
        priority: document.getElementById('fPriority').value,
        countdown: document.getElementById('fCountdown').value==='1',
        reminder: document.getElementById('fReminder').value==='1',
        status,
        isUpdateFile: 'false'
    };

     const submitdata =  new MyFetch('/sms/agenda/add', 
                            {
                                method: 'POST',
                                csrfHash: document.getElementById('csrf_edusmara').textContent,
                                params : entry
                            }
                        );

    if(editId){

        const updateData =  new MyFetch('/sms/agenda/update/' + editId, 
                                {
                                    method: 'POST',
                                    csrfHash: document.getElementById('csrf_edusmara').textContent,
                                    params : entry
                                }
                            );

            try {
                const result = await updateData.fetchData();
                if(result.status){
                    showToast('success', 'Berhasil!', `Data ${entry.judul} berhasil diperbarui.`);
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
                    showToast('error', 'Gagal!', `Terjadi kesalahan saat menambahkan data Agenda.`);
                }

                return;
            }

        // const idx = agendaData.findIndex(a=>a.id===parseInt(editId));
        // if(idx>-1) agendaData[idx] = {...agendaData[idx],...entry};
        // showToast('success','Agenda Diperbarui','Perubahan berhasil disimpan');
    } else {
        try {
                const result = await submitdata.fetchData();
                if(result.status){

                    showToast('success', 'Berhasil!', `Agenda ${entry.judul} berhasil ditambahkan.`);
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
                    showToast('error', 'Gagal!', `Terjadi kesalahan saat menambahkan data Agenda.`);
                }

               return;
            }

        // entry.id = nextId++;
        // entry.createdAt = new Date().toISOString();
        // agendaData.push(entry);
        // toast('success',status==='draft'?'Draft Disimpan':'Agenda Dipublikasikan',`"${title}" berhasil ${status==='draft'?'disimpan sebagai draft':'dipublikasikan'}`);
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

    //renderList(); updateSummary(); resetForm(); 
}

// ============ DELETE ============
window.openDeleteConfirm = function(id){
    const a = agendaData.find(x=>x.id===id);
    if(!a) return;
    document.getElementById('delId').value = a.id;
    document.getElementById('delName').textContent = a.title;
    deleteModalInst.show();
};

window.confirmDelete = function(){
    const id = parseInt(document.getElementById('delId').value);
    const a = agendaData.find(x=>x.id==id);
    if(!a) return;

        const removeData =  new MyFetch('/sms/agenda/delete/' + id, {method: 'GET', csrfHash: document.getElementById('csrf_edusmara').textContent});

        try {
            removeData.fetchData().then(result => {
                console.log('Delete Result:', result);
                if(result.status){
                    showToast('warning', 'Dihapus', `Data ${a.judul} telah dihapus permanen.`);
                    //agendaData = agendaData.filter(x => x.id !== id);
                    loadDataFromServer();
                    updateSummary();
                    renderList();
                    deleteModalInst?.hide();
                }
            });

        } catch (error) {
            console.error(error);
            showToast('warning', 'Dihapus', error.message || `Terjadi kesalahan saat menghapus ${k.judul}.`);
        }
};

// ============ HELPERS ============
function formatDateRange(start,end){
    if(!start) return '-';
    const s = new Date(start).toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
    if(!end) return s;
    const e = new Date(end).toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'});
    return `${s} s.d. ${e}`;
}

})();