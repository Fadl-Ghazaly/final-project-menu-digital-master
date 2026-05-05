import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:mobile_flutter/constants.dart';
import 'package:mobile_flutter/controllers/kasir_controller.dart';
import 'package:mobile_flutter/screens/kasir/transaksi_baru_page.dart';
import 'package:mobile_flutter/screens/kasir/pembayaran_page.dart';
import 'package:mobile_flutter/screens/kasir/riwayat_pesanan_page.dart';
import 'package:mobile_flutter/screens/kasir/rekap_harian_page.dart';
import 'package:mobile_flutter/screens/kasir/pengaturan_kasir_page.dart';

class KasirLayout extends StatelessWidget {
  const KasirLayout({super.key});

  @override
  Widget build(BuildContext context) {
    // Cari controller yang sudah didaftarkan di main.dart
    final KasirController controller = Get.find<KasirController>();

    return Scaffold(
      backgroundColor: AppColors.background,
      body: Row(
        children: [
          // Sidebar (Fixed 240px)
          _buildSidebar(context, controller),
          
          // Main Content Area
          Expanded(
            child: Obx(() {
              return _buildContent(controller.selectedIndex.value);
            }),
          ),
        ],
      ),
    );
  }

  Widget _buildSidebar(BuildContext context, KasirController controller) {
    return Container(
      width: 240,
      height: double.infinity,
      color: const Color(0xFF1E1E2E),
      child: Column(
        children: [
          _buildLogo(),
          const SizedBox(height: 20),
          Expanded(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Column(
                children: [
                  _navItem(controller, 0, "Transaksi Baru", LucideIcons.shoppingCart),
                  _navItem(controller, 1, "Riwayat Pesanan", LucideIcons.clock),
                  _navItem(controller, 2, "Pembayaran", LucideIcons.creditCard),
                  _navItem(controller, 3, "Rekap Harian", LucideIcons.barChart2),
                  _navItem(controller, 4, "Pengaturan Kasir", LucideIcons.settings),
                ],
              ),
            ),
          ),
          _buildBottomBar(),
        ],
      ),
    );
  }

  Widget _buildLogo() {
    return Padding(
      padding: const EdgeInsets.all(24.0),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: AppColors.primary,
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Icon(LucideIcons.shoppingCart, color: Colors.white, size: 20),
          ),
          const SizedBox(width: 12),
          Text(
            "POS Resto",
            style: GoogleFonts.outfit(
              color: Colors.white,
              fontWeight: FontWeight.w900,
              fontSize: 18,
              fontStyle: FontStyle.italic,
            ),
          ),
        ],
      ),
    );
  }

  Widget _navItem(KasirController controller, int index, String title, IconData icon) {
    return Obx(() {
      bool isActive = controller.selectedIndex.value == index;
      return GestureDetector(
        onTap: () => controller.changeIndex(index),
        child: Container(
          margin: const EdgeInsets.only(bottom: 8),
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          decoration: BoxDecoration(
            color: isActive ? AppColors.primary : Colors.transparent,
            borderRadius: BorderRadius.circular(16),
          ),
          child: Row(
            children: [
              Icon(
                icon,
                color: isActive ? Colors.white : const Color(0xFF64748B),
                size: 20,
              ),
              const SizedBox(width: 12),
              Text(
                title,
                style: GoogleFonts.outfit(
                  color: isActive ? Colors.white : const Color(0xFF64748B),
                  fontWeight: isActive ? FontWeight.bold : FontWeight.w500,
                  fontSize: 14,
                ),
              ),
            ],
          ),
        ),
      );
    });
  }

  Widget _buildBottomBar() {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.black.withOpacity(0.2),
        border: Border(top: BorderSide(color: Colors.white.withOpacity(0.05))),
      ),
      child: Column(
        children: [
          Row(
            children: [
              Container(
                width: 36,
                height: 36,
                decoration: BoxDecoration(
                  color: const Color(0xFF0F172A),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Center(
                  child: Text("RN", style: GoogleFonts.outfit(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 12)),
                ),
              ),
              const SizedBox(width: 12),
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text("Kasir: Rina", style: GoogleFonts.outfit(color: const Color(0xFFE2E8F0), fontWeight: FontWeight.w900, fontSize: 12)),
                  Text("SHIFT PAGI · 08:00", style: GoogleFonts.outfit(color: const Color(0xFF64748B), fontWeight: FontWeight.bold, fontSize: 9, letterSpacing: 1.2)),
                ],
              ),
            ],
          ),
          const SizedBox(height: 16),
          GestureDetector(
            onTap: () => Get.back(),
            child: Row(
              children: [
                const Icon(LucideIcons.logOut, color: Color(0x7FFFFFFF), size: 14),
                const SizedBox(width: 8),
                Text("LOGOUT", style: GoogleFonts.outfit(color: const Color(0x7FFFFFFF), fontWeight: FontWeight.w900, fontSize: 10, letterSpacing: 2)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildContent(int index) {
    switch (index) {
      case 0: return TransaksiBaruPage();
      case 1: return RiwayatPesananPage();
      case 2: return PembayaranPage();
      case 3: return RekapHarianPage();
      case 4: return const PengaturanKasirPage();
      default: return TransaksiBaruPage();
    }
  }
}
