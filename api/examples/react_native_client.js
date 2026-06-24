/**
 * WebDokter Mobile API Client
 * Example untuk React Native
 * 
 * Installation:
 * npm install axios
 */

import axios from 'axios';

const API_BASE_URL = 'http://localhost/WebDokter/api';
const API_TIMEOUT = 30000;

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: API_TIMEOUT,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Interceptor for error handling
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    console.log('API Error:', error.message);
    throw error;
  }
);

class WebDokterAPI {
  // ============= ORGAN ENDPOINTS =============

  static async getOrgans() {
    try {
      const response = await apiClient.get('/organ.php');
      if (response.data.success) {
        return response.data.data;
      }
      throw new Error(response.data.message);
    } catch (error) {
      console.error('Error fetching organs:', error);
      throw error;
    }
  }

  // ============= DISEASE ENDPOINTS =============

  static async getDiseases() {
    try {
      const response = await apiClient.get('/penyakit.php');
      if (response.data.success) {
        return response.data.data;
      }
      throw new Error(response.data.message);
    } catch (error) {
      console.error('Error fetching diseases:', error);
      throw error;
    }
  }

  static async getDiseasesByOrgan(organId) {
    try {
      const response = await apiClient.get('/penyakit.php', {
        params: { organId },
      });
      if (response.data.success) {
        return response.data.data;
      }
      throw new Error(response.data.message);
    } catch (error) {
      console.error('Error fetching diseases by organ:', error);
      throw error;
    }
  }

  static async getDiseaseDetail(diseaseId) {
    try {
      const response = await apiClient.get('/penyakit.php', {
        params: { id: diseaseId },
      });
      if (response.data.success) {
        return response.data.data;
      }
      throw new Error(response.data.message);
    } catch (error) {
      console.error('Error fetching disease detail:', error);
      throw error;
    }
  }

  // ============= DOCTOR ENDPOINTS =============

  static async getDoctorProfile() {
    try {
      const response = await apiClient.get('/profil_dokter.php');
      if (response.data.success) {
        return response.data.data;
      }
      throw new Error(response.data.message);
    } catch (error) {
      console.error('Error fetching doctor profile:', error);
      throw error;
    }
  }

  // ============= SCHEDULE ENDPOINTS =============

  static async getSchedules() {
    try {
      const response = await apiClient.get('/jadwal_praktek.php');
      if (response.data.success) {
        return response.data.data;
      }
      throw new Error(response.data.message);
    } catch (error) {
      console.error('Error fetching schedules:', error);
      throw error;
    }
  }

  static async getScheduleDetail(scheduleId) {
    try {
      const response = await apiClient.get('/jadwal_praktek.php', {
        params: { id: scheduleId },
      });
      if (response.data.success) {
        return response.data.data;
      }
      throw new Error(response.data.message);
    } catch (error) {
      console.error('Error fetching schedule detail:', error);
      throw error;
    }
  }

  // ============= SERVICES ENDPOINTS =============

  static async getServices() {
    try {
      const response = await apiClient.get('/pelayanan.php');
      if (response.data.success) {
        return response.data.data;
      }
      throw new Error(response.data.message);
    } catch (error) {
      console.error('Error fetching services:', error);
      throw error;
    }
  }
}

export default WebDokterAPI;

// ============= USAGE EXAMPLES =============

/*
import React, { useState, useEffect } from 'react';
import { View, FlatList, Text, ActivityIndicator } from 'react-native';
import WebDokterAPI from './api/WebDokterAPI';

export default function OrgansScreen() {
  const [organs, setOrgans] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const loadOrgans = async () => {
      try {
        const data = await WebDokterAPI.getOrgans();
        setOrgans(data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    loadOrgans();
  }, []);

  if (loading) return <ActivityIndicator size="large" />;
  if (error) return <Text>Error: {error}</Text>;

  return (
    <View>
      <FlatList
        data={organs}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item }) => (
          <View style={{ padding: 10, borderBottomWidth: 1 }}>
            <Text style={{ fontSize: 18, fontWeight: 'bold' }}>
              {item.nama}
            </Text>
            <Text>{item.deskripsi}</Text>
          </View>
        )}
      />
    </View>
  );
}
*/
