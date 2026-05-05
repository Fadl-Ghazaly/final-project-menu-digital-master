import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:mobile_flutter/constants.dart';
import 'dart:math';

class RekapHarianPage extends StatelessWidget {
  RekapHarianPage({super.key});

  final currencyFormatter = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      body: LayoutBuilder(
        builder: (context, constraints) {
          double fullWidth = max(0.0, constraints.maxWidth);
          double padding = 32.0;
          double contentWidth = max(0.0, fullWidth - (padding * 2));
          double column1Width = max(0.0, (contentWidth - 32) * 0.6);
          double column2Width = max(0.0, (contentWidth - 32) * 0.4);

          return Stack(
            children: [
              ListView(
                padding: EdgeInsets.fromLTRB(padding, padding, padding, 120),
                children: [
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Kolom Kiri: Stats
                      SizedBox(
                        width: column1Width,
                        child: Column(
                          children: [
                            _buildShiftCard(),
                            const SizedBox(height: 24),
                            _buildRevenueCard(),
                            const SizedBox(height: 24),
                            _buildTopMenuSection(),
                          ],
                        ),
                      ),
                      const SizedBox(width: 32),
                      // Kolom Kanan: Mutation
                      SizedBox(
                        width: column2Width,
                        child: Column(
                          children: [
                            _buildPaymentBreakdown(),
                            const SizedBox(height: 24),
                            _buildCashMutation(),
                          ],
                        ),
                      ),
                    ],
                  ),
                ],
              ),
              
              // Sticky Bottom Button
              Positioned(
                bottom: 32,
                left: 32,
                right: 32,
                child: _buildCloseShiftBtn(context),
              ),
            ],
          );
        },
      ),
    );
  }

  Widget _buildShiftCard() {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24), border: Border.all(color: AppColors.slate200)),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(color: AppColors.primary.withOpacity(0.1), borderRadius: BorderRadius.circular(16)),
            child: const Icon(LucideIcons.sun, color: AppColors.primary),
          ),
          const SizedBox(width: 20),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Text("Shift Pagi", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 18)),
                    const SizedBox(width: 12),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(color: const Color(0xFFDCFCE7), borderRadius: BorderRadius.circular(6)),
                      child: Text("AKTIF", style: GoogleFonts.outfit(color: const Color(0xFF166534), fontWeight: FontWeight.bold, fontSize: 10)),
                    ),
                  ],
                ),
                Text("08:00 - 16:00", style: GoogleFonts.outfit(color: AppColors.slate500, fontWeight: FontWeight.bold, fontSize: 14)),
              ],
            ),
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text("Kasir: Rina", style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 14)),
              Text("43 transaksi", style: GoogleFonts.outfit(color: AppColors.slate400, fontSize: 12, fontWeight: FontWeight.bold)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildRevenueCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(32),
      decoration: BoxDecoration(
        color: AppColors.primary,
        borderRadius: BorderRadius.circular(32),
        boxShadow: [
          BoxShadow(color: AppColors.primary.withOpacity(0.3), blurRadius: 30, offset: const Offset(0, 15)),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text("Total Pendapatan Hari Ini", style: GoogleFonts.outfit(color: Colors.white.withOpacity(0.8), fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          Text(currencyFormatter.format(2280000), style: GoogleFonts.outfit(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 36)),
          const SizedBox(height: 12),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
            decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), borderRadius: BorderRadius.circular(8)),
            child: Text("+12% dari kemarin", style: GoogleFonts.outfit(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12)),
          ),
          const SizedBox(height: 32),
          const Divider(color: Colors.white24),
          const SizedBox(height: 16),
          Row(
            children: [
              _subRevenueInfo("Penjualan Kotor", "Rp 2.100.000"),
              Container(width: 1, height: 40, color: Colors.white24, margin: const EdgeInsets.symmetric(horizontal: 24)),
              _subRevenueInfo("Total Pajak", "Rp 180.000"),
            ],
          ),
        ],
      ),
    );
  }

  Widget _subRevenueInfo(String label, String val) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: GoogleFonts.outfit(color: Colors.white70, fontSize: 11, fontWeight: FontWeight.bold)),
        Text(val, style: GoogleFonts.outfit(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 16)),
      ],
    );
  }

  Widget _buildTopMenuSection() {
    final topMenu = [
      {'rank': 1, 'name': 'Nasi Goreng Spesial', 'qty': 24, 'revenue': 840000},
      {'rank': 2, 'name': 'Mie Goreng Seafood', 'qty': 18, 'revenue': 576000},
      {'rank': 3, 'name': 'Ayam Bakar Madu', 'qty': 15, 'revenue': 675000},
    ];

    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24)),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text("Top 3 Menu Terlaris", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 18)),
          const SizedBox(height: 24),
          ...topMenu.map((item) => Padding(
            padding: const EdgeInsets.only(bottom: 16),
            child: Row(
              children: [
                Container(
                  width: 32, height: 32,
                  decoration: BoxDecoration(color: AppColors.slate50, borderRadius: BorderRadius.circular(8)),
                  child: Center(child: Text("${item['rank']}", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, color: AppColors.primary))),
                ),
                const SizedBox(width: 16),
                Expanded(child: Text("${item['name']}", style: GoogleFonts.outfit(fontWeight: FontWeight.bold))),
                Text("${item['qty']} terjual", style: GoogleFonts.outfit(color: AppColors.slate500, fontWeight: FontWeight.bold, fontSize: 12)),
              ],
            ),
          )).toList(),
        ],
      ),
    );
  }

  Widget _buildPaymentBreakdown() {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24)),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text("Metode Pembayaran", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 18)),
          const SizedBox(height: 24),
          _methodBar("Tunai", 1200000, 2280000, Colors.orange),
          const SizedBox(height: 16),
          _methodBar("QRIS", 850000, 2280000, Colors.blue),
          const SizedBox(height: 16),
          _methodBar("Transfer", 230000, 2280000, Colors.teal),
        ],
      ),
    );
  }

  Widget _methodBar(String label, double val, double total, Color color) {
    double percent = val / total;
    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(label, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 13)),
            Text(currencyFormatter.format(val), style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 13)),
          ],
        ),
        const SizedBox(height: 8),
        LinearProgressIndicator(value: percent, backgroundColor: AppColors.slate50, color: color, minHeight: 8, borderRadius: BorderRadius.circular(4)),
      ],
    );
  }

  Widget _buildCashMutation() {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24)),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text("Mutasi Kas", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 18)),
          const SizedBox(height: 24),
          _mutationRow("Modal Awal", 500000, isAdd: true),
          _mutationRow("Penjualan Tunai", 1200000, isAdd: true),
          _mutationRow("Pengeluaran Operasional", 100000, isAdd: false),
          const Divider(height: 32),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text("Saldo Kas Akhir", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 16)),
              Text(currencyFormatter.format(1600000), style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 18, color: AppColors.primary)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _mutationRow(String label, double val, {required bool isAdd}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: GoogleFonts.outfit(color: AppColors.slate500, fontWeight: FontWeight.bold, fontSize: 13)),
          Text(
            "${isAdd ? '+' : '-'} ${currencyFormatter.format(val)}",
            style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 13, color: isAdd ? Colors.teal : Colors.red),
          ),
        ],
      ),
    );
  }

  Widget _buildCloseShiftBtn(BuildContext context) {
    return SizedBox(
      width: double.infinity,
      height: 60,
      child: ElevatedButton(
        onPressed: () => _showCloseShiftDialog(context),
        style: ElevatedButton.styleFrom(
          backgroundColor: Colors.red,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
          elevation: 20, shadowColor: Colors.red.withOpacity(0.4),
        ),
        child: Text("TUTUP SHIFT", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, letterSpacing: 2)),
      ),
    );
  }

  void _showCloseShiftDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
        title: Text("Yakin tutup shift?", style: GoogleFonts.outfit(fontWeight: FontWeight.w900)),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text("Semua transaksi hari ini akan direkap dan saldo kas akan dibekukan.", style: GoogleFonts.outfit(color: AppColors.slate500)),
            const SizedBox(height: 24),
            _dialogSummaryRow("Total Transaksi", "43"),
            _dialogSummaryRow("Omzet Kotor", "Rp 2.280.000"),
            _dialogSummaryRow("Saldo Kas Akhir", "Rp 1.600.000"),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Get.back(), child: Text("BATAL", style: GoogleFonts.outfit(color: AppColors.slate400, fontWeight: FontWeight.bold))),
          ElevatedButton(
            onPressed: () => Get.back(),
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12))),
            child: Text("YA, TUTUP SHIFT", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }

  Widget _dialogSummaryRow(String label, String val) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
          Text(val, style: GoogleFonts.outfit(fontWeight: FontWeight.w900, color: AppColors.primary)),
        ],
      ),
    );
  }
}
