import 'dart:convert';
import 'package:get/get.dart';
import 'package:mobile_flutter/models/category.dart';
import 'package:mobile_flutter/models/product.dart';
import 'package:mobile_flutter/services/api_service.dart';

class ProductController extends GetxController {
  final ApiService _apiService = ApiService();

  var isLoading = false.obs;
  var categories = <Category>[].obs;
  var products = <Product>[].obs;
  var popularProducts = <Product>[].obs;
  var selectedCategory = 0.obs;

  @override
  void onInit() {
    super.onInit();
    fetchCategories();
    fetchPopularProducts();
    fetchProducts();
  }

  Future<void> fetchCategories() async {
    try {
      final response = await _apiService.get('/categories');
      if (response.statusCode == 200) {
        final List data = jsonDecode(response.body);
        categories.value = data.map((e) => Category.fromJson(e)).toList();
      }
    } catch (e) {
      print("Error fetching categories: $e");
    }
  }

  Future<void> fetchProducts({int? categoryId, String? query}) async {
    isLoading(true);
    try {
      String endpoint = '/products';
      if (query != null && query.isNotEmpty) {
        endpoint += '/search?q=$query';
      } else if (categoryId != null && categoryId != 0) {
        endpoint += '?category_id=$categoryId';
      }
      
      final response = await _apiService.get(endpoint);
      if (response.statusCode == 200) {
        final List data = jsonDecode(response.body);
        products.value = data.map((e) => Product.fromJson(e)).toList();
      }
    } catch (e) {
      print("Error fetching products: $e");
    } finally {
      isLoading(false);
    }
  }

  Future<void> fetchPopularProducts() async {
    try {
      final response = await _apiService.get('/products/popular');
      if (response.statusCode == 200) {
        final List data = jsonDecode(response.body);
        popularProducts.value = data.map((e) => Product.fromJson(e)).toList();
      }
    } catch (e) {
      print("Error fetching popular products: $e");
    }
  }

  void selectCategory(int id) {
    selectedCategory.value = id;
    fetchProducts(categoryId: id);
  }
}
