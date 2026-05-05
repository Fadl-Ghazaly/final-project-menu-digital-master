import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:mobile_flutter/constants.dart';
import 'package:mobile_flutter/controllers/pos_cart_controller.dart';
import 'package:mobile_flutter/controllers/payment_controller.dart';
import 'package:mobile_flutter/controllers/kasir_controller.dart';

class PembayaranPage extends StatelessWidget {
  PembayaranPage({super.key});

  final currencyFormatter = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

  @override
  Widget build(BuildContext context) {
    // Lazy find
    final PosCartController cartController = Get.find<PosCartController>();
    final PaymentController paymentController = Get.find<PaymentController>();
    final KasirController kasirController = Get.find<KasirController>();

    return Obx(() {
      if (paymentController.isSuccess.value) {
        return _buildSuccessState(cartController, paymentController, kasirController);
      }
      return _buildPaymentLayout(cartController, paymentController);
    });
  }

  Widget _buildPaymentLayout(PosCartController cartController, PaymentController paymentController) {
    return Column(
      children: [
        // Tabs
        _buildTabs(paymentController),

        // Main Content Area
        Expanded(
          child: Container(
            color: AppColors.background,
            padding: const EdgeInsets.all(32),
            child: Obx(() {
              switch (paymentController.selectedTab.value) {
                case 1: return _buildQRISTab();
                case 2: return _buildSplitBillTab(cartController, paymentController);
                default: return _buildTunaiTab(cartController, paymentController);
              }
            }),
          ),
        ),
      ],
    );
  }

  Widget _buildTabs(PaymentController paymentController) {
    return Container(
      color: Colors.white,
      padding: const EdgeInsets.symmetric(horizontal: 32),
      child: Row(
        children: [
          _tabItem(paymentController, 0, "Tunai"),
          _tabItem(paymentController, 1, "QRIS"),
          _tabItem(paymentController, 2, "Split Bill"),
        ],
      ),
    );
  }

