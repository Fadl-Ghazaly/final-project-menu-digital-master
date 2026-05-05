import 'dart:async';
import 'dart:convert';
import 'package:get/get.dart';
import 'package:mobile_flutter/services/api_service.dart';

class OrderController extends GetxController {
  final ApiService _apiService = ApiService();
  
  var isLoading = false.obs;
  var allOrders = <dynamic>[].obs;
  Timer? _timer;

  @override
  void onInit() {
    super.onInit();
    fetchAllOrders();
    startPolling();
  }

  @override
  void onClose() {
    _timer?.cancel();
    super.onClose();
  }

  void startPolling() {
    _timer = Timer.periodic(const Duration(seconds: 10), (timer) {
      fetchAllOrders(showLoading: false);
    });
  }

  Future<void> fetchAllOrders({bool showLoading = true}) async {
    if (showLoading) isLoading(true);
    try {
      final response = await _apiService.get('/all-orders');
      if (response.statusCode == 200) {
        var data = jsonDecode(response.body);
        // Only update if data has changed to prevent unnecessary rebuilds
        if (allOrders.toString() != data.toString()) {
          allOrders.value = data;
        }
      }
    } catch (e) {
      print("Error fetching all orders: $e");
    } finally {
      if (showLoading) isLoading(false);
    }
  }

  Future<bool> updateOrderStatus(int orderId, String status) async {
    try {
      final response = await _apiService.post('/orders/$orderId/status', {
        'status': status,
      });
      if (response.statusCode == 200) {
        fetchAllOrders(showLoading: false); // Refresh list immediately
        return true;
      }
      return false;
    } catch (e) {
      print("Error updating status: $e");
      return false;
    }
  }
}
