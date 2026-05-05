import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:mobile_flutter/constants.dart';
import 'package:mobile_flutter/controllers/pos_cart_controller.dart';
import 'package:mobile_flutter/controllers/kasir_controller.dart';

class TransaksiBaruPage extends StatelessWidget {
  TransaksiBaruPage({super.key});

  final currencyFormatter = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

  final List<Map<String, dynamic>> dummyProducts = [
    {'id': 1, 'name': 'Nasi Goreng Spesial', 'price': 35000.0, 'category': 'Makanan', 'status': 'TERSEDIA', 'image': 'https://images.unsplash.com/photo-1623653387945-2fd25214f8fc?w=500'},
    {'id': 2, 'name': 'Mie Goreng Seafood', 'price': 32000.0, 'category': 'Makanan', 'status': 'TERSEDIA', 'image': 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=500'},
    {'id': 3, 'name': 'Ayam Bakar Madu', 'price': 45000.0, 'category': 'Makanan', 'status': 'HABIS', 'image': 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=500'},
    {'id': 4, 'name': 'Es Teh Manis', 'price': 8000.0, 'category': 'Minuman', 'status': 'TERSEDIA', 'image': 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500'},
    {'id': 5, 'name': 'Es Jeruk Segar', 'price': 12000.0, 'category': 'Minuman', 'status': 'TERSEDIA', 'image': 'https://images.unsplash.com/photo-1613478223719-2ab802602423?w=500'},
    {'id': 6, 'name': 'Kentang Goreng', 'price': 18000.0, 'category': 'Snack', 'status': 'TERSEDIA', 'image': 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=500'},
    {'id': 7, 'name': 'Pisang Goreng Keju', 'price': 15000.0, 'category': 'Snack', 'status': 'TERSEDIA', 'image': 'https://images.unsplash.com/photo-1590005354167-6da97870c757?w=500'},
    {'id': 8, 'name': 'Promo Paket Hemat', 'price': 50000.0, 'category': 'Promo', 'status': 'TERSEDIA', 'image': 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500'},
  ];

  @override
  Widget build(BuildContext context) {
    // Pindahkan Get.find ke sini agar dipanggil saat build
    final PosCartController cartController = Get.find<PosCartController>();
    final KasirController kasirController = Get.find<KasirController>();

    return Row(
      children: [
        // Left Side: Product Browser (65%)
        Expanded(
          flex: 65,
          child: Container(
            color: AppColors.background,
            child: Column(
              children: [
                _buildTopHeader(cartController),
                _buildCategoryChips(),
                Expanded(child: _buildProductGrid(cartController)),
              ],
            ),
          ),
        ),

        // Vertical Divider
        Container(width: 1, color: AppColors.slate200),

        // Right Side: Order Summary (35%)
        Expanded(
          flex: 35,
          child: _buildOrderPanel(cartController, kasirController),
        ),
      ],
    );
  }

  Widget _buildTopHeader(PosCartController cartController) {
    return Padding(
      padding: const EdgeInsets.all(24.0),
      child: Row(
        children: [
          Expanded(
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: AppColors.slate200),
              ),
              child: TextField(
                decoration: InputDecoration(
                  hintText: "Cari menu...",
                  prefixIcon: const Icon(LucideIcons.search, size: 20),
                  border: InputBorder.none,
                  enabledBorder: InputBorder.none,
                  focusedBorder: InputBorder.none,
                  contentPadding: const EdgeInsets.symmetric(vertical: 15),
                ),
              ),
            ),
          ),
          const SizedBox(width: 20),
          Obx(() => Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: AppColors.slate200),
            ),
            child: DropdownButton<String>(
              value: cartController.selectedTable.value,
              underline: const SizedBox(),
              icon: const Icon(LucideIcons.chevronDown, size: 16),
              items: ["Meja 1", "Meja 2", "Meja 5", "Meja 10", "Takeaway"].map((String value) {
                return DropdownMenuItem<String>(
                  value: value,
                  child: Text(value, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 14)),
                );
              }).toList(),
              onChanged: (val) => cartController.selectedTable.value = val!,
            ),
          )),
        ],
      ),
    );
  }

  Widget _buildCategoryChips() {
    final categories = ["Semua", "Makanan", "Minuman", "Snack", "Promo"];
    return Container(
      height: 40,
      margin: const EdgeInsets.only(bottom: 24),
      child: ListView.builder(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 24),
        itemCount: categories.length,
        itemBuilder: (context, index) {
          bool isActive = index == 0;
          return Container(
            margin: const EdgeInsets.only(right: 12),
            child: Chip(
              label: Text(categories[index]),
              backgroundColor: isActive ? AppColors.primary : Colors.white,
              labelStyle: GoogleFonts.outfit(
                color: isActive ? Colors.white : AppColors.slate500,
                fontWeight: FontWeight.bold,
                fontSize: 12,
              ),
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
                side: BorderSide(color: isActive ? Colors.transparent : AppColors.slate200),
              ),
            ),
          );
        },
      ),
    );
  }

  Widget _buildProductGrid(PosCartController cartController) {
    return GridView.builder(
      padding: const EdgeInsets.fromLTRB(24, 0, 24, 24),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 3,
        childAspectRatio: 0.8,
        crossAxisSpacing: 20,
        mainAxisSpacing: 20,
      ),
      itemCount: dummyProducts.length,
      itemBuilder: (context, index) {
        final product = dummyProducts[index];
        bool isHabis = product['status'] == 'HABIS';

        return Container(
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(24),
            border: Border.all(color: AppColors.slate200),
            boxShadow: [AppDesign.shadow],
          ),
          clipBehavior: Clip.antiAlias,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Product Image
              Expanded(
                child: Stack(
                  fit: StackFit.expand,
                  children: [
                    Image.network(product['image'], fit: BoxFit.cover),
                    if (isHabis)
                      Container(
                        color: Colors.black.withOpacity(0.4),
                        child: Center(
                          child: Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: Text("HABIS", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 10, color: AppColors.slate500)),
                          ),
                        ),
                      ),
                  ],
                ),
              ),
              
              // Product Info
              Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      product['name'],
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 14),
                    ),
                    const SizedBox(height: 4),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          currencyFormatter.format(product['price']),
                          style: GoogleFonts.outfit(fontWeight: FontWeight.w900, color: AppColors.primary, fontSize: 14),
                        ),
                        GestureDetector(
                          onTap: isHabis ? null : () => cartController.addItem(product['id'], product['name'], product['price']),
                          child: Container(
                            padding: const EdgeInsets.all(8),
                            decoration: BoxDecoration(
                              color: isHabis ? AppColors.slate100 : AppColors.primary,
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: Icon(LucideIcons.plus, size: 16, color: isHabis ? AppColors.slate300 : Colors.white),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildOrderPanel(PosCartController cartController, KasirController kasirController) {
    return Container(
      color: Colors.white,
      padding: const EdgeInsets.all(32),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text("Pesanan", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 24)),
          const SizedBox(height: 24),
          
          // Order List
          Expanded(
            child: Obx(() => cartController.cartItems.isEmpty 
              ? Center(child: Text("Belum ada pesanan", style: GoogleFonts.outfit(color: AppColors.slate400, fontWeight: FontWeight.bold)))
              : ListView.separated(
                  itemCount: cartController.cartItems.length,
                  separatorBuilder: (context, index) => const Divider(height: 32, color: Color(0xFFF1F5F9)),
                  itemBuilder: (context, index) {
                    final item = cartController.cartItems[index];
                    return Row(
                      children: [
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(item.name, style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 14)),
                              Text(currencyFormatter.format(item.price), style: GoogleFonts.outfit(color: AppColors.slate400, fontWeight: FontWeight.bold, fontSize: 12)),
                            ],
                          ),
                        ),
                        Row(
                          children: [
                            GestureDetector(
                              onTap: () => cartController.removeItem(item.id),
                              child: Container(
                                padding: const EdgeInsets.all(6),
                                decoration: BoxDecoration(border: Border.all(color: AppColors.slate200), borderRadius: BorderRadius.circular(8)),
                                child: Icon(item.qty.value > 1 ? LucideIcons.minus : LucideIcons.trash2, size: 14, color: item.qty.value > 1 ? AppColors.slate500 : Colors.red),
                              ),
                            ),
                            Padding(
                              padding: const EdgeInsets.symmetric(horizontal: 12),
                              child: Obx(() => Text(item.qty.value.toString(), style: GoogleFonts.outfit(fontWeight: FontWeight.w900))),
                            ),
                            GestureDetector(
                              onTap: () => cartController.addItem(item.id, item.name, item.price),
                              child: Container(
                                padding: const EdgeInsets.all(6),
                                decoration: BoxDecoration(color: AppColors.primary, borderRadius: BorderRadius.circular(8)),
                                child: const Icon(LucideIcons.plus, size: 14, color: Colors.white),
                              ),
                            ),
                          ],
                        ),
                      ],
                    );
                  },
                ),
            ),
          ),

          const Divider(height: 48, thickness: 1, color: Color(0xFFF1F5F9)),

          // Summary
          Obx(() => Column(
            children: [
              _buildSummaryRow("Subtotal", currencyFormatter.format(cartController.subtotal)),
              const SizedBox(height: 12),
              _buildSummaryRow("Pajak (10%)", currencyFormatter.format(cartController.tax)),
              const SizedBox(height: 24),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text("Total", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 18)),
                  Text(currencyFormatter.format(cartController.total), style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 24, color: AppColors.primary)),
                ],
              ),
            ],
          )),

          const SizedBox(height: 32),

          ElevatedButton(
            onPressed: () {
              if (cartController.cartItems.isNotEmpty) {
                kasirController.changeIndex(2);
              } else {
                Get.snackbar("Peringatan", "Keranjang masih kosong");
              }
            },
            style: ElevatedButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 20),
              backgroundColor: AppColors.primary,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
              elevation: 20,
              shadowColor: AppColors.primary.withOpacity(0.4),
            ),
            child: Text("LANJUT KE PEMBAYARAN", style: GoogleFonts.outfit(fontWeight: FontWeight.w900, fontSize: 16, letterSpacing: 1)),
          ),
        ],
      ),
    );
  }

  Widget _buildSummaryRow(String label, String value) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(label, style: GoogleFonts.outfit(color: AppColors.slate500, fontWeight: FontWeight.bold)),
        Text(value, style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
      ],
    );
  }
}
