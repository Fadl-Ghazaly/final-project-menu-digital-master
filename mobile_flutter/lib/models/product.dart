class Product {
  final int id;
  final int categoryId;
  final String name;
  final String description;
  final double price;
  final String? image;
  final bool isPopular;

  Product({
    required this.id,
    required this.categoryId,
    required this.name,
    required this.description,
    required this.price,
    this.image,
    required this.isPopular,
  });

  factory Product.fromJson(Map<String, dynamic> json) {
    return Product(
      id: json['id'],
      categoryId: json['category_id'],
      name: json['name'],
      description: json['description'] ?? '',
      price: double.parse(json['price'].toString()),
      image: json['image'],
      isPopular: json['is_popular'] == 1 || json['is_popular'] == true,
    );
  }
}
