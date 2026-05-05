import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:mobile_flutter/controllers/auth_controller.dart';
import 'package:mobile_flutter/controllers/kasir_controller.dart';
import 'package:mobile_flutter/controllers/pos_cart_controller.dart';
import 'package:mobile_flutter/controllers/payment_controller.dart';
import 'package:mobile_flutter/screens/splash_screen.dart';
import 'package:mobile_flutter/screens/login_screen.dart';
import 'package:mobile_flutter/screens/kasir/kasir_layout.dart';
import 'package:mobile_flutter/constants.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return GetMaterialApp(
      title: 'MenuKu POS',
      debugShowCheckedModeBanner: false,
      
      // Centralized Bindings: Daftarkan semua controller di sini agar tidak pernah NULL
      initialBinding: BindingsBuilder(() {
        Get.put(AuthController(), permanent: true);
        Get.put(KasirController(), permanent: true);
        Get.put(PosCartController(), permanent: true);
        Get.put(PaymentController(), permanent: true);
      }),
      
      theme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
          seedColor: AppColors.primary,
          primary: AppColors.primary,
          surface: AppColors.background,
        ),
        textTheme: GoogleFonts.outfitTextTheme(),
        scaffoldBackgroundColor: AppColors.background,
      ),
      initialRoute: '/',
      getPages: [
        GetPage(name: '/', page: () => SplashScreen()),
        GetPage(name: '/login', page: () => LoginScreen()),
        GetPage(name: '/kasir', page: () => KasirLayout()),
      ],
    );
  }
}
