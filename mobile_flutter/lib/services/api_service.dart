import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:mobile_flutter/constants.dart';
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static final ApiService _instance = ApiService._internal();

  factory ApiService() => _instance;

  ApiService._internal();

  /// ================= HEADER =================
  Future<Map<String, String>> _getHeaders() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      if (token != null && token.isNotEmpty)
        'Authorization': 'Bearer $token',
    };
  }

  /// ================= GET =================
  Future<http.Response> get(String endpoint) async {
    try {
      final response = await http.get(
        Uri.parse('${ApiConstants.baseUrl}$endpoint'),
        headers: await _getHeaders(),
      );

      return response;
    } catch (e) {
      throw Exception('GET ERROR: $e');
    }
  }

  /// ================= POST =================
  Future<http.Response> post(
    String endpoint,
    Map<String, dynamic> body,
  ) async {
    try {
      final response = await http.post(
        Uri.parse('${ApiConstants.baseUrl}$endpoint'),
        headers: await _getHeaders(),
        body: jsonEncode(body),
      );

      return response;
    } catch (e) {
      throw Exception('POST ERROR: $e');
    }
  }

  /// ================= PUT =================
  Future<http.Response> put(
    String endpoint,
    Map<String, dynamic> body,
  ) async {
    try {
      final response = await http.put(
        Uri.parse('${ApiConstants.baseUrl}$endpoint'),
        headers: await _getHeaders(),
        body: jsonEncode(body),
      );

      return response;
    } catch (e) {
      throw Exception('PUT ERROR: $e');
    }
  }

  /// ================= DELETE =================
  Future<http.Response> delete(String endpoint) async {
    try {
      final response = await http.delete(
        Uri.parse('${ApiConstants.baseUrl}$endpoint'),
        headers: await _getHeaders(),
      );

      return response;
    } catch (e) {
      throw Exception('DELETE ERROR: $e');
    }
  }
}