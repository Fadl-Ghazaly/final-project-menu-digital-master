import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:lucide_icons/lucide_icons.dart';
import 'package:mobile_flutter/constants.dart';
import 'package:mobile_flutter/controllers/auth_controller.dart';
import 'package:mobile_flutter/screens/login_screen.dart';
import 'package:mobile_flutter/screens/cashier/cashier_dashboard.dart';
import 'package:mobile_flutter/screens/cashier/cashier_orders_screen.dart';
import 'package:mobile_flutter/screens/profile_page.dart';

class CashierMainScreen extends StatefulWidget {
  const CashierMainScreen({super.key});

  @override
  State<CashierMainScreen> createState() => _CashierMainScreenState();
}

class _CashierMainScreenState extends State<CashierMainScreen> {
  final AuthController _authController = Get.find<AuthController>();
  int _selectedIndex = 0;

  @override
  void initState() {
    super.initState();
    // Security check: only cashiers can access this screen
    String userRole = _authController.role.value;
    print("CASHIER SCREEN CHECK: role=$userRole");
    
    if (userRole != 'kasir') {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        Get.offAll(() => const LoginScreen());
        Get.snackbar(
          'Akses Ditolak', 
          'Hanya Kasir yang dapat mengakses halaman ini (Role anda: $userRole).',
          backgroundColor: Colors.red,
          colorText: Colors.white,
        );
      });
    }
  }

  final List<Widget> _pages = [
    const CashierDashboard(),
    const CashierOrdersScreen(),
    const ProfilePage(),
  ];

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _pages[_selectedIndex],
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: AppColors.slate900.withOpacity(0.05),
              blurRadius: 20,
              offset: const Offset(0, -5),
            ),
          ],
        ),
        child: SafeArea(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8),
            child: BottomNavigationBar(
              currentIndex: _selectedIndex,
              onTap: _onItemTapped,
              backgroundColor: Colors.transparent,
              elevation: 0,
              selectedItemColor: AppColors.primary,
              unselectedItemColor: AppColors.slate400,
              selectedLabelStyle: const TextStyle(fontWeight: FontWeight.w900, fontSize: 11),
              unselectedLabelStyle: const TextStyle(fontWeight: FontWeight.w700, fontSize: 11),
              type: BottomNavigationBarType.fixed,
              items: const [
                BottomNavigationBarItem(
                  icon: Icon(LucideIcons.layoutDashboard, size: 22),
                  label: 'Dashboard',
                ),
                BottomNavigationBarItem(
                  icon: Icon(LucideIcons.shoppingBag, size: 22),
                  label: 'Pesanan',
                ),
                BottomNavigationBarItem(
                  icon: Icon(LucideIcons.user, size: 22),
                  label: 'Profil',
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
