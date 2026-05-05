import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:mobile_flutter/constants.dart';

class RiwayatPesananPage extends StatelessWidget {
  RiwayatPesananPage({super.key});

  final currencyFormatter = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);
  final selectedTrx = {}.obs;

  final List<Map<String, dynamic>> dummyTrx = [
    {
      'id': '#TRX001',
      'date': '24 Mei 2024',
      'time': '12:30',
      'table': 'Meja 5',
      'items': '3 items',
      'total': 125000.0,
      'status': 'LUNAS',
      'method': 'Tunai',
      'kasir': 'Rina',
      'details': [
        {'name': 'Nasi Goreng Spesial', 'qty': 2, 'price': 35000.0},
        {'name': 'Es Teh Manis', 'qty': 2, 'price': 8000.0},
        {'name': 'Kentang Goreng', 'qty': 1, 'price': 18000.0},
      ]
    },
    {
      'id': '#TRX002',
      'date': '24 Mei 2024',
      'time': '13:15',
      'table': 'Meja 2',
      'items': '1 item',
      'total': 45000.0,
      'status': 'PENDING',
      'method': 'QRIS',
      'kasir': 'Rina',
      'details': [
        {'name': 'Ayam Bakar Madu', 'qty': 1, 'price': 45000.0},
      ]
    },
    {
      'id': '#TRX003',
      'date': '24 Mei 2024',
      'time': '14:00',
      'table': 'Meja 10',
      'items': '2 items',
      'total': 24000.0,
      'status': 'DIBATALKAN',
      'method': '-',
      'kasir': 'Rina',
      'details': [
        {'name': 'Es Jeruk Segar', 'qty': 2, 'price': 12000.0},
      ]
    },
  ];

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        // Left Column: List (65%)
        Expanded(
          flex: 65,
          child: Container(
            color: AppColors.background,
            child: Column(
              children: [
                _buildSummaryStats(),
                _buildFilters(),
                Expanded(child: _buildTransactionList()),
              ],
            ),
          ),
        ),
        // Divider
        Container(width: 1, color: AppColors.slate200),
        // Right Column: Details (35%)
        Expanded(
          flex: 35,
          child: Obx(() => _buildDetailPanel()),
        ),
      ],
    );
  }

  Widget _buildSummaryStats() {
    return Padding(
      padding: const EdgeInsets.all(24),
      child: Row(
        children: [
          _statCard("Total Transaksi", "43", LucideIcons.shoppingBag, Colors.blue),
          const SizedBox(width: 20),
          _statCard("Lunas", "38", LucideIcons.checkCircle, Colors.teal),
          const SizedBox(width: 20),
          _statCard("Pending", "5", LucideIcons.clock, Colors.amber),
        ],
      ),
    );
  }

  Widget _statCard(String label, String val, IconData icon, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: AppColors.slate200),
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(10),
              decoration: BoxDecoration(color: color.withOpacity(0.1), borderRadius: BorderRadius.circular(12)),
              child: Icon(icon, color: color, size: 20),
            ),
            const SizedBox(width: 16),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(label, style: GoogleFonts.outfit(color: AppColors.slate500, fontSize: 12, fontWeight: FontWeight.bold)),
                Text(val, style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 20)),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFilters() {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 24),
      child: Row(
        children: [
          _filterDropdown("Hari Ini", LucideIcons.calendar),
          const SizedBox(width: 12),
          _filterDropdown("Semua Status", LucideIcons.filter),
        ],
      ),
    );
  }

  Widget _filterDropdown(String label, IconData icon) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(12), border: Border.all(color: AppColors.slate200)),
      child: Row(
        children: [
          Icon(icon, size: 16, color: AppColors.slate500),
          const SizedBox(width: 8),
          Text(label, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 13)),
          const SizedBox(width: 8),
          const Icon(LucideIcons.chevronDown, size: 14),
        ],
      ),
    );
  }

  Widget _buildTransactionList() {
    return ListView.builder(
      padding: const EdgeInsets.all(24),
      itemCount: dummyTrx.length,
      itemBuilder: (context, index) {
        final trx = dummyTrx[index];
        return Obx(() {
          bool isSelected = selectedTrx['id'] == trx['id'];
          return GestureDetector(
            onTap: () => selectedTrx.value = trx,
            child: Container(
              margin: const EdgeInsets.only(bottom: 12),
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: isSelected ? Colors.white : Colors.transparent,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: isSelected ? AppColors.primary : AppColors.slate200),
                boxShadow: isSelected ? [AppDesign.shadow] : [],
              ),
              child: Row(
                children: [
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Text(trx['id'], style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 16)),
                            const SizedBox(width: 8),
                            _statusBadge(trx['status']),
                          ],
                        ),
                        const SizedBox(height: 4),
                        Text("${trx['date']} • ${trx['time']}", style: GoogleFonts.outfit(color: AppColors.slate400, fontSize: 12, fontWeight: FontWeight.bold)),
                      ],
                    ),
                  ),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Text(currencyFormatter.format(trx['total']), style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 16, color: AppColors.slate900)),
                      Text("${trx['table']} • ${trx['items']}", style: GoogleFonts.outfit(color: AppColors.slate500, fontSize: 12, fontWeight: FontWeight.bold)),
                    ],
                  ),
                ],
              ),
            ),
          );
        });
      },
    );
  }

  Widget _statusBadge(String status) {
    Color bg = Colors.grey;
    Color text = Colors.white;
    if (status == 'LUNAS') { bg = const Color(0xFFDCFCE7); text = const Color(0xFF166534); }
    if (status == 'PENDING') { bg = const Color(0xFFFEF3C7); text = const Color(0xFF92400E); }
    if (status == 'DIBATALKAN') { bg = const Color(0xFFFEE2E2); text = const Color(0xFF991B1B); }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(color: bg, borderRadius: BorderRadius.circular(8)),
      child: Text(status, style: GoogleFonts.outfit(color: text, fontWeight: FontWeight.w900, fontSize: 10)),
    );
  }

  Widget _buildDetailPanel() {
    if (selectedTrx.isEmpty) {
      return Center(child: Text("Pilih transaksi untuk melihat detail", style: GoogleFonts.outfit(color: AppColors.slate400, fontWeight: FontWeight.bold)));
    }

    return Container(
      color: Colors.white,
      padding: const EdgeInsets.all(32),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text("Detail Transaksi", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 20)),
              _statusBadge(selectedTrx['status']),
            ],
          ),
          const SizedBox(height: 32),
          _detailRow("ID Transaksi", selectedTrx['id']),
          _detailRow("Waktu", "${selectedTrx['date']} ${selectedTrx['time']}"),
          _detailRow("Meja", selectedTrx['table']),
          _detailRow("Kasir", selectedTrx['kasir']),
          _detailRow("Metode Pembayaran", selectedTrx['method']),
          const Divider(height: 48),
          Text("Pesanan", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 16)),
          const SizedBox(height: 16),
          Expanded(
            child: ListView.separated(
              itemCount: (selectedTrx['details'] as List).length,
              separatorBuilder: (context, index) => const SizedBox(height: 12),
              itemBuilder: (context, index) {
                final item = selectedTrx['details'][index];
                return Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text("${item['qty']}x ${item['name']}", style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 13)),
                    Text(currencyFormatter.format(item['price'] * item['qty']), style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 13)),
                  ],
                );
              },
            ),
          ),
          const Divider(height: 48),
          _summaryRow("Subtotal", currencyFormatter.format(selectedTrx['total'] / 1.1)),
          _summaryRow("Pajak (10%)", currencyFormatter.format(selectedTrx['total'] - (selectedTrx['total'] / 1.1))),
          const SizedBox(height: 16),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text("Total", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 18)),
              Text(currencyFormatter.format(selectedTrx['total']), style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 24, color: AppColors.primary)),
            ],
          ),
          const SizedBox(height: 32),
          SizedBox(
            width: double.infinity,
            height: 60,
            child: ElevatedButton.icon(
              onPressed: () {},
              icon: const Icon(LucideIcons.printer, size: 20),
              label: Text("CETAK STRUK", style: GoogleFonts.outfit(fontWeight: FontWeight.w900)),
              style: ElevatedButton.styleFrom(backgroundColor: AppColors.primary, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))),
            ),
          ),
        ],
      ),
    );
  }

  Widget _detailRow(String label, String val) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: GoogleFonts.outfit(color: AppColors.slate500, fontWeight: FontWeight.bold, fontSize: 13)),
          Text(val, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 13)),
        ],
      ),
    );
  }

  Widget _summaryRow(String label, String val) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: GoogleFonts.outfit(color: AppColors.slate500, fontWeight: FontWeight.bold, fontSize: 13)),
          Text(val, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 14)),
        ],
      ),
    );
  }
}
