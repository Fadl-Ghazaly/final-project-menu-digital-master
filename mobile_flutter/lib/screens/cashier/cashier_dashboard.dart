import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'package:intl/intl.dart';

import 'package:mobile_flutter/constants.dart';
import 'package:mobile_flutter/controllers/order_controller.dart';

class CashierDashboard extends StatefulWidget {
  const CashierDashboard({super.key});

  @override
  State<CashierDashboard> createState() => _CashierDashboardState();
}

class _CashierDashboardState extends State<CashierDashboard> {
  final OrderController orderController = Get.put(OrderController());

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: RefreshIndicator(
          onRefresh: () => orderController.fetchAllOrders(),
          color: AppColors.primary,
          child: SingleChildScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            padding: const EdgeInsets.symmetric(
              horizontal: 24,
              vertical: 24,
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // HEADER
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Column(
                        crossAxisAlignment:
                            CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Panel Kasir',
                            style: TextStyle(
                              fontSize: 28,
                              fontWeight: FontWeight.w900,
                              color: AppColors.slate900,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            'Pantau pesanan aktif hari ini.',
                            style: TextStyle(
                              color: AppColors.slate500,
                              fontSize: 13,
                              fontWeight: FontWeight.w500,
                            ),
                          ),
                        ],
                      ),
                    ),
                    GestureDetector(
                      onTap: () {
                        orderController.fetchAllOrders();
                      },
                      child: Container(
                        padding: const EdgeInsets.all(10),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius:
                              BorderRadius.circular(15),
                          border: Border.all(
                            color: AppColors.slate200,
                          ),
                          boxShadow: [
                            AppDesign.shadow,
                          ],
                        ),
                        child: const Icon(
                          LucideIcons.refreshCw,
                          size: 20,
                          color: AppColors.primary,
                        ),
                      ),
                    ),
                  ],
                ),

                const SizedBox(height: 24),

                // DATE CHIP
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 16,
                    vertical: 8,
                  ),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius:
                        BorderRadius.circular(12),
                    border: Border.all(
                      color: AppColors.slate200,
                    ),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      const Icon(
                        LucideIcons.calendar,
                        size: 14,
                        color: AppColors.primary,
                      ),
                      const SizedBox(width: 8),
                      Text(
                        DateFormat('dd MMM yyyy')
                            .format(DateTime.now()),
                        style: TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w900,
                          color: AppColors.slate600,
                        ),
                      ),
                    ],
                  ),
                ),

                const SizedBox(height: 32),

                // STATISTIK
                Obx(() {
                  int totalOrders =
                      orderController.allOrders.length;

                  int pendingOrders = orderController
                      .allOrders
                      .where((o) =>
                          o['status'] ==
                          'processing')
                      .length;

                  double totalIncome =
                      orderController.allOrders
                          .where((o) =>
                              o['status'] ==
                              'completed')
                          .fold(
                    0,
                    (sum, o) =>
                        sum +
                        (double.tryParse(
                              o['total_price']
                                  .toString(),
                            ) ??
                            0),
                  );

                  return Column(
                    children: [
                      Row(
                        children: [
                          Expanded(
                            child: _buildWebStatCard(
                              title:
                                  'PESANAN BARU',
                              value:
                                  pendingOrders
                                      .toString(),
                              icon:
                                  LucideIcons
                                      .package,
                              color:
                                  Colors.orange,
                              subtitle:
                                  'Perlu Diproses',
                            ),
                          ),
                          const SizedBox(
                              width: 16),
                          Expanded(
                            child: _buildWebStatCard(
                              title:
                                  'TOTAL PESANAN',
                              value:
                                  totalOrders
                                      .toString(),
                              icon:
                                  LucideIcons
                                      .shoppingBag,
                              color:
                                  Colors.blue,
                              subtitle:
                                  'Hari Ini',
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      _buildWebStatCard(
                        title:
                            'PENDAPATAN',
                        value:
                            'Rp ${NumberFormat('#,###').format(totalIncome)}',
                        icon:
                            LucideIcons.wallet,
                        color: const Color(
                            0xFF10B981),
                        subtitle:
                            'Pesanan Selesai',
                        fullWidth: true,
                      ),
                    ],
                  );
                }),

                const SizedBox(height: 32),

                // RECENT ORDER
                Text(
                  'Pesanan Terbaru',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.w900,
                    color: AppColors.slate900,
                  ),
                ),

                const SizedBox(height: 16),

                Obx(() {
                  if (orderController
                      .isLoading.value) {
                    return const Center(
                      child:
                          CircularProgressIndicator(),
                    );
                  }

                  if (orderController
                      .allOrders.isEmpty) {
                    return Container(
                      width: double.infinity,
                      padding:
                          const EdgeInsets.all(
                              24),
                      decoration:
                          BoxDecoration(
                        color: Colors.white,
                        borderRadius:
                            BorderRadius
                                .circular(
                                    20),
                      ),
                      child: const Center(
                        child: Text(
                            'Belum ada pesanan'),
                      ),
                    );
                  }

                  return ListView.builder(
                    shrinkWrap: true,
                    physics:
                        const NeverScrollableScrollPhysics(),
                    itemCount: orderController
                                .allOrders
                                .length >
                            5
                        ? 5
                        : orderController
                            .allOrders
                            .length,
                    itemBuilder:
                        (context, index) {
                      final order =
                          orderController
                                  .allOrders[
                              index];

                      return _buildOrderTile(
                          order);
                    },
                  );
                }),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildWebStatCard({
    required String title,
    required String value,
    required IconData icon,
    required Color color,
    required String subtitle,
    bool fullWidth = false,
  }) {
    return Container(
      width:
          fullWidth ? double.infinity : null,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(
          AppDesign.radiusXL,
        ),
        border: Border.all(
          color: AppColors.slate200,
        ),
      ),
      child: Column(
        crossAxisAlignment:
            CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: TextStyle(
              fontSize: 10,
              fontWeight: FontWeight.w900,
              color: AppColors.slate400,
            ),
          ),
          const SizedBox(height: 12),
          Row(
            mainAxisAlignment:
                MainAxisAlignment
                    .spaceBetween,
            children: [
              Expanded(
                child: Text(
                  value,
                  maxLines: 1,
                  overflow:
                      TextOverflow.ellipsis,
                  style: TextStyle(
                    fontSize:
                        fullWidth
                            ? 28
                            : 22,
                    fontWeight:
                        FontWeight.w900,
                    color: AppColors
                        .slate900,
                  ),
                ),
              ),
              Container(
                padding:
                    const EdgeInsets.all(
                        10),
                decoration:
                    BoxDecoration(
                  color: color.withOpacity(
                      0.1),
                  borderRadius:
                      BorderRadius
                          .circular(14),
                ),
                child: Icon(
                  icon,
                  color: color,
                ),
              ),
            ],
          ),
          const SizedBox(height: 14),
          Text(
            subtitle,
            style: TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w700,
              color: color,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildOrderTile(dynamic order) {
    Color statusColor =
        order['status'] == 'processing'
            ? Colors.orange
            : order['status'] ==
                    'completed'
                ? const Color(
                    0xFF10B981)
                : Colors.red;

    return Container(
      margin:
          const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius:
            BorderRadius.circular(18),
        border: Border.all(
          color: AppColors.slate200,
        ),
      ),
      child: Row(
        mainAxisAlignment:
            MainAxisAlignment
                .spaceBetween,
        children: [
          Row(
            children: [
              Container(
                padding:
                    const EdgeInsets.all(
                        10),
                decoration:
                    BoxDecoration(
                  color:
                      AppColors.slate50,
                  shape:
                      BoxShape.circle,
                ),
                child: Icon(
                  LucideIcons.package,
                  size: 18,
                  color: AppColors
                      .slate400,
                ),
              ),
              const SizedBox(width: 12),
              Column(
                crossAxisAlignment:
                    CrossAxisAlignment
                        .start,
                children: [
                  Text(
                    'Order #${order['id']}',
                    style: TextStyle(
                      fontWeight:
                          FontWeight
                              .w900,
                      color: AppColors
                          .slate900,
                    ),
                  ),
                  Text(
                    order['name'] ??
                        'Pelanggan',
                    style: TextStyle(
                      fontSize: 12,
                      color: AppColors
                          .slate500,
                    ),
                  ),
                ],
              ),
            ],
          ),
          Container(
            padding:
                const EdgeInsets.symmetric(
              horizontal: 10,
              vertical: 4,
            ),
            decoration: BoxDecoration(
              color: statusColor
                  .withOpacity(0.1),
              borderRadius:
                  BorderRadius.circular(
                      8),
            ),
            child: Text(
              order['status']
                  .toString()
                  .toUpperCase(),
              style: TextStyle(
                color: statusColor,
                fontSize: 10,
                fontWeight:
                    FontWeight.w900,
              ),
            ),
          ),
        ],
      ),
    );
  }
}