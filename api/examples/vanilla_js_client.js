/**
 * WebDokter Mobile API Client
 * Vanilla JavaScript Example (Tanpa Framework)
 * Kompatibel dengan Browser & Node.js
 */

class WebDokterAPI {
  constructor(baseUrl = 'http://localhost/WebDokter/api', timeout = 30000) {
    this.baseUrl = baseUrl;
    this.timeout = timeout;
    this.headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
  }

  /**
   * Make HTTP request
   * @private
   */
  async request(endpoint, method = 'GET', data = null) {
    const url = `${this.baseUrl}${endpoint}`;
    
    try {
      const options = {
        method,
        headers: this.headers,
      };

      if (data && ['POST', 'PUT'].includes(method)) {
        options.body = JSON.stringify(data);
      }

      // Add timeout
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), this.timeout);

      const response = await fetch(url, {
        ...options,
        signal: controller.signal,
      });

      clearTimeout(timeoutId);

      if (!response.ok) {
        throw new Error(`HTTP Error: ${response.status}`);
      }

      const json = await response.json();

      if (!json.success) {
        throw new Error(json.message || 'API returned error');
      }

      return json.data;
    } catch (error) {
      console.error(`API Error [${endpoint}]:`, error);
      throw error;
    }
  }

  // ============= ORGAN ENDPOINTS =============

  async getOrgans() {
    return this.request('/organ.php');
  }

  // ============= DISEASE ENDPOINTS =============

  async getDiseases() {
    return this.request('/penyakit.php');
  }

  async getDiseasesByOrgan(organId) {
    return this.request(`/penyakit.php?organId=${organId}`);
  }

  async getDiseaseDetail(diseaseId) {
    return this.request(`/penyakit.php?id=${diseaseId}`);
  }

  // ============= DOCTOR ENDPOINTS =============

  async getDoctorProfile() {
    return this.request('/profil_dokter.php');
  }

  // ============= SCHEDULE ENDPOINTS =============

  async getSchedules() {
    return this.request('/jadwal_praktek.php');
  }

  async getScheduleDetail(scheduleId) {
    return this.request(`/jadwal_praktek.php?id=${scheduleId}`);
  }

  // ============= SERVICES ENDPOINTS =============

  async getServices() {
    return this.request('/pelayanan.php');
  }
}

// Export for Node.js atau module bundler
if (typeof module !== 'undefined' && module.exports) {
  module.exports = WebDokterAPI;
}

// ============= USAGE EXAMPLES =============

/*

// Browser usage:
const api = new WebDokterAPI();

// Get all organs
api.getOrgans()
  .then(organs => {
    console.log('Organs:', organs);
    organs.forEach(organ => {
      console.log(`- ${organ.nama}: ${organ.deskripsi}`);
    });
  })
  .catch(error => console.error('Error:', error));

// Get diseases by organ
api.getDiseasesByOrgan(1)
  .then(diseases => {
    console.log('Diseases:', diseases);
  })
  .catch(error => console.error('Error:', error));

// Get disease detail
api.getDiseaseDetail(1)
  .then(disease => {
    console.log('Disease Detail:', disease);
    console.log('Gejala:', disease.gejala);
    console.log('Cara Mencegah:', disease.cara_mencegah);
  })
  .catch(error => console.error('Error:', error));

// Get doctor profile
api.getDoctorProfile()
  .then(doctor => {
    console.log('Doctor:', doctor.nama);
    console.log('Spesialisasi:', doctor.spesialisasi);
    console.log('Riwayat Pendidikan:', doctor.riwayat_pendidikan);
  })
  .catch(error => console.error('Error:', error));

// Get schedules
api.getSchedules()
  .then(schedules => {
    console.log('Schedules:', schedules);
  })
  .catch(error => console.error('Error:', error));

// Get services
api.getServices()
  .then(services => {
    console.log('Services:', services);
  })
  .catch(error => console.error('Error:', error));

*/
