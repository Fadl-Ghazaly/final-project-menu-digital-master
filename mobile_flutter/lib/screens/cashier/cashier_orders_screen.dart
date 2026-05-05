import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'package:mobile_flutter/constants.dart';
import 'package:mobile_flutter/controllers/order_controller.dart';
import 'package:intl/intl.dart';

class CashierOrdersScreen extends StatefulWidget {
  const CashierOrdersScreen({super.key});

  @override
  State<CashierOrdersScreen> createState() => _CashierOrdersScreenState();
}

class _CashierOrdersScreenState extends State<CashierOrdersScreen> {
  final OrderController orderController = Get.find<OrderController>();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: Text(
          'Data Pesanan',
          style: TextStyle(fontWeight: FontWeight.w900, color: AppColors.slate900),
        ),
        centerTitle: true,
        backgroundColor: Colors.transparent,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(LucideIcons.refreshCw, color: AppColors.primary),
            onPressed: () => orderController.fetchAllOrders(),
          ),
        ],
      ),
      body: Obx(() {
        if (orderController.isLoading.value) {
          return const Center(child: CircularProgressIndicator());
        }
        if (orderController.allOrders.isEmpty) {
          return const Center(child: Text('Tidak ada pesanan tersedia.'));
        }

        return RefreshIndicator(
          onRefresh: () => orderController.fetchAllOrders(),
          color: AppColors.primary,
          child: ListView.builder(
            physics: const AlwaysScrollableScrollPhysics(),
            padding: const EdgeInsets.all(24),
            itemCount: orderController.allOrders.length,
            itemBuilder: (context, index) {
              final order = orderController.allOrders[index];
              return _buildOrderCard(order);
            },
          ),
        );
      }),
    );
  }

  Widget _buildOrderCard(dynamic order) {
    Color statusColor = order['status'] == 'processing' ? Colors.orange : (order['status'] == 'completed' ? Color(0xFF10B981) : Colors.red);
    
    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: AppColors.slate200),
        boxShadow: [AppDesign.shadow],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Order Header
          Padding(
            padding: const EdgeInsets.all(20),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'ORDER #${order['id']}',
                      style: TextStyle(fontWeight: FontWeight.w900, fontSize: 16, color: AppColors.slate900),
                    ),
                    Text(
                      DateFormat('dd MMM yyyy, HH:mm').format(DateTime.parse(order['created_at'])),
                      style: TextStyle(color: AppColors.slate500, fontSize: 11, fontWeight: FontWeight.bold),
                    ),
                  ],
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                  decoration: BoxDecoration(
                    color: statusColor.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: Text(
                    order['status'].toString().toUpperCase(),
                    style: TextStyle(color: statusColor, fontSize: 10, fontWeight: FontWeight.w900),
                  ),
                ),
              ],
            ),
          ),
          
          const Divider(height: 1, color: AppColors.slate100),
          
          // Order Details
          Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildInfoRow(LucideIcons.user, 'Pelanggan', order['name'] ?? '-'),
                const SizedBox(height: 12),
                _buildInfoRow(LucideIcons.phone, 'Kontak', order['phone'] ?? '-'),
                const SizedBox(height: 12),
                _buildInfoRow(LucideIcons.mapPin, 'Alamat', order['address'] ?? '-'),
                const SizedBox(height: 20),
                
                Text(
                  'Daftar Pesanan:',
                  style: TextStyle(fontWeight: FontWeight.w900, fontSize: 12, color: AppColors.slate900),
                ),
                const SizedBox(height: 8),
                ...(order['items'] as List).map((item) => Padding(
                  padding: const EdgeInsets.only(bottom: 4),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        '${item['quantity']}x ${item['product']['name']}',
                        style: TextStyle(color: AppColors.slate600, fontSize: 13, fontWeight: FontWeight.w500),
                      ),
                      Text(
                        'Rp ${NumberFormat('#,###').format(double.parse(item['price'].toString()) * item['quantity'])}',
                        style: TextStyle(color: AppColors.slate900, fontSize: 13, fontWeight: FontWeight.bold),
                      ),
                    ],
                  ),
                )).toList(),
                
                const Padding(
                  padding: EdgeInsets.symmetric(vertical: 16),
                  child: Divider(height: 1, color: AppColors.slate100),
                ),
                
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'TOTAL PEMBAYARAN',
                      style: TextStyle(fontWeight: FontWeight.w900, fontSize: 11, color: AppColors.slate500),
                    ),
                    Text(
                      'Rp ${NumberFormat('#,###').format(double.tryParse(order['total_price'].toString()) ?? 0)}',
                      style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 18, color: AppColors.primary),
                    ),
                  ],
                ),
              ],
            ),
          ),
          
          // Actions
          if (order['status'] == 'processing')
            Padding(
              padding: const EdgeInsets.fromLTRB(20, 0, 20, 20),
              child: Row(
                children: [
                  Expanded(
                    child: ElevatedButton(
                      onPressed: () => _updateStatus(order['id'], 'completed'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.success,
                        padding: const EdgeInsets.symmetric(vertical: 16),
                      ),
                      child: const Text('TANDAI SELESAI', style: TextStyle(fontWeight: FontWeight.w900)),
                    ),
                  ),
                  const SizedBox(width: 12),
                  IconButton(
                    onPressed: () => _updateStatus(order['id'], 'cancelled'),
                    icon: const Icon(LucideIcons.xCircle, color: Colors.red),
                    padding: const EdgeInsets.all(12),
                  ),
                ],
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildInfoRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Icon(icon, size: 14, color: AppColors.slate400),
        const SizedBox(width: 12),
        Text(
          '$label:',
          style: TextStyle(color: AppColors.slate400, fontSize: 12, fontWeight: FontWeight.bold),
        ),
        const SizedBox(width: 8),
        Expanded(
          child: Text(
            value,
            style: TextStyle(color: AppColors.slate900, fontSize: 12, fontWeight: FontWeight.w900),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
        ),
      ],
    );
  }

  void _updateStatus(int id, String status) async {
    bool success = await orderController.updateOrderStatus(id, status);
    if (success) {
      Get.snackbar(
        'Berhasil', 
        'Status pesanan diperbarui.',
        backgroundColor: AppColors.success,
        colorText: Colors.white,
      );
    } else {
      Get.snackbar(
        'Gagal', 
        'Terjadi kesalahan.',
        backgroundColor: Colors.red,
        colorText: Colors.white,
      );
    }
  }
}
