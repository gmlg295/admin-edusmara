
(function(){
'use strict';

const STORAGE='smantique_pelanggaran';
let vioData=JSON.parse(localStorage.getItem(STORAGE)||'[]');
let currentMain='terlambat', currentSub='10', currentDetailId=null;
let detailModalInst, deleteModalInst;

// Seed demo
if(vioData.length===0){
    vioData=[
        {id:1,nama:'Andi Saputra',kelas:'10',namaKelas:'10.I',jk:'L',jenis:'terlambat',tanggal:'2026-08-15',jamMasuk:'07:00',jamTiba:'07:25',alasanTerlambat:'Kemacetan',keterangan:'Terlambat 25 menit, sudah ditegur guru piket.',pencatat:'Guru Piket'},
        {id:2,nama:'Rina Marlina',kelas:'10',namaKelas:'10.II',jk:'P',jenis:'terlambat',tanggal:'2026-08-14',jamMasuk:'07:00',jamTiba:'07:15',alasanTerlambat:'Bangun kesiangan',keterangan:'Terlambat 15 menit.',pencatat:'Guru Piket'},
        {id:3,nama:'Doni Prasetyo',kelas:'11',namaKelas:'11.I',jk:'L',jenis:'bolos',tanggal:'2026-08-13',durasiBolos:'Sehari penuh',diketahuiBolos:'Wali Kelas',keterangan:'Tidak hadir tanpa kabar, orang tua dihubungi.',pencatat:'Wali Kelas'},
        {id:4,nama:'Yoga Aditya',kelas:'11',namaKelas:'11.II',jk:'L',jenis:'bolos',tanggal:'2026-08-12',durasiBolos:'2 jam pelajaran',diketahuiBolos:'Guru Mapel',keterangan:'Keluar sekolah saat jam pelajaran ke-3 dan ke-4.',pencatat:'Guru Mapel'},
        {id:5,nama:'Fajar Nugroho',kelas:'12',namaKelas:'12.I',jk:'L',jenis:'kasus',tanggal:'2026-08-10',tingkatKasus:'sedang',jenisKasus:'Merokok',sanksi:'Surat peringatan 1',keterangan:'Kedapatan merokok di belakang kantin saat jam istirahat.',pencatat:'Guru BK'},
        {id:6,nama:'Intan Permatasari',kelas:'10',namaKelas:'10.I',jk:'P',jenis:'kasus',tanggal:'2026-08-09',tingkatKasus:'ringan',jenisKasus:'Seragam tidak lengkap',sanksi:'Teguran lisan',keterangan:'Tidak memakai dasi dan atribut lengkap untuk ketiga kalinya.',pencatat:'Guru Piket'},
        {id:7,nama:'Hendra Wijaya',kelas:'12',namaKelas:'12.II',jk:'L',jenis:'kasus',tanggal:'2026-08-08',tingkatKasus:'berat',jenisKasus:'Berkelahi',sanksi:'Skorsing 3 hari',keterangan:'Terlibat perkelahian dengan siswa lain di luar gerbang sekolah setelah pulang.',pencatat:'Wakil Kesiswaan'}
    ];
    saveData();
}

// ============ INIT ============
document.addEventListener('DOMContentLoaded',()=>{
    detailModalInst=new bootstrap.Modal(document.getElementById('detailModal'));
    deleteModalInst=new bootstrap.Modal(document.getElementById('deleteModal'));
    initMainTabs();
    initSubTabs();
    initFormConditionals();
    initDynamicRombel();
    document.getElementById('inputTanggal').value=new Date().toISOString().split('T')[0];
    updateAllCounts();
    renderCurrentView();
});

// ============ MAIN TABS ============
function initMainTabs(){
    document.querySelectorAll('.main-tab').forEach(tab=>{
        tab.addEventListener('click',function(){
            currentMain=this.dataset.main;
            document.querySelectorAll('.main-tab').forEach(t=>t.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.main-content').forEach(c=>c.classList.remove('active'));
            document.getElementById(`main-${currentMain}`).classList.add('active');
            // Reset sub to first
            const firstSub=document.querySelector(`#subTabs${capitalize(currentMain)} .sub-tab`);
            if(firstSub){firstSub.click();}
        });
    });
}

// ============ SUB TABS ============
function initSubTabs(){
    document.querySelectorAll('.sub-tab').forEach(tab=>{
        tab.addEventListener('click',function(){
            const parent=this.dataset.parent;
            const sub=this.dataset.sub;
            currentSub=sub;
            // Update sub tabs in same group
            this.closest('.sub-tabs').querySelectorAll('.sub-tab').forEach(t=>t.classList.remove('active'));
            this.classList.add('active');
            // Show content
            document.querySelectorAll(`[id^="sub-${parent}-"]`).forEach(c=>c.classList.remove('active'));
            document.getElementById(`sub-${parent}-${sub}`).classList.add('active');
            renderList(parent,sub);
        });
    });
}

// ============ FORM CONDITIONALS ============
function initFormConditionals(){
    document.getElementById('inputJenis').addEventListener('change',function(){
        document.getElementById('fieldTerlambat').classList.remove('visible');
        document.getElementById('fieldBolos').classList.remove('visible');
        document.getElementById('fieldKasus').classList.remove('visible');
        if(this.value==='terlambat') document.getElementById('fieldTerlambat').classList.add('visible');
        if(this.value==='bolos') document.getElementById('fieldBolos').classList.add('visible');
        if(this.value==='kasus') document.getElementById('fieldKasus').classList.add('visible');
    });
}

function initDynamicRombel(){
    document.getElementById('inputKelas').addEventListener('change',function(){
        const sel=document.getElementById('inputNamaKelas');
        sel.innerHTML='<option value="">Pilih Rombel</option>';
        if(this.value){for(let i=1;i<=5;i++){const r=['I','II','III','IV','V'][i-1];sel.innerHTML+=`<option value="${this.value}.${r}">${this.value}.${r}</option>`;}}
    });
}

// ============ RENDER LIST ============
function renderList(type,kelas){
    const container=document.getElementById(`sub-${type}-${kelas}`);
    const items=vioData.filter(v=>v.jenis===type&&v.kelas===kelas).sort((a,b)=>new Date(b.tanggal)-new Date(a.tanggal));

    // Summary mini
    let summaryHtml='';
    if(items.length>0){
        if(type==='terlambat'){
            const total=items.length;
            const avgMin=items.filter(v=>v.jamTiba&&v.jamMasuk).reduce((acc,v)=>{return acc+diffMinutes(v.jamMasuk,v.jamTiba);},0)/(items.filter(v=>v.jamTiba).length||1);
            summaryHtml=`<div class="summary-mini"><div class="summary-mini-card warna-accent"><div class="val">${total}</div><div class="lbl">Total Terlambat</div></div><div class="summary-mini-card warna-blue"><div class="val">${Math.round(avgMin)}</div><div class="lbl">Rata-rata (menit)</div></div><div class="summary-mini-card warna-red"><div class="val">${items.filter(v=>diffMinutes(v.jamMasuk||'07:00',v.jamTiba||'07:00')>30).length}</div><div class="lbl">&gt; 30 Menit</div></div></div>`;
        } else if(type==='bolos'){
            summaryHtml=`<div class="summary-mini"><div class="summary-mini-card warna-blue"><div class="val">${items.length}</div><div class="lbl">Total Bolos</div></div><div class="summary-mini-card warna-accent"><div class="val">${items.filter(v=>v.durasiBolos==='Sehari penuh'||v.durasiBolos==='Lebih dari 1 hari').length}</div><div class="lbl">Full Day</div></div><div class="summary-mini-card warna-red"><div class="val">${new Set(items.map(v=>v.nama)).size}</div><div class="lbl">Siswa Unik</div></div></div>`;
        } else {
            summaryHtml=`<div class="summary-mini"><div class="summary-mini-card warna-accent"><div class="val">${items.filter(v=>v.tingkatKasus==='ringan').length}</div><div class="lbl">Ringan</div></div><div class="summary-mini-card warna-blue"><div class="val">${items.filter(v=>v.tingkatKasus==='sedang').length}</div><div class="lbl">Sedang</div></div><div class="summary-mini-card warna-red"><div class="val">${items.filter(v=>v.tingkatKasus==='berat').length}</div><div class="lbl">Berat</div></div></div>`;
        }
    }

    if(items.length===0){
        container.innerHTML=`<div class="empty-state"><i class="fa-solid fa-check-circle"></i><h4>Tidak Ada Pelanggaran</h4><p>Belum ada catatan pelanggaran ${getLabel(type)} untuk kelas ${kelas}</p></div>
        <div style="text-align:center;margin-top:12px;"><button class="btn-add" onclick="openAddForm('${type}','${kelas}')"><i class="fa-solid fa-plus"></i> Catat Pelanggaran</button></div>`;
        return;
    }

    const listHtml=items.map((v,idx)=>{
        const initials=v.nama.split(' ').map(n=>n[0]).join('').substring(0,2);
        const avClass=v.jk==='L'?'av-m':'av-f';
        let badgeClass='',badgeText='';
        if(type==='terlambat'){badgeClass='terlambat';badgeText=`⏰ ${diffMinutes(v.jamMasuk||'07:00',v.jamTiba||'07:00')} mnt`;}
        else if(type==='bolos'){badgeClass='bolos';badgeText=`🚪 ${v.durasiBolos||'-'}`;}
        else{badgeClass=`kasus-${v.tingkatKasus||'ringan'}`;badgeText=`⚖️ ${(v.tingkatKasus||'').toUpperCase()} – ${v.jenisKasus||'-'}`;}

        let extraMeta='';
        if(type==='terlambat') extraMeta=`<span><i class="fa-regular fa-clock"></i> ${v.jamTiba||'-'}</span>`;
        else if(type==='bolos') extraMeta=`<span><i class="fa-solid fa-hourglass-half"></i> ${v.durasiBolos||'-'}</span>`;
        else extraMeta=`<span><i class="fa-solid fa-gavel"></i> ${v.sanksi||'-'}</span>`;

        return `<div class="vio-card type-${type}" style="animation-delay:${idx*0.04}s" onclick="showDetail(${v.id})">
            <div class="vio-avatar ${avClass}">${initials}</div>
            <div class="vio-body">
                <div class="vio-top"><span class="vio-badge ${badgeClass}">${badgeText}</span></div>
                <div class="vio-name">${v.nama} • ${v.namaKelas}</div>
                <div class="vio-detail">${v.keterangan}</div>
                <div class="vio-meta">
                    <span><i class="fa-regular fa-calendar"></i> ${fmtDate(v.tanggal)}</span>
                    ${extraMeta}
                    <span><i class="fa-solid fa-user-pen"></i> ${v.pencatat||'-'}</span>
                </div>
            </div>
            <div class="vio-actions" onclick="event.stopPropagation()">
                <button class="act-btn view" onclick="showDetail(${v.id})"><i class="fa-solid fa-eye"></i></button>
                <button class="act-btn edit" onclick="openEditForm(${v.id})"><i class="fa-solid fa-pen"></i></button>
                <button class="act-btn delete" onclick="openDeleteDirect(${v.id})"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>`;
    }).join('');

    container.innerHTML=summaryHtml+`<div class="toolbar">
            <div class="search-box">
                <div class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <input type="search" class="search-input" id="searchGuru" placeholder="Cari nama atau NIP/NUPTK...">
            </div>
            <select class="filter-select" id="filterStatus">
                <option value="all">Semua Status</option>
                <option value="PNS">PNS</option>
                <option value="PPPK">PPPK / P3K</option>
                <option value="Honorer">Honorer</option>
                <option value="GTT">GTT</option>
            </select>
            <select class="filter-select" id="filterJabatan">
                <option value="all">Semua Jabatan</option>
                <option value="Kepala Sekolah">Kepala Sekolah</option>
                <option value="Wakil Kepala Sekolah">Wakil Kepala Sekolah</option>
                <option value="Guru Mata Pelajaran">Guru Mata Pelajaran</option>
                <option value="Guru BK">Guru BK</option>
                <option value="Staff TU">Staff TU</option>
            </select>
            <button class="btn-add" onclick="openAddForm('${type}','${kelas}')"><i class="fa-solid fa-plus"></i><span>Tambah Pelanggaran</span></button>
        </div>
        <div class="violation-list">${listHtml}</div>`;


    // container.innerHTML=summaryHtml+`<div class="toolbar"><div class="search-box"><i class="fa-solid fa-magnifying-glass search-icon"></i><input type="search" class="search-input" placeholder="Cari nama siswa..." oninput="filterLocal(this,'sub-${type}-${kelas}')"></div><button class="btn-add" onclick="openAddForm('${type}','${kelas}')"><i class="fa-solid fa-plus"></i> Catat</button></div><div class="violation-list">${listHtml}</div>`;
}

window.filterLocal=function(input,containerId){
    const q=input.value.toLowerCase();
    document.querySelectorAll(`#${containerId} .vio-card`).forEach(card=>{
        const name=card.querySelector('.vio-name').textContent.toLowerCase();
        card.style.display=name.includes(q)?'flex':'none';
    });
};

function renderCurrentView(){renderList(currentMain,currentSub);}

// ============ ADD FORM ============
window.openAddForm=function(presetType,presetKelas){
    document.querySelectorAll('.main-content').forEach(c=>c.classList.remove('active'));
    document.getElementById('main-form').classList.add('active');
    resetForm();
    if(presetType){document.getElementById('inputJenis').value=presetType;document.getElementById('inputJenis').dispatchEvent(new Event('change'));}
    if(presetKelas){document.getElementById('inputKelas').value=presetKelas;document.getElementById('inputKelas').dispatchEvent(new Event('change'));}
};

window.openEditForm=function(id){
    const v=vioData.find(x=>x.id===id);if(!v)return;
    document.querySelectorAll('.main-content').forEach(c=>c.classList.remove('active'));
    document.getElementById('main-form').classList.add('active');
    document.getElementById('editId').value=v.id;
    document.getElementById('formTitle').innerHTML='<i class="fa-solid fa-pen-to-square"></i> Ubah Data Pelanggaran';
    document.getElementById('formSubtitle').textContent=`Mengedit data ${v.nama}`;
    document.getElementById('inputNama').value=v.nama;
    document.getElementById('inputKelas').value=v.kelas;
    document.getElementById('inputKelas').dispatchEvent(new Event('change'));
    setTimeout(()=>{document.getElementById('inputNamaKelas').value=v.namaKelas;},50);
    document.getElementById('inputGender').value=v.jk;
    document.getElementById('inputJenis').value=v.jenis;
    document.getElementById('inputJenis').dispatchEvent(new Event('change'));
    document.getElementById('inputTanggal').value=v.tanggal;
    document.getElementById('inputKeterangan').value=v.keterangan;
    document.getElementById('inputPencatat').value=v.pencatat||'';
    if(v.jenis==='terlambat'){document.getElementById('inputJamMasuk').value=v.jamMasuk||'07:00';document.getElementById('inputJamTiba').value=v.jamTiba||'';document.getElementById('inputAlasanTerlambat').value=v.alasanTerlambat||'';}
    if(v.jenis==='bolos'){document.getElementById('inputDurasiBolos').value=v.durasiBolos||'Sehari penuh';document.getElementById('inputDiketahuiBolos').value=v.diketahuiBolos||'';}
    if(v.jenis==='kasus'){document.getElementById('inputTingkatKasus').value=v.tingkatKasus||'';document.getElementById('inputJenisKasus').value=v.jenisKasus||'';document.getElementById('inputSanksi').value=v.sanksi||'';}
};

window.resetForm=function(){
    document.getElementById('vioForm').reset();
    document.getElementById('editId').value='';
    document.getElementById('formTitle').innerHTML='<i class="fa-solid fa-circle-exclamation"></i> Catat Pelanggaran Baru';
    document.getElementById('formSubtitle').textContent='Lengkapi data pelanggaran disiplin siswa';
    document.getElementById('inputTanggal').value=new Date().toISOString().split('T')[0];
    document.getElementById('inputNamaKelas').innerHTML='<option value="">Pilih Rombel</option>';
    document.querySelectorAll('.conditional-field').forEach(f=>f.classList.remove('visible'));
};

document.getElementById('vioForm').addEventListener('submit',function(e){
    e.preventDefault();
    const editId=document.getElementById('editId').value;
    const jenis=document.getElementById('inputJenis').value;
    const data={
        nama:document.getElementById('inputNama').value.trim(),
        kelas:document.getElementById('inputKelas').value,
        namaKelas:document.getElementById('inputNamaKelas').value,
        jk:document.getElementById('inputGender').value,
        jenis:jenis,
        tanggal:document.getElementById('inputTanggal').value,
        keterangan:document.getElementById('inputKeterangan').value.trim(),
        pencatat:document.getElementById('inputPencatat').value.trim()
    };
    if(jenis==='terlambat'){data.jamMasuk=document.getElementById('inputJamMasuk').value;data.jamTiba=document.getElementById('inputJamTiba').value;data.alasanTerlambat=document.getElementById('inputAlasanTerlambat').value;}
    if(jenis==='bolos'){data.durasiBolos=document.getElementById('inputDurasiBolos').value;data.diketahuiBolos=document.getElementById('inputDiketahuiBolos').value.trim();}
    if(jenis==='kasus'){data.tingkatKasus=document.getElementById('inputTingkatKasus').value;data.jenisKasus=document.getElementById('inputJenisKasus').value;data.sanksi=document.getElementById('inputSanksi').value;}

    if(!data.nama||!data.kelas||!data.namaKelas||!data.jenis||!data.tanggal||!data.keterangan||!data.pencatat){showToast('error','Form Belum Lengkap','Harap isi semua field wajib');return;}

    if(editId){
        const idx=vioData.findIndex(v=>v.id===parseInt(editId));
        if(idx>-1)vioData[idx]={...vioData[idx],...data};
        showToast('success','Diperbarui!',`Data pelanggaran ${data.nama} berhasil diperbarui`);
    }else{
        data.id=Date.now();
        vioData.push(data);
        showToast('success','Tersimpan!',`Pelanggaran ${getLabel(jenis)} untuk ${data.nama} telah dicatat`);
    }
    saveData();updateAllCounts();resetForm();
    // Navigate back
    currentMain=data.jenis;currentSub=data.kelas;
    document.querySelectorAll('.main-tab').forEach(t=>{t.classList.toggle('active',t.dataset.main===currentMain);});
    document.querySelectorAll('.main-content').forEach(c=>c.classList.remove('active'));
    document.getElementById(`main-${currentMain}`).classList.add('active');
    // Activate correct sub tab
    const subTab=document.querySelector(`.sub-tab[data-parent="${currentMain}"][data-sub="${currentSub}"]`);
    if(subTab){subTab.closest('.sub-tabs').querySelectorAll('.sub-tab').forEach(t=>t.classList.remove('active'));subTab.classList.add('active');}
    document.querySelectorAll(`[id^="sub-${currentMain}-"]`).forEach(c=>c.classList.remove('active'));
    const subPanel=document.getElementById(`sub-${currentMain}-${currentSub}`);
    if(subPanel)subPanel.classList.add('active');
    renderList(currentMain,currentSub);
});

// ============ DETAIL MODAL ============
window.showDetail=function(id){
    const v=vioData.find(x=>x.id===id);if(!v)return;
    currentDetailId=id;
    const labels={terlambat:'Terlambat',bolos:'Bolos Sekolah',kasus:'Kasus'};
    let badgeClass='',badgeText='';
    if(v.jenis==='terlambat'){badgeClass='terlambat';badgeText=`⏰ Terlambat ${diffMinutes(v.jamMasuk||'07:00',v.jamTiba||'07:00')} menit`;}
    else if(v.jenis==='bolos'){badgeClass='bolos';badgeText=`🚪 Bolos – ${v.durasiBolos||'-'}`;}
    else{badgeClass=`kasus-${v.tingkatKasus||'ringan'}`;badgeText=`⚖️ ${(v.tingkatKasus||'').toUpperCase()} – ${v.jenisKasus||'-'}`;}

    document.getElementById('mBadge').innerHTML=`<span class="vio-badge ${badgeClass}" style="font-size:11px;padding:4px 12px;">${badgeText}</span>`;
    document.getElementById('mTitle').textContent=labels[v.jenis]||v.jenis;
    document.getElementById('mStudent').textContent=`${v.nama} • ${v.namaKelas} • ${v.jk==='L'?'Laki-laki':'Perempuan'}`;
    document.getElementById('mJenis').textContent=labels[v.jenis]||'-';
    document.getElementById('mTanggal').textContent=fmtDateLong(v.tanggal);
    document.getElementById('mKelas').textContent=v.namaKelas;
    document.getElementById('mPencatat').textContent=v.pencatat||'-';
    document.getElementById('mKeterangan').textContent=v.keterangan;

    // Extra field
    const ef=document.getElementById('mExtraField');
    ef.classList.remove('visible');
    if(v.jenis==='terlambat'&&v.jamTiba){ef.classList.add('visible');document.getElementById('mExtraLabel').textContent='Jam Tiba / Alasan';document.getElementById('mExtraValue').textContent=`${v.jamTiba} WIB – ${v.alasanTerlambat||'-'}`;}
    else if(v.jenis==='bolos'){ef.classList.add('visible');document.getElementById('mExtraLabel').textContent='Durasi / Diketahui Oleh';document.getElementById('mExtraValue').textContent=`${v.durasiBolos||'-'} – ${v.diketahuiBolos||'-'}`;}
    else if(v.jenis==='kasus'){ef.classList.add('visible');document.getElementById('mExtraLabel').textContent='Jenis Kasus / Sanksi';document.getElementById('mExtraValue').textContent=`${v.jenisKasus||'-'} → ${v.sanksi||'-'}`;}

    detailModalInst.show();
};

window.openEditFromModal=function(){detailModalInst.hide();setTimeout(()=>openEditForm(currentDetailId),300);};

// ============ DELETE ============
window.openDeleteConfirm=function(){
    const v=vioData.find(x=>x.id===currentDetailId);if(!v)return;
    detailModalInst.hide();
    document.getElementById('deleteId').value=v.id;
    document.getElementById('deleteName').textContent=`${v.nama} (${getLabel(v.jenis)})`;
    setTimeout(()=>deleteModalInst.show(),300);
};
window.openDeleteDirect=function(id){
    const v=vioData.find(x=>x.id===id);if(!v)return;
    document.getElementById('deleteId').value=v.id;
    document.getElementById('deleteName').textContent=`${v.nama} (${getLabel(v.jenis)})`;
    deleteModalInst.show();
};
window.confirmDelete=function(){
    const id=parseInt(document.getElementById('deleteId').value);
    const v=vioData.find(x=>x.id===id);if(!v)return;
    vioData=vioData.filter(x=>x.id!==id);
    saveData();updateAllCounts();renderList(currentMain,currentSub);
    deleteModalInst.hide();
    showToast('warning','Dihapus',`Data pelanggaran ${v.nama} telah dihapus`);
};

// ============ COUNTS ============
function updateAllCounts(){
    ['terlambat','bolos','kasus'].forEach(type=>{
        document.getElementById(`countMain${capitalize(type)}`).textContent=vioData.filter(v=>v.jenis===type).length;
        ['10','11','12'].forEach(k=>{
            const el=document.getElementById(`subCount-${type}-${k}`);
            if(el)el.textContent=vioData.filter(v=>v.jenis===type&&v.kelas===k).length;
        });
    });
}

// ============ HELPERS ============
function saveData(){localStorage.setItem(STORAGE,JSON.stringify(vioData));}
function capitalize(s){return s.charAt(0).toUpperCase()+s.slice(1);}
function getLabel(t){return{terlambat:'Terlambat',bolos:'Bolos',kasus:'Kasus'}[t]||t;}
function fmtDate(d){return d?new Date(d).toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'}):'-';}
function fmtDateLong(d){return d?new Date(d).toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'}):'-';}
function diffMinutes(start,end){
    if(!start||!end)return 0;
    const [sh,sm]=start.split(':').map(Number);
    const [eh,em]=end.split(':').map(Number);
    return Math.max(0,(eh*60+em)-(sh*60+sm));
}

// ============ TOAST ============
window.showToast=function(type,title,msg){
    const c=document.getElementById('toastContainer');
    const icons={success:'fa-circle-check',info:'fa-circle-info',warning:'fa-triangle-exclamation',error:'fa-circle-xmark'};
    const t=document.createElement('div');t.className=`toast-custom ${type}`;
    t.innerHTML=`<i class="fa-solid ${icons[type]}"></i><div class="toast-content"><div class="toast-title">${title}</div><div class="toast-msg">${msg}</div></div><button class="toast-close"><i class="fa-solid fa-xmark"></i></button>`;
    c.appendChild(t);
    t.querySelector('.toast-close').addEventListener('click',()=>{t.classList.add('hiding');setTimeout(()=>t.remove(),300);});
    setTimeout(()=>{if(t.parentNode){t.classList.add('hiding');setTimeout(()=>t.remove(),300);}},3500);
};

})();