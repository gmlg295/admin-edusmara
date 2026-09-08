 let importedData = [];
        let validationInfo = null;

        // Setup drag and drop
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFile(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        function handleFile(file) {
            // Validasi ukuran file (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                showAlert('error', 'Ukuran file terlalu besar! Maksimal 5MB.');
                return;
            }

            // Validasi ekstensi
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['xlsx', 'xls'].includes(ext)) {
                showAlert('error', 'Format file harus .xlsx atau .xls');
                return;
            }

            // Upload file
            uploadFile(file);
        }

      

        async function uploadFile(file) {
            showLoading(true);
            hideAlerts();
            
            const formData = new FormData();
            formData.append('excel_file', file);
            const submitData =  new MyFetch('/siswa/upload', 
                        {
                            method: 'POST',
                            csrfHash: document.getElementById('csrf_edusmara').textContent,
                            params : formData
                        }
                    );

            try {

                const result = await submitData.fetchData();
                if(result.status === 'success'){
                    showToast('success', 'Berhasil!', `${result.message}.`);
                    importedData = result.data;
                    validationInfo = result.validation;
                    
                    displayPreview(result.data, result.validation);
                    //showAlert('success', result.message);
                } else {
                    showAlert('error', result.message);
                }
            } catch (error) {
                showAlert('error', 'Terjadi kesalahan saat upload: ' + error.message);
            } finally {
                showLoading(false);
            }
        }

        function displayPreview(data, validation) {
            const previewSection = document.getElementById('previewSection');
            const tableBody = document.getElementById('tableBody');
            const totalRows = document.getElementById('totalRows');
            const validationStatus = document.getElementById('validationStatus');
            const btnSave = document.getElementById('btnSave');

            // Clear table
            tableBody.innerHTML = '';

            // Display data
            data.forEach((record, index) => {
                const row = document.createElement('tr');
                const hasErrors = record.errors && record.errors.length > 0;
                
                if (hasErrors) {
                    row.classList.add('has-error');
                }

                // No
                const tdNo = document.createElement('td');
                tdNo.textContent = index + 1;
                row.appendChild(tdNo);

                // Data columns
                const columns = [
                    'nisn', 'nis', 'nama_lengkap', 'jenis_kelamin',
                    'tempat_lahir', 'tanggal_lahir', 'agama', 'nomor_hp',
                    'email', 'alamat_lengkap', 'nama_ayah', 'nama_ibu',
                    'pekerjaan_ayah', 'pekerjaan_ibu', 'no_hp_wali',
                    'hubungan_wali', 'tanggal_masuk', 'jalur_masuk', 'status_siswa'
                ];

                columns.forEach(col => {
                    const td = document.createElement('td');
                    const value = record.data[col] || '-';
                    td.textContent = value;

                    // Highlight error cells
                    if (hasErrors && record.errors.includes(col)) {
                        td.classList.add('error-cell');
                    }

                    row.appendChild(td);
                });

                tableBody.appendChild(row);
            });

            // Update info
            totalRows.textContent = data.length;
            
            if (validation.has_errors) {
                validationStatus.innerHTML = '<span style="color: #e53e3e;">❌ Ada Error Validasi</span>';
                btnSave.disabled = true;
                
                const errorFields = Object.values(validation.required_labels).join(', ');
                showAlert('warning', 
                    `Terdapat ${data.filter(r => r.errors.length > 0).length} baris dengan data tidak lengkap. ` +
                    `Kolom wajib: ${errorFields}`
                );
            } else {
                validationStatus.innerHTML = '<span style="color: #38a169;">✅ Validasi Berhasil</span>';
                btnSave.disabled = false;
            }

            previewSection.classList.add('active');
        }

        async function saveData() {
            if (!importedData || importedData.length === 0) {
                showAlert('error', 'Tidak ada data untuk disimpan');
                return;
            }

            if (!confirm(`Yakin ingin menyimpan ${importedData.length} data siswa?`)) {
                return;
            }

            showLoading(true);
            hideAlerts();

            // Bersihkan data sebelum dikirim
            const cleanData = importedData.map(item => {
                return {
                    row_number: item.row_number,
                    errors: item.errors || [],
                    data: item.data // Pastikan ini plain object
                };
            });


            const saveData =  new MyFetch('/siswa/saveFile', 
                        {
                            method: 'POST',
                            csrfHash: document.getElementById('csrf_edusmara').textContent,
                            params : {data: cleanData}, // Menggunakan cleanData
                        }
                    );

            try {
                console.log('Data yang dikirim:', cleanData);
                const result = await saveData.fetchData();

                if (result.status === 'success') {
                    showAlert('success', result.message);
                    
                    // Reset after success
                    setTimeout(() => {
                        window.location.href = '/siswa';
                    }, 3000);
                } else {
                    showAlert('error', result.message);
                    if (result.errors && result.errors.length > 0) {
                        console.error('Detail errors:', result.errors);
                    }
                }
            } catch (error) {
                showAlert('error', 'Terjadi kesalahan saat menyimpan: ' + error.message);
            } finally {
                showLoading(false);
            }
        }

        function resetForm() {
            importedData = [];
            validationInfo = null;
            
            document.getElementById('previewSection').classList.remove('active');
            document.getElementById('fileInput').value = '';
            document.getElementById('tableBody').innerHTML = '';
            
            hideAlerts();
        }

        function downloadTemplate() {
            // Buat template Excel sederhana
            const template = [
                ['NISN', 'NIS', 'Nama Lengkap', 'Jenis Kelamin', 'Tempat Lahir', 
                 'Tanggal Lahir', 'Agama', 'No HP', 'Email', 'Alamat',
                 'Nama Ayah', 'Nama Ibu', 'Pekerjaan Ayah', 'Pekerjaan Ibu',
                 'No HP Wali', 'Hubungan Wali', 'Tanggal Masuk', 'Jalur Masuk', 'Status'],
                ['1234567890', '12345', 'Budi Santoso', 'L', 'Jakarta', 
                 '15/01/2010', 'Islam', '08123456789', 'budi@email.com', 'Jl. Merdeka No. 1',
                 'Ahmad Santoso', 'Siti Aminah', 'Wiraswasta', 'Ibu Rumah Tangga',
                 '08123456789', 'Orang Tua Kandung', '01/07/2023', 'Zonasi', 'aktif']
            ];

            // Convert to CSV untuk download sederhana
            let csv = template.map(row => row.join(',')).join('\n');
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'template_import_siswa.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function showLoading(show) {
            document.getElementById('loading').classList.toggle('active', show);
        }

        function showAlert(type, message) {
            hideAlerts();
            const alertId = 'alert' + type.charAt(0).toUpperCase() + type.slice(1);
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.textContent = message;
                alert.classList.add('active');
            }
        }

        function hideAlerts() {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.classList.remove('active');
            });
        }