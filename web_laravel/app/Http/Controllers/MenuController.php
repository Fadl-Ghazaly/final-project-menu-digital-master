<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $products = Product::all();
        $tables = \App\Models\Table::all();
        
        // Data for Menu Digital
        $restoName = $request->query('resto', 'Warung Makan Pak Budi');
        $tableName = $request->query('meja', 'Meja Default');
        
        // Dummy Promos
        $promos = [
            [
                'title' => 'Diskon 20% Semua Menu',
                'description' => 'Khusus hari ini untuk makan di tempat',
                'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800',
                'color' => 'linear-gradient(135deg, #FF8C00 0%, #E8781A 100%)'
            ],
            [
                'title' => 'Beli 1 Gratis 1 Es Teh',
                'description' => 'Setiap pembelian Nasi Goreng Spesial',
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=800',
                'color' => 'linear-gradient(135deg, #1D9E75 0%, #166534 100%)'
            ],
        ];

        return view('menu', compact('categories', 'products', 'tables', 'restoName', 'tableName', 'promos'));
    }
}
