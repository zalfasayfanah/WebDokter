/**
 * WebDokter Mobile API Client
 * Example untuk Flutter
 * 
 * Installation:
 * flutter pub add http
 */

import 'package:http/http.dart' as http;
import 'dart:convert';

class WebDokterApiClient {
  static const String baseUrl = 'http://localhost/WebDokter/api';
  static const Duration timeout = Duration(seconds: 30);

  // Headers configuration
  static final Map<String, String> headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  };

  // ============= ORGAN ENDPOINTS =============

  /// Get all organs
  static Future<List<Map<String, dynamic>>> getOrgans() async {
    try {
      final response = await http
          .get(Uri.parse('$baseUrl/organ.php'), headers: headers)
          .timeout(timeout);

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          return List<Map<String, dynamic>>.from(data['data']);
        }
      }
      throw Exception('Failed to load organs: ${response.body}');
    } catch (e) {
      print('Error fetching organs: $e');
      rethrow;
    }
  }

  // ============= DISEASE ENDPOINTS =============

  /// Get all diseases
  static Future<List<Map<String, dynamic>>> getDiseases() async {
    try {
      final response = await http
          .get(Uri.parse('$baseUrl/penyakit.php'), headers: headers)
          .timeout(timeout);

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          return List<Map<String, dynamic>>.from(data['data']);
        }
      }
      throw Exception('Failed to load diseases');
    } catch (e) {
      print('Error fetching diseases: $e');
      rethrow;
    }
  }

  /// Get diseases by organ
  static Future<List<Map<String, dynamic>>> getDiseasesByOrgan(
    int organId,
  ) async {
    try {
      final response = await http
          .get(
            Uri.parse('$baseUrl/penyakit.php?organId=$organId'),
            headers: headers,
          )
          .timeout(timeout);

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          return List<Map<String, dynamic>>.from(data['data']);
        }
      }
      throw Exception('Failed to load diseases by organ');
    } catch (e) {
      print('Error fetching diseases by organ: $e');
      rethrow;
    }
  }

  /// Get disease detail
  static Future<Map<String, dynamic>> getDiseaseDetail(int diseaseId) async {
    try {
      final response = await http
          .get(
            Uri.parse('$baseUrl/penyakit.php?id=$diseaseId'),
            headers: headers,
          )
          .timeout(timeout);

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          return data['data'];
        }
      }
      throw Exception('Failed to load disease detail');
    } catch (e) {
      print('Error fetching disease detail: $e');
      rethrow;
    }
  }

  // ============= DOCTOR ENDPOINTS =============

  /// Get doctor profile
  static Future<Map<String, dynamic>> getDoctorProfile() async {
    try {
      final response = await http
          .get(Uri.parse('$baseUrl/profil_dokter.php'), headers: headers)
          .timeout(timeout);

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          return data['data'];
        }
      }
      throw Exception('Failed to load doctor profile');
    } catch (e) {
      print('Error fetching doctor profile: $e');
      rethrow;
    }
  }

  // ============= SCHEDULE ENDPOINTS =============

  /// Get all schedules
  static Future<List<Map<String, dynamic>>> getSchedules() async {
    try {
      final response = await http
          .get(Uri.parse('$baseUrl/jadwal_praktek.php'), headers: headers)
          .timeout(timeout);

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          return List<Map<String, dynamic>>.from(data['data']);
        }
      }
      throw Exception('Failed to load schedules');
    } catch (e) {
      print('Error fetching schedules: $e');
      rethrow;
    }
  }

  /// Get schedule detail
  static Future<Map<String, dynamic>> getScheduleDetail(int scheduleId) async {
    try {
      final response = await http
          .get(
            Uri.parse('$baseUrl/jadwal_praktek.php?id=$scheduleId'),
            headers: headers,
          )
          .timeout(timeout);

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          return data['data'];
        }
      }
      throw Exception('Failed to load schedule detail');
    } catch (e) {
      print('Error fetching schedule detail: $e');
      rethrow;
    }
  }

  // ============= SERVICES ENDPOINTS =============

  /// Get all services
  static Future<List<Map<String, dynamic>>> getServices() async {
    try {
      final response = await http
          .get(Uri.parse('$baseUrl/pelayanan.php'), headers: headers)
          .timeout(timeout);

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          return List<Map<String, dynamic>>.from(data['data']);
        }
      }
      throw Exception('Failed to load services');
    } catch (e) {
      print('Error fetching services: $e');
      rethrow;
    }
  }
}

// ============= USAGE EXAMPLES =============

// Example dalam main.dart atau widget:
/*
void main() {
  runApp(const MyApp());
}

class MyApp extends StatefulWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  State<MyApp> createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
  late Future<List<Map<String, dynamic>>> organs;

  @override
  void initState() {
    super.initState();
    organs = WebDokterApiClient.getOrgans();
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'WebDokter',
      home: Scaffold(
        appBar: AppBar(title: const Text('Organ List')),
        body: FutureBuilder(
          future: organs,
          builder: (context, snapshot) {
            if (snapshot.hasData) {
              return ListView.builder(
                itemCount: snapshot.data!.length,
                itemBuilder: (context, index) {
                  final organ = snapshot.data![index];
                  return ListTile(
                    title: Text(organ['nama']),
                    subtitle: Text(organ['deskripsi']),
                  );
                },
              );
            } else if (snapshot.hasError) {
              return Center(child: Text('Error: ${snapshot.error}'));
            }
            return const Center(child: CircularProgressIndicator());
          },
        ),
      ),
    );
  }
}
*/
