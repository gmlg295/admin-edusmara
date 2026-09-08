class MyFetch {
  constructor(urls, options = {}) {
    this.urls = urls;
    this.options = {
      method: options.method || 'GET', // bisa GET atau POST
      params: options.params || null,  
      onSuccess: options.onSuccess || null,
      onError: options.onError || null,
      showLoading: options.showLoading ?? false,
      elmLoading: options.elmLoading ?? null,
      beforeSend: options.beforeSend || null,
      csrfName: options.csrfName || 'csrf_edusmara',
      csrfHash: options.csrfHash || null,
      csrfHeader: options.csrfHeader || 'X-CSRF-TOKEN'
    };

    if (this.options.showLoading && this.options.elmLoading) {
      this.elmLoader = document.querySelector(this.options.elmLoading);
      if (!this.elmLoader) {
        throw new Error('Element loading tidak ditemukan : ' + this.options.elmLoading);
      }
      this.originalLoaderHtml = this.elmLoader.innerHTML;
    }
  }

  buildQueryParams(params) {
    const esc = encodeURIComponent;
    return Object.keys(params)
      .map(k => `${esc(k)}=${esc(params[k])}`)
      .join('&');
  }

  async fetchData() {
    if (this.options.showLoading && this.elmLoader) {
      this.elmLoader.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Loading...`;
    }

    if (typeof this.options.beforeSend === 'function') {
      this.options.beforeSend();
    }

    try {
      let fetchUrl = this.urls;
      let fetchOptions = {
        method: this.options.method.toUpperCase(),
        headers: {}
      };

      // Kirim parameter
      if (this.options.params && typeof this.options.params === 'object') {
        if (fetchOptions.method === 'GET') {
          const queryString = this.buildQueryParams(this.options.params);
          fetchUrl += (fetchUrl.includes('?') ? '&' : '?') + queryString;
        } else if (fetchOptions.method === 'POST') {
             // Cek apakah params adalah FormData
          if (this.options.params instanceof FormData) {
            // Untuk FormData, JANGAN set Content-Type (biarkan browser handle)
            fetchOptions.headers[this.options.csrfHeader] = this.options.csrfHash;
            fetchOptions.headers['X-Requested-With'] = 'XMLHttpRequest';
            fetchOptions.body = this.options.params;
            // Hapus Content-Type jika ada
            delete fetchOptions.headers['Content-Type'];
          } else {
            // Untuk data biasa, tetap gunakan JSON
            fetchOptions.headers[this.options.csrfHeader] = this.options.csrfHash;
            fetchOptions.headers['Content-Type'] = 'application/json';
            fetchOptions.headers['X-Requested-With'] = 'XMLHttpRequest'; 
            fetchOptions.body = JSON.stringify(this.options.params);
          }
        }else if (fetchOptions.method === 'DELETE') {
          // Untuk DELETE, bisa kirim params sebagai query string
          fetchOptions.headers[this.options.csrfHeader] = this.options.csrfHash;
          fetchOptions.headers['Content-Type'] = 'application/json';
          fetchOptions.headers['X-Requested-With'] = 'XMLHttpRequest';
          fetchOptions.body = JSON.stringify(this.options.params);
        }else if (fetchOptions.method === 'PUT') {
          // Untuk PUT, kirim params sebagai JSON
          fetchOptions.headers[this.options.csrfHeader] = this.options.csrfHash;
          fetchOptions.headers['Content-Type'] = 'application/json';
          fetchOptions.headers['X-Requested-With'] = 'XMLHttpRequest';
          fetchOptions.body = JSON.stringify(this.options.params);
        }else {
          throw new Error('Method ' + fetchOptions.method + ' tidak didukung.');
        }
      }

      const response = await fetch(fetchUrl, fetchOptions);
      const data = await response.json();

      if (data.csrf_hash) {
        this.updateCsrf(data.csrf_hash);
      }

      if (response.ok) {
        if (typeof this.options.onSuccess === 'function') {
          this.options.onSuccess(data);
        }
        return data; // Return data untuk async/await
      } else {
        const errorData = { 
          status: response.status, 
          message: data.message || 'Request failed',
          data: data 
        };

        if (typeof this.options.onError === 'function') {
          this.options.onError(data);
        }

        throw errorData; // Throw error untuk bisa di-catch
      }

    } catch (error) {

      const errorObj = error instanceof Error 
        ? { message: 'Gagal mengirim: ' + error.message }
        : error;
      
      if (typeof this.options.onError === 'function') {
        this.options.onError(errorObj);
      }
      throw errorObj; // Re-throw untuk async/await

      // if (typeof this.options.onError === 'function') {
      //   this.options.onError({ message: 'Gagal mengirim: ' + error.message });
      // }
    } finally {
      if (this.options.showLoading && this.elmLoader) {
        this.elmLoader.innerHTML = this.originalLoaderHtml;
      }
    }
  }

  updateCsrf(csrfToken) {
    //console.log("CSRF updated:", csrfToken);
    const csrfElement = document.getElementById(this.options.csrfName);
    if (csrfElement) {
      csrfElement.textContent = csrfToken;
    }
  }
}

 
/**
 * Cara penggunaan
 * const fetcher = new MyFetch('https://api.example.com/user', {
  method: 'GET',
  showLoading: true,
  elmLoading: '#loaderArea',
  params: {
    id: 123,
    id_user: 45
  },
  onSuccess: (data) => {
    console.log('Sukses:', data);
  },
  onError: (err) => {
    console.error('Gagal:', err);
  }
});
fetcher.fetchData();


const removeData =  new MyFetch('/pengumuman/delete/' + id, {method: 'GET', csrfHash: document.getElementById('csrf_edusmara').textContent});
        try {
            removeData.fetchData().then(result => {
                console.log('Delete Result:', result);
                if(result.status){
                    console.log('Data berhasil dihapus');
                }
            });

        } catch (error) {
            console.error(error);
            
        }

 */
