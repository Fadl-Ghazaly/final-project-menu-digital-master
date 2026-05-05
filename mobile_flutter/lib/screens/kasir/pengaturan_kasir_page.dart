import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:mobile_flutter/constants.dart';
import 'dart:math';

class PengaturanKasirPage extends StatelessWidget {
  const PengaturanKasirPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      body: LayoutBuilder(
        builder: (context, constraints) {
          // Safety Check: Pastikan lebar tidak negatif
          double fullWidth = max(0.0, constraints.maxWidth);
          double padding = 32.0;
          double contentWidth = max(0.0, fullWidth - (padding * 2));
          double columnWidth = max(0.0, (contentWidth - 32) / 2);

          return ListView(
            padding: EdgeInsets.all(padding),
            children: [
              _buildProfileCard(),
              const SizedBox(height: 32),
              
              Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  SizedBox(
                    width: columnWidth,
                    child: _buildSectionOperasional(),
                  ),
                  const SizedBox(width: 32),
                  SizedBox(
                    width: columnWidth,
                    child: _buildSectionPrinter(),
                  ),
                ],
              ),
              
              const SizedBox(height: 32),
              _buildSectionPayment(),
              const SizedBox(height: 32),
              _buildSectionAccount(),
            ],
          );
        },
      ),
    );
  }

  Widget _buildProfileCard() {
    return Container(
      padding: const EdgeInsets.all(32),
      decoration: BoxDecoration(
        color: Colors.white, 
        borderRadius: BorderRadius.circular(24), 
        border: Border.all(color: AppColors.slate200),
        boxShadow: [AppDesign.shadow],
      ),
      child: Row(
        children: [
          Container(
            width: 80, height: 80,
            decoration: const BoxDecoration(color: AppColors.primary, shape: BoxShape.circle),
            child: Center(child: Text("R", style: GoogleFonts.outfit(color: Colors.white, fontSize: 36, fontWeight: FontWeight.w900))),
          ),
          const SizedBox(width: 24),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text("Rina Susanti", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 24)),
                Text("KASIR UTAMA", style: GoogleFonts.outfit(color: AppColors.primary, fontWeight: FontWeight.bold, fontSize: 14, letterSpacing: 1)),
              ],
            ),
          ),
          ElevatedButton(
            onPressed: () {},
            style: ElevatedButton.styleFrom(
              backgroundColor: AppColors.slate100, 
              foregroundColor: AppColors.slate900, 
              elevation: 0,
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
            ),
            child: Text("UBAH PIN", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }

  Widget _buildSectionOperasional() {
    return _sectionWrapper(
      "Operasional",
      [
        _settingTile("Meja Default", "Meja 1", LucideIcons.layout, hasArrow: true),
        _settingToggle("Auto Print Struk", true, LucideIcons.printer),
        _settingToggle("Konfirmasi Pembayaran", false, LucideIcons.alertCircle),
      ],
    );
  }

  Widget _buildSectionPrinter() {
    return _sectionWrapper(
      "Printer",
      [
        _printerTile("Printer Struk", "Epson TM-T82", isConnected: true),
        _printerTile("Printer Dapur", "HP LaserJet", isConnected: false),
      ],
    );
  }

  Widget _buildSectionPayment() {
    return _sectionWrapper(
      "Metode Pembayaran",
      [
        Wrap(
          spacing: 16, runSpacing: 16,
          children: [
            _paymentToggle("Tunai", true),
            _paymentToggle("QRIS", true),
            _paymentToggle("Transfer Bank", false),
            _paymentToggle("Kartu Kredit", false),
          ],
        ),
      ],
    );
  }

  Widget _buildSectionAccount() {
    return _sectionWrapper(
      "Akun",
      [
        ListTile(
          leading: const Icon(LucideIcons.lock, size: 20),
          title: Text("Ganti Password", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
          trailing: const Icon(LucideIcons.chevronRight, size: 16),
          onTap: () {},
        ),
        const Divider(height: 1, indent: 16, endIndent: 16),
        ListTile(
          leading: const Icon(LucideIcons.logOut, size: 20, color: Colors.red),
          title: Text("Logout dari Terminal", style: GoogleFonts.outfit(fontWeight: FontWeight.bold, color: Colors.red)),
          onTap: () => Get.back(),
        ),
      ],
    );
  }

  Widget _sectionWrapper(String title, List<Widget> children) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.only(left: 8, bottom: 12),
          child: Text(title, style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 18, color: AppColors.slate900)),
        ),
        Container(
          decoration: BoxDecoration(
            color: Colors.white, 
            borderRadius: BorderRadius.circular(24), 
            border: Border.all(color: AppColors.slate200),
            boxShadow: [AppDesign.shadow],
          ),
          child: Column(children: children),
        ),
      ],
    );
  }

  Widget _settingTile(String label, String val, IconData icon, {bool hasArrow = false}) {
    return ListTile(
      leading: Icon(icon, size: 20, color: AppColors.slate400),
      title: Text(label, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 14)),
      trailing: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(val, style: GoogleFonts.outfit(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 14)),
          if (hasArrow) const Icon(LucideIcons.chevronRight, size: 16, color: AppColors.slate400),
        ],
      ),
    );
  }

  Widget _settingToggle(String label, bool val, IconData icon) {
    return SwitchListTile(
      secondary: Icon(icon, size: 20, color: AppColors.slate400),
      title: Text(label, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 14)),
      value: val,
      activeColor: AppColors.primary,
      onChanged: (v) {},
    );
  }

  Widget _printerTile(String label, String name, {required bool isConnected}) {
    return ListTile(
      title: Text(label, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 14)),
      subtitle: Text(name, style: GoogleFonts.outfit(color: AppColors.slate400, fontSize: 12)),
      trailing: Container(
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
        decoration: BoxDecoration(
          color: isConnected ? const Color(0xFFDCFCE7) : const Color(0xFFFEE2E2), 
          borderRadius: BorderRadius.circular(8)
        ),
        child: Text(
          isConnected ? "Terhubung" : "Terputus", 
          style: GoogleFonts.outfit(
            color: isConnected ? const Color(0xFF166534) : const Color(0xFF991B1B), 
            fontWeight: FontWeight.w900, 
            fontSize: 10
          )
        ),
      ),
    );
  }

  Widget _paymentToggle(String label, bool val) {
    return Container(
      width: 180,
      decoration: BoxDecoration(color: AppColors.slate50, borderRadius: BorderRadius.circular(16)),
      child: SwitchListTile(
        contentPadding: const EdgeInsets.only(left: 16, right: 8),
        title: Text(label, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 12)),
        value: val,
        activeColor: AppColors.primary,
        onChanged: (v) {},
      ),
    );
  }
}