  Widget _tabItem(PaymentController paymentController, int index, String label) {
    return Obx(() {
      bool isActive = paymentController.selectedTab.value == index;
      return GestureDetector(
        onTap: () => paymentController.selectedTab.value = index,
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
          decoration: BoxDecoration(
            border: Border(
              bottom: BorderSide(
                color: isActive ? AppColors.primary : Colors.transparent,
                width: 3,
              ),
            ),
          ),
          child: Text(
            label,
            style: GoogleFonts.outfit(
              color: isActive ? AppColors.primary : AppColors.slate400,
              fontWeight: FontWeight.w900,
              fontSize: 16,
            ),
          ),
        ),
      );
    });
  }

  Widget _buildTunaiTab(PosCartController cartController, PaymentController paymentController) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Expanded(
          flex: 4,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildInfoBox("Total Pembayaran", cartController.total, isMain: true),
              const SizedBox(height: 24),
              Obx(() => _buildInputBox("Uang Diterima", paymentController.uangDiterima.value)),
              const SizedBox(height: 24),
              Obx(() => _buildChangeBox("Kembalian", paymentController.uangDiterima.value - cartController.total)),
              const Spacer(),
              _buildActionButtons(cartController, paymentController),
            ],
          ),
        ),
        const SizedBox(width: 32),
        Expanded(
          flex: 3,
          child: _buildNumpadSection(paymentController),
        ),
      ],
    );
  }

  Widget _buildInfoBox(String label, double amount, {bool isMain = false}) {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: AppColors.slate200),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: GoogleFonts.outfit(color: AppColors.slate500, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          Text(
            currencyFormatter.format(amount),
            style: GoogleFonts.outfit(
              fontWeight: FontWeight.w900,
              fontSize: isMain ? 32 : 24,
              color: isMain ? AppColors.slate900 : AppColors.primary,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInputBox(String label, double amount) {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: AppColors.primary, width: 2),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: GoogleFonts.outfit(color: AppColors.primary, fontWeight: FontWeight.w900)),
          const SizedBox(height: 8),
          Text(
            currencyFormatter.format(amount),
            style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 32, color: AppColors.slate900),
          ),
        ],
      ),
    );
  }

  Widget _buildChangeBox(String label, double amount) {
    bool isNegative = amount < 0;
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: isNegative ? AppColors.slate100 : const Color(0xFFDCFCE7),
        borderRadius: BorderRadius.circular(24),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: GoogleFonts.outfit(color: isNegative ? AppColors.slate400 : const Color(0xFF166534), fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          Text(
            isNegative ? "Rp 0" : currencyFormatter.format(amount),
            style: GoogleFonts.outfit(
              fontWeight: FontWeight.w900,
              fontSize: 24,
              color: isNegative ? AppColors.slate400 : const Color(0xFF166534),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildNumpadSection(PaymentController paymentController) {
    return Column(
      children: [
        Row(
          children: [
            _quickAmountBtn(paymentController, 50000),
            const SizedBox(width: 12),
            _quickAmountBtn(paymentController, 100000),
            const SizedBox(width: 12),
            _quickAmountBtn(paymentController, 150000),
          ],
        ),
        const SizedBox(height: 12),
        Expanded(
          child: Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: const Color(0xFF1E1E2E),
              borderRadius: BorderRadius.circular(32),
            ),
            child: GridView.count(
              crossAxisCount: 3,
              mainAxisSpacing: 12,
              crossAxisSpacing: 12,
              childAspectRatio: 1.2,
              children: [
                _numpadBtn(paymentController, "1"), _numpadBtn(paymentController, "2"), _numpadBtn(paymentController, "3"),
                _numpadBtn(paymentController, "4"), _numpadBtn(paymentController, "5"), _numpadBtn(paymentController, "6"),
                _numpadBtn(paymentController, "7"), _numpadBtn(paymentController, "8"), _numpadBtn(paymentController, "9"),
                _numpadBtn(paymentController, "000"), _numpadBtn(paymentController, "0"), _numpadBtn(paymentController, "backspace", icon: LucideIcons.delete),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _quickAmountBtn(PaymentController paymentController, double amount) {
    return Expanded(
      child: ElevatedButton(
        onPressed: () => paymentController.setQuickAmount(amount),
        style: ElevatedButton.styleFrom(
          backgroundColor: Colors.white,
          foregroundColor: AppColors.slate900,
          elevation: 0,
          side: const BorderSide(color: AppColors.slate200),
          padding: const EdgeInsets.symmetric(vertical: 16),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        ),
        child: Text(NumberFormat.compact().format(amount), style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
      ),
    );
  }

  Widget _numpadBtn(PaymentController paymentController, String val, {IconData? icon}) {
    return GestureDetector(
      onTap: () => paymentController.appendNumpad(val),
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white.withOpacity(0.05),
          borderRadius: BorderRadius.circular(20),
        ),
        child: Center(
          child: icon != null 
            ? Icon(icon, color: Colors.white)
            : Text(val, style: GoogleFonts.outfit(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
        ),
      ),
    );
  }

  Widget _buildActionButtons(PosCartController cartController, PaymentController paymentController) {
    return Obx(() => SizedBox(
      width: double.infinity,
      height: 70,
      child: ElevatedButton(
        onPressed: paymentController.uangDiterima.value >= cartController.total
            ? () => paymentController.processPayment()
            : null,
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.primary,
          elevation: 20,
          shadowColor: AppColors.primary.withOpacity(0.4),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
        ),
        child: Text(
          "KONFIRMASI PEMBAYARAN",
          style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 18, letterSpacing: 1),
        ),
      ),
    ));
  }

  Widget _buildQRISTab() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(32),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(32),
              boxShadow: [AppDesign.shadowLg],
            ),
            child: Column(
              children: [
                Image.network("https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=MENUKU_POS_PAYMENT", width: 250),
                const SizedBox(height: 24),
                Text("Berlaku selama: 04:58", style: GoogleFonts.outfit(color: Colors.red, fontWeight: FontWeight.bold)),
              ],
            ),
          ),
          const SizedBox(height: 48),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 3)),
              const SizedBox(width: 16),
              Text("Menunggu pembayaran...", style: GoogleFonts.outfit(fontWeight: FontWeight.bold, color: AppColors.slate500)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildSplitBillTab(PosCartController cartController, PaymentController paymentController) {
    return Obx(() {
      double perPerson = cartController.total / paymentController.splitCount.value;
      return Column(
        children: [
          Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24)),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text("Jumlah Orang", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 18)),
                Row(
                  children: [
                    IconButton(onPressed: () => paymentController.splitCount.value > 2 ? paymentController.splitCount.value-- : null, icon: const Icon(LucideIcons.minusCircle)),
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 20),
                      child: Text(paymentController.splitCount.value.toString(), style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 24)),
                    ),
                    IconButton(onPressed: () => paymentController.splitCount.value++, icon: const Icon(LucideIcons.plusCircle, color: AppColors.primary)),
                  ],
                ),
              ],
            ),
          ),
          const SizedBox(height: 24),
          Expanded(
            child: ListView.separated(
              itemCount: paymentController.splitCount.value,
              separatorBuilder: (context, index) => const SizedBox(height: 12),
              itemBuilder: (context, index) {
                return Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text("Orang ${index + 1}", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
                      Text(currencyFormatter.format(perPerson), style: GoogleFonts.outfit(fontWeight: FontWeight.w900, color: AppColors.primary)),
                      ElevatedButton(
                        onPressed: () {},
                        style: ElevatedButton.styleFrom(backgroundColor: AppColors.primary, minimumSize: const Size(100, 40)),
                        child: Text("BAYAR", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 12)),
                      ),
                    ],
                  ),
                );
              },
            ),
          ),
        ],
      );
    });
  }

  Widget _buildSuccessState(PosCartController cartController, PaymentController paymentController, KasirController kasirController) {
    return Container(
      width: double.infinity,
      color: Colors.white,
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Icon(LucideIcons.checkCircle, color: Color(0xFF22C55E), size: 100),
          const SizedBox(height: 24),
          Text("Pembayaran Berhasil!", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 32)),
          const SizedBox(height: 8),
          Text("Transaksi #${DateFormat('yyyyMMddHHmm').format(DateTime.now())}", style: GoogleFonts.outfit(color: AppColors.slate400, fontWeight: FontWeight.bold)),
          const SizedBox(height: 48),
          Container(
            width: 400,
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(color: AppColors.slate50, borderRadius: BorderRadius.circular(24)),
            child: Column(
              children: [
                _summarySuccessRow("Total Pembayaran", currencyFormatter.format(cartController.total)),
                const Divider(height: 32),
                _summarySuccessRow("Metode", paymentController.selectedTab.value == 0 ? "Tunai" : (paymentController.selectedTab.value == 1 ? "QRIS" : "Split Bill")),
                const SizedBox(height: 12),
                _summarySuccessRow("Diterima", currencyFormatter.format(paymentController.uangDiterima.value)),
                const SizedBox(height: 12),
                _summarySuccessRow("Kembalian", currencyFormatter.format(paymentController.uangDiterima.value - cartController.total)),
              ],
            ),
          ),
          const SizedBox(height: 48),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              _successActionBtn("Cetak Struk", LucideIcons.printer, Colors.white, AppColors.slate900),
              const SizedBox(width: 16),
              _successActionBtn("Kirim WhatsApp", LucideIcons.messageCircle, Colors.white, const Color(0xFF25D366)),
            ],
          ),
          const SizedBox(height: 16),
          SizedBox(
            width: 400,
            height: 60,
            child: ElevatedButton(
              onPressed: () {
                cartController.clearCart();
                paymentController.resetPayment();
                kasirController.changeIndex(0);
              },
              style: ElevatedButton.styleFrom(backgroundColor: AppColors.primary, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16))),
              child: Text("TRANSAKSI BARU", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 16)),
            ),
          ),
        ],
      ),
    );
  }

  Widget _summarySuccessRow(String label, String value) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(label, style: GoogleFonts.outfit(color: AppColors.slate500, fontWeight: FontWeight.bold)),
        Text(value, style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 18)),
      ],
    );
  }

  Widget _successActionBtn(String label, IconData icon, Color bg, Color text) {
    return ElevatedButton.icon(
      onPressed: () {},
      icon: Icon(icon, size: 18),
      label: Text(label),
      style: ElevatedButton.styleFrom(
        backgroundColor: text,
        foregroundColor: bg,
        minimumSize: const Size(192, 56),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      ),
    );
  }
}
