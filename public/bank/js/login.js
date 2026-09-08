(function() {
    'use strict';

    // ============ STATE ============
    const state = {
        captchaAnswer: 0,
        failedAttempts: parseInt(sessionStorage.getItem('edusmara_failed') || '0'),
        lockoutUntil: parseInt(sessionStorage.getItem('edusmara_lockout') || '0'),
        lockoutInterval: null,
        maxAttempts: 5,
        lockoutDuration: 5 * 60 * 1000 // 30 menit dalam ms
    };

    const DEVICE_ID_KEY = 'app_device_id_v1';

    async function saveDeviceLog() {
        console.log('Saving device log...');
        const payload = {
            device_id: localStorage.getItem('DEVICE_HASH'),
            ip_addr: localStorage.getItem('IP_CLIENT'),
            platform: navigator.userAgentData?.platform ?? navigator.platform ?? '',
            language: navigator.language ?? '',
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone ?? '',
            screen_width: window.screen.width,
            screen_height: window.screen.height,
        };
        const apis =  new MyFetch('/auth/device-logs', 
            {
                method: 'POST',
                csrfHash: document.getElementById('csrf_edusmara').textContent,
                params : payload
            }
        );


        try {
            const result = await apis.fetchData();
                if(result.ok){
                    console.log('Device log saved successfully.');
                };
                if (!result.ok) throw new Error(`Device log gagal: ${result.status}`);
            
        } catch (error) {
         
            console.error(error);
        }

    }

    // ============ INIT ============
    document.addEventListener('DOMContentLoaded', () => {
        generateCaptcha();
        checkLockout();
        initTogglePassword();
        initFormValidation();
        getDeviceConf();
    });

    // ============ CAPTCHA SYSTEM ============
    function generateCaptcha() {
        const operators = ['+', '-', '×'];
        const operator = operators[Math.floor(Math.random() * operators.length)];
        let a, b, answer;

        switch (operator) {
            case '+':
                a = Math.floor(Math.random() * 20) + 1;
                b = Math.floor(Math.random() * 20) + 1;
                answer = a + b;
                break;
            case '-':
                a = Math.floor(Math.random() * 20) + 5;
                b = Math.floor(Math.random() * a); // Pastikan hasil tidak negatif
                answer = a - b;
                break;
            case '×':
                a = Math.floor(Math.random() * 9) + 1;
                b = Math.floor(Math.random() * 9) + 1;
                answer = a * b;
                break;
        }

        state.captchaAnswer = answer;
        document.getElementById('captchaText').textContent = `${a} ${operator} ${b}= ?`;

        // Reset input
        document.getElementById('captchaInput').value = '';
        clearFieldError('captcha');
    }

    document.getElementById('refreshCaptcha').addEventListener('click', () => {
        const btn = document.getElementById('refreshCaptcha');
        btn.style.transform = 'rotate(360deg)';
        setTimeout(() => btn.style.transform = '', 300);
        generateCaptcha();
    });

    // ============ TOGGLE PASSWORD ============
    function initTogglePassword() {
        const toggle = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        toggle.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggle.querySelector('i').className = isPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        });
    }

    // ============ LOCKOUT SYSTEM ============
    function checkLockout() {
        const now = Date.now();
        if (state.lockoutUntil > now) {
            activateLockout(state.lockoutUntil);
        } else {
            // Lockout expired, reset
            sessionStorage.removeItem('edusmara_lockout');
            sessionStorage.removeItem('edusmara_failed');
            state.failedAttempts = 0;
            state.lockoutUntil = 0;
        }
    }

    function activateLockout(untilTimestamp) {
        const banner = document.getElementById('lockoutBanner');
        const timerEl = document.getElementById('lockoutTimer');
        const btnLogin = document.getElementById('btnLogin');
        const inputs = document.querySelectorAll('.form-input');

        banner.classList.add('visible');
        btnLogin.disabled = true;
        inputs.forEach(input => input.disabled = true);

        function updateTimer() {
            const remaining = Math.max(0, untilTimestamp - Date.now());
            if (remaining <= 0) {
                clearInterval(state.lockoutInterval);
                banner.classList.remove('visible');
                btnLogin.disabled = false;
                inputs.forEach(input => input.disabled = false);
                sessionStorage.removeItem('edusmara_lockout');
                sessionStorage.removeItem('edusmara_failed');
                state.failedAttempts = 0;
                state.lockoutUntil = 0;
                generateCaptcha();
                showToast('success', 'Akun Terbuka', 'Silakan coba login kembali');
                return;
            }
            const mins = Math.floor(remaining / 60000);
            const secs = Math.floor((remaining % 60000) / 1000);
            timerEl.textContent = `${String(mins).padStart(2,'0')}:${String(secs).padStart(2,'0')}`;
        }

        updateTimer();
        state.lockoutInterval = setInterval(updateTimer, 1000);
    }

    function triggerLockout() {
        const until = Date.now() + state.lockoutDuration;
        saveDeviceLog();

        state.lockoutUntil = until;
        sessionStorage.setItem('edusmara_lockout', String(until));
        activateLockout(until);
        showToast('error', 'Akun Terkunci', `Terlalu banyak percobaan gagal. Akun dikunci selama 15 menit.`);
    }

    function recordFailedAttempt() {
        state.failedAttempts++;
        sessionStorage.setItem('edusmara_failed', String(state.failedAttempts));

        if (state.failedAttempts >= state.maxAttempts) {
            triggerLockout();
        } 
        
        // else {
        //     const remaining = state.maxAttempts - state.failedAttempts;
        //     showToast('warning', 'Login Gagal', `Username atau password salah. Sisa ${remaining} percobaan.`);
        // }
    }

    function resetFailedAttempts() {
        state.failedAttempts = 0;
        sessionStorage.removeItem('edusmara_failed');
        sessionStorage.removeItem('edusmara_lockout');
    }

    // ============ FORM VALIDATION ============
    function initFormValidation() {
        const form = document.getElementById('loginForm');

        // Real-time validation on blur
        document.getElementById('username').addEventListener('blur', () => validateUsername());
        document.getElementById('password').addEventListener('blur', () => validatePassword());
        document.getElementById('captchaInput').addEventListener('blur', () => validateCaptcha());

        // Clear error on focus
        ['username', 'password', 'captchaInput'].forEach(id => {
            document.getElementById(id).addEventListener('focus', () => {
                document.getElementById(id).classList.remove('error');
            });
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Check lockout first
            if (state.lockoutUntil > Date.now()) return;

            // Validate all fields
            const isUsernameValid = validateUsername();
            const isPasswordValid = validatePassword();
            const isCaptchaValid = validateCaptcha();

            if (!isUsernameValid || !isPasswordValid || !isCaptchaValid) {
                showToast('error', 'Form Belum Lengkap', 'Harap perbaiki field yang ditandai merah');
                return;
            }

            // Simulate login request
            await performLogin();
        });
    }

    function validateUsername() {
        const val = document.getElementById('username').value.trim();
        if (!val) {
            showFieldError('username', 'Username wajib diisi');
            return false;
        }
        if (val.length < 3) {
            showFieldError('username', 'Username minimal 3 karakter');
            return false;
        }
        clearFieldError('username');
        return true;
    }

    function validatePassword() {
        const val = document.getElementById('password').value;
        if (!val) {
            showFieldError('password', 'Password wajib diisi');
            return false;
        }
        if (val.length < 6) {
            showFieldError('password', 'Password minimal 6 karakter');
            return false;
        }
        clearFieldError('password');
        return true;
    }

    function validateCaptcha() {
        const val = document.getElementById('captchaInput').value.trim();
        if (!val) {
            showFieldError('captcha', 'Jawaban captcha wajib diisi');
            return false;
        }
        if (parseInt(val) !== state.captchaAnswer) {
            showFieldError('captcha', 'Jawaban captcha salah');
            return false;
        }
        clearFieldError('captcha');
        return true;
    }

    function showFieldError(field, message) {
        const input = document.getElementById(field === 'captcha' ? 'captchaInput' : field);
        const errorEl = document.getElementById(`error${field.charAt(0).toUpperCase() + field.slice(1)}`);
        input.classList.add('error');
        errorEl.querySelector('span').textContent = message;
        errorEl.classList.add('visible');
    }

    function clearFieldError(field) {
        const input = document.getElementById(field === 'captcha' ? 'captchaInput' : field);
        const errorEl = document.getElementById(`error${field.charAt(0).toUpperCase() + field.slice(1)}`);
        input.classList.remove('error');
        errorEl.classList.remove('visible');
    }

    // ============ LOGIN SIMULATION ============
    async function performLogin() {
        const btn = document.getElementById('btnLogin');
        btn.classList.add('loading');
        btn.disabled = true;

        // Simulate network delay
       // await new Promise(resolve => setTimeout(resolve, 1800));
        const username = document.getElementById('username').value.trim();
        const api =  new MyFetch('/auth/login', 
            {
                method: 'POST',
                csrfHash: document.getElementById('csrf_edusmara').textContent,
                params : {
                    'username' : username,
                    'password' : document.getElementById('password').value
                }
            }
        );


        try {
            const result = await api.fetchData();
                if(result.lockout) {
                    triggerLockout();
                }

                if (result.success && result.lockout === false) {
                    showToast('success', 'Login Berhasil!', `Selamat datang, ${username}! Mengalihkan ke dashboard...`);

                    resetFailedAttempts();
                    btn.classList.remove('loading');
                    setTimeout(() => {
                        window.location.href = '/home';
                        btn.disabled = false;
                    }, 2000);

                }else{
                    btn.classList.remove('loading');
                    btn.disabled = false;
                    recordFailedAttempt();
                    generateCaptcha();
                    showToast('error', 'Login Gagal', result.message);
                    
                }
        } catch (error) {
            btn.classList.remove('loading');
            btn.disabled = false;
            recordFailedAttempt();
            generateCaptcha();
            showToast('error', 'Login Gagal', 'Error: ' + error.message);
            //console.error(error);
        }

    }

    // ============ TOAST ============
    window.showToast = function(type, title, msg) {
        const container = document.getElementById('toastContainer');
        const icons = { success: 'fa-circle-check', info: 'fa-circle-info', warning: 'fa-triangle-exclamation', error: 'fa-circle-xmark' };
        const toast = document.createElement('div');
        toast.className = `toast-custom ${type}`;
        toast.innerHTML = `
            <i class="fa-solid ${icons[type]}"></i>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-msg">${msg}</div>
            </div>
            <button class="toast-close"><i class="fa-solid fa-xmark"></i></button>
        `;
        container.appendChild(toast);
        toast.querySelector('.toast-close').addEventListener('click', () => removeToast(toast));
        setTimeout(() => { if (toast.parentNode) removeToast(toast); }, 4000);
    };

    function removeToast(toast) {
        if (!toast || !toast.parentNode) return;
        toast.classList.add('hiding');
        setTimeout(() => toast.remove(), 300);
    }

    function getDeviceConf() {
        const fetcher = new MyFetch('/auth/device-conf', {
            method: 'GET',
            onSuccess: (response) => {
                //console.log('Sukses:', response.data);
                localStorage.setItem('DEVICE_HASH', response.data.device_hash);
                localStorage.setItem('IP_CLIENT', response.data.ip_address);
                localStorage.setItem('TIMESTAMP', Date.now());
            },
            onError: (err) => {
                console.error('Gagal:', err);
            }
        });

        fetcher.fetchData();
        return true;
    }

})();