@php
    $setting = \App\Models\Setting::first() ?? new \App\Models\Setting;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $restoName }} - Menu Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '{{ $setting->primary_color ?? "#E8781A" }}',
                    },
                    borderRadius: {
                        '3xl': '1.5rem',
                        '4xl': '2rem',
                        '5xl': '2.5rem',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Outfit', sans-serif; -webkit-tap-highlight-color: transparent; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .safe-area-inset-bottom { padding-bottom: env(safe-area-inset-bottom); }
        .promo-gradient { background: linear-gradient(135deg, #FF8C00 0%, #E8781A 100%); }
        .stepper-line { width: 2px; background: #E2E8F0; position: absolute; left: 15px; top: 30px; bottom: 10px; }
        .stepper-line.active { background: #E8781A; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 overflow-x-hidden min-h-screen" x-data="appData()" x-init="initApp('{{ $tableName }}')" x-cloak>

    <!-- App Container (Mobile Centered) -->
    <div class="max-w-[430px] mx-auto min-h-screen bg-white shadow-2xl relative flex flex-col">
        
        <!-- Header (Sticky) -->
        <header class="sticky top-0 z-50 glass border-b border-slate-100 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <a href="{{ route('home') }}" class="w-8 h-8 rounded-full bg-white border border-slate-50 flex items-center justify-center text-slate-400 hover:text-brand transition mr-1">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </a>
                    <div class="w-10 h-10 bg-brand rounded-xl flex items-center justify-center text-white shadow-lg shadow-brand/20">
                        <i data-lucide="utensils" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="font-black text-slate-900 text-sm leading-tight">{{ $restoName }}</h1>
                        <div class="flex items-center gap-1">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $tableName }}</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <button @click="screen = 'cart'" class="w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-900 shadow-sm">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    </button>
                    <div x-show="cartCount() > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-brand text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white" x-text="cartCount()"></div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 pb-32">
            
            <!-- SCREEN: HOME (Menu List) -->
            <div x-show="screen === 'home'" class="animate-in fade-in slide-in-from-right duration-300">
                @if(count($promos) > 0)
                <!-- Promo Banner Carousel -->
                <section class="mt-6 px-4">
                    <div class="relative group" x-data="{ active: 0, timer: null }" x-init="timer = setInterval(() => active = (active + 1) % {{ count($promos) }}, 5000)">
                        <div class="flex overflow-hidden rounded-3xl h-44 shadow-xl bg-slate-100">
                            @foreach($promos as $index => $promo)
                            @php
                                $gradient = 'linear-gradient(135deg, #FF8C00 0%, #E8781A 100%)';
                                if($promo->promo_type == 'bundeling') $gradient = 'linear-gradient(135deg, #1D9E75 0%, #166534 100%)';
                                if($promo->promo_type == 'free_item') $gradient = 'linear-gradient(135deg, #4F46E5 0%, #3730A3 100%)';
                            @endphp
                            <div x-show="active === {{ $index }}" class="w-full h-full flex-shrink-0 relative p-6 text-white overflow-hidden transition-all duration-500"
                                 style="background: {{ $gradient }}">
                                <div class="relative z-10 w-2/3">
                                    <span class="text-[10px] font-black uppercase tracking-[0.2em] mb-2 block opacity-80">{{ str_replace('_', ' ', $promo->promo_type) }}</span>
                                    <h3 class="text-xl font-black leading-tight mb-2">{{ $promo->name }}</h3>
                                    <p class="text-[10px] font-medium opacity-90 line-clamp-2">{{ $promo->description }}</p>
                                </div>
                                @if($promo->image)
                                <img src="{{ asset('storage/promos/' . $promo->image) }}" class="absolute right-0 bottom-0 w-32 h-32 object-cover rounded-tl-[3rem] opacity-40 mix-blend-overlay rotate-12 translate-x-4 translate-y-4">
                                @else
                                <div class="absolute right-0 bottom-0 w-32 h-32 bg-white/20 rounded-tl-[3rem] flex items-center justify-center rotate-12 translate-x-4 translate-y-4">
                                    <i data-lucide="tag" class="w-16 h-16 opacity-30"></i>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        
                        @if(count($promos) > 1)
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5">
                            @foreach($promos as $index => $promo)
                            <div class="h-1.5 rounded-full transition-all duration-300" :class="active === {{ $index }} ? 'w-6 bg-white' : 'w-1.5 bg-white/40'"></div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </section>
                @endif

                <!-- Popular Menu -->
                @if($products->where('is_popular', true)->count() > 0)
                <section class="mt-8">
                    <div class="px-6 flex items-center justify-between mb-4">
                        <h3 class="font-black text-slate-900">Menu Populer</h3>
                    </div>
                    <div class="flex gap-4 overflow-x-auto no-scrollbar px-6 pb-4">
                        @foreach($products->where('is_popular', true) as $product)
                        <div @click="openProduct({{ json_encode($product) }})" class="w-40 shrink-0 bg-white p-3 rounded-4xl border border-slate-100 shadow-sm relative group active:scale-95 transition">
                            <div class="w-full aspect-square rounded-3xl bg-gray-50 mb-3 overflow-hidden border border-gray-100">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300' }}" class="w-full h-full object-cover">
                            </div>
                            <h4 class="font-black text-slate-900 text-[11px] mb-1 line-clamp-1">{{ $product->name }}</h4>
                            <div class="flex items-center justify-between">
                                <span class="font-black text-brand text-xs">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <div class="w-6 h-6 bg-brand rounded-lg flex items-center justify-center text-white">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- Category & Cuisine Filters -->
                <section class="mt-6 space-y-4">
                    <!-- Categories -->
                    <div class="overflow-x-auto no-scrollbar flex gap-2 px-6">
                        <button @click="activeCategory = 'all'" 
                                :class="activeCategory === 'all' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-white border-slate-100 text-slate-400'"
                                class="px-6 py-2.5 rounded-2xl font-black text-[10px] uppercase tracking-widest shrink-0 transition">Semua Kategori</button>
                        @foreach($categories as $category)
                        <button @click="activeCategory = {{ $category->id }}" 
                                :class="activeCategory === {{ $category->id }} ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-white border-slate-100 text-slate-400'"
                                class="px-6 py-2.5 border rounded-2xl text-[10px] font-black uppercase tracking-widest shrink-0 hover:border-brand/30 transition">{{ $category->name }}</button>
                        @endforeach
                    </div>

                    <!-- Cuisines (Regions) -->
                    <div class="overflow-x-auto no-scrollbar flex gap-2 px-6">
                        <button @click="activeCuisine = 'all'" 
                                :class="activeCuisine === 'all' ? 'bg-slate-900 text-white shadow-lg' : 'bg-white border-slate-100 text-slate-400'"
                                class="px-5 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest shrink-0 transition">Semua Daerah</button>
                        <template x-for="c in cuisines" :key="c">
                            <button @click="activeCuisine = c" 
                                    :class="activeCuisine === c ? 'bg-slate-900 text-white shadow-lg' : 'bg-white border-slate-100 text-slate-400'"
                                    class="px-5 py-2 border rounded-xl text-[9px] font-black uppercase tracking-widest shrink-0 transition" x-text="c"></button>
                        </template>
                    </div>

                    <!-- Tags Rasa -->
                    <div class="overflow-x-auto no-scrollbar flex gap-2 px-6">
                        <button @click="activeTag = 'all'" 
                                :class="activeTag === 'all' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-white border-slate-100 text-slate-400'"
                                class="px-5 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest shrink-0 transition">Semua Rasa</button>
                        <template x-for="tag in availableTags" :key="tag">
                            <button @click="activeTag = tag" 
                                    :class="activeTag === tag ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-white border-slate-100 text-slate-400'"
                                    class="px-5 py-2 border rounded-xl text-[9px] font-black uppercase tracking-widest shrink-0 transition" x-text="tag"></button>
                        </template>
                    </div>
                </section>

                <!-- Product Grid -->
                <section class="mt-8 px-6 pb-12">
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($products as $product)
                        <div x-show="(activeCategory === 'all' || activeCategory === {{ $product->category_id }}) && (activeCuisine === 'all' || activeCuisine === '{{ $product->cuisine }}') && (activeTag === 'all' || ({{ json_encode($product->tags) }} && {{ json_encode($product->tags) }}.includes(activeTag)))" 
                             @click="openProduct({{ json_encode($product) }})" 
                             class="bg-white p-3 rounded-4xl border border-slate-100 shadow-sm relative group overflow-hidden transition active:scale-95 animate-in fade-in zoom-in duration-300">
                            <div class="w-full aspect-square rounded-3xl bg-gray-50 mb-3 overflow-hidden border border-gray-100 relative">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300' }}" class="w-full h-full object-cover {{ !$product->is_available ? 'grayscale opacity-40' : '' }}">
                                @if(!$product->is_available)
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="bg-black/60 text-white px-3 py-1 rounded-lg font-black text-[10px] uppercase tracking-widest">Habis</span>
                                </div>
                                @endif
                                <!-- Category Tag (Optional overlay) -->
                                <div class="absolute top-2 left-2 flex gap-1">
                                    @foreach(($product->tags ?? []) as $tag)
                                    <span class="px-1.5 py-0.5 bg-white/90 backdrop-blur text-[7px] font-black uppercase rounded shadow-sm">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <h4 class="font-black text-slate-900 text-xs mb-1 line-clamp-1">{{ $product->name }}</h4>
                            <div class="flex items-center justify-between">
                                <span class="font-black text-brand text-sm tracking-tight">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <button @click.stop="{{ $product->is_available ? 'addToCart('.json_encode($product).', 1)' : '' }}" class="w-8 h-8 rounded-xl flex items-center justify-center text-white shadow-lg transition {{ $product->is_available ? 'bg-brand shadow-brand/20 active:scale-90' : 'bg-slate-200 cursor-not-allowed' }}">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <!-- SCREEN: KERANJANG (Cart tab) -->
            <div x-show="screen === 'cart'" class="animate-in fade-in slide-in-from-right duration-300 p-6">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-8">Keranjang</h2>
                
                <!-- Cart Items List -->
                <div class="space-y-4 mb-10">
                    <template x-if="cartItems.length === 0">
                        <div class="flex flex-col items-center justify-center py-20 opacity-30">
                            <i data-lucide="shopping-bag" class="w-20 h-20 mb-4"></i>
                            <p class="font-black uppercase tracking-widest text-xs">Keranjang Kosong</p>
                        </div>
                    </template>
                    <template x-for="item in cartItems" :key="item.id">
                        <div class="flex gap-4 p-4 bg-gray-50 rounded-4xl border border-gray-100 items-center">
                            <div class="w-16 h-16 rounded-2xl bg-white border border-gray-100 overflow-hidden shrink-0">
                                <img :src="item.image ? '{{ asset('storage') }}/' + item.image : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300'" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h4 class="font-black text-slate-900 text-sm" x-text="item.name"></h4>
                                <span class="font-black text-brand text-xs" x-text="'Rp' + item.price.toLocaleString()"></span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center p-0.5 bg-white rounded-xl border border-gray-100 shadow-sm">
                                    <button @click="updateQty(item.id, -1)" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 transition"><i data-lucide="minus" class="w-4 h-4"></i></button>
                                    <span class="w-8 text-center font-black text-sm" x-text="item.qty"></span>
                                    <button @click="updateQty(item.id, 1)" class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center shadow-lg active:scale-95 transition"><i data-lucide="plus" class="w-4 h-4"></i></button>
                                </div>
                                <button @click="removeFromCart(item.id)" class="w-8 h-8 flex items-center justify-center text-red-500">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Summary -->
                <div x-show="cartItems.length > 0" class="pt-8 border-t border-dashed border-slate-200 mb-10">
                    <h3 class="font-black text-slate-900 mb-6 tracking-tight">Ringkasan Biaya</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">Subtotal</span>
                            <span class="font-black text-slate-900" x-text="'Rp' + cartSubtotal().toLocaleString()"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">Pajak (10%)</span>
                            <span class="font-black text-slate-900" x-text="'Rp' + (cartSubtotal() * 0.1).toLocaleString()"></span>
                        </div>
                        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-lg font-black text-slate-900 tracking-tight">Total</span>
                            <span class="text-2xl font-black text-brand tracking-tighter" x-text="'Rp' + (cartSubtotal() * 1.1).toLocaleString()"></span>
                        </div>
                    </div>
                </div>

                <!-- Customer Name -->
                <div x-show="cartItems.length > 0" class="mb-8">
                    <h3 class="font-black text-slate-900 mb-4 tracking-tight">Nama Pelanggan</h3>
                    <input type="text" x-model="customerName" placeholder="Masukkan nama kamu" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-3xl focus:ring-2 focus:ring-brand outline-none font-bold shadow-sm">
                </div>

                <!-- Payment Method -->
                <div x-show="cartItems.length > 0" class="mb-10">
                    <h3 class="font-black text-slate-900 mb-4 tracking-tight">Pilih Metode Pembayaran</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="paymentMethod = 'qris'" class="p-6 rounded-4xl border-2 transition-all flex flex-col items-center gap-3" :class="paymentMethod === 'qris' ? 'border-brand bg-brand/5' : 'border-slate-100 bg-white'">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition" :class="paymentMethod === 'qris' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-gray-50 text-slate-400'">
                                <i data-lucide="qr-code" class="w-6 h-6"></i>
                            </div>
                            <span class="font-black uppercase tracking-widest text-[10px]" :class="paymentMethod === 'qris' ? 'text-brand' : 'text-slate-400'">QRIS</span>
                        </button>
                        <button @click="paymentMethod = 'cash'" class="p-6 rounded-4xl border-2 transition-all flex flex-col items-center gap-3" :class="paymentMethod === 'cash' ? 'border-brand bg-brand/5' : 'border-slate-100 bg-white'">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition" :class="paymentMethod === 'cash' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'bg-gray-50 text-slate-400'">
                                <i data-lucide="banknote" class="w-6 h-6"></i>
                            </div>
                            <span class="font-black uppercase tracking-widest text-[10px]" :class="paymentMethod === 'cash' ? 'text-brand' : 'text-slate-400'">Cash / Kasir</span>
                        </button>
                    </div>
                </div>

                <!-- Checkout Button -->
                <div x-show="cartItems.length > 0">
                    <button @click="checkout()" :disabled="isLoading" class="w-full py-5 bg-brand text-white rounded-3xl font-black text-xl shadow-2xl shadow-brand/30 transition active:scale-95 flex items-center justify-center gap-3 disabled:opacity-70">
                        <i x-show="isLoading" data-lucide="loader-2" class="w-6 h-6 animate-spin"></i>
                        <span x-text="isLoading ? 'Memproses...' : 'Pesan Sekarang'"></span>
                    </button>
                </div>
            </div>

            <!-- SCREEN: STATUS PESANAN tab -->
            <div x-show="screen === 'status'" class="animate-in fade-in slide-in-from-right duration-300 p-6 pb-20">
                <template x-if="!lastOrder">
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center text-slate-200 mb-8">
                            <i data-lucide="shopping-bag" class="w-16 h-16"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-900 mb-2 tracking-tight">Belum ada pesanan</h4>
                        <p class="text-slate-500 font-medium px-8 mb-10">Yuk pesan makanan favoritmu dulu!</p>
                        <button @click="screen = 'home'" class="px-10 py-4 bg-brand text-white rounded-2xl font-black shadow-xl shadow-brand/20">Lihat Menu</button>
                    </div>
                </template>

                <template x-if="lastOrder">
                    <div>
                        <div class="mb-10">
                            <h2 class="text-3xl font-black text-slate-900 tracking-tight" x-text="'Pesanan ' + lastOrder.id"></h2>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="tableName"></span>
                                <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="lastOrder.time"></span>
                            </div>
                        </div>

                        <!-- Progress Tracker -->
                        <div class="space-y-12 relative mb-12">
                            <div class="stepper-line"></div>
                            <div class="stepper-line active" :style="'height: ' + (orderStep * 60) + 'px'"></div>

                            <!-- Step 1 -->
                            <div class="flex gap-6 relative">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center z-10" :class="orderStep >= 1 ? 'bg-green-500 text-white shadow-lg shadow-green-200' : 'bg-slate-200 text-slate-400'">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1 -mt-1">
                                    <h4 class="font-black text-slate-900" :class="orderStep >= 1 ? 'opacity-100' : 'opacity-40'">Pesanan Diterima</h4>
                                    <p class="text-xs text-slate-500 font-medium" :class="orderStep >= 1 ? 'opacity-100' : 'opacity-40'">Pesanan kamu sudah diterima restoran</p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex gap-6 relative">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center z-10" :class="orderStep === 2 ? 'bg-blue-500 text-white shadow-lg shadow-blue-200' : (orderStep > 2 ? 'bg-green-500 text-white shadow-lg shadow-green-200' : 'bg-slate-200 text-slate-400')">
                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1 -mt-1">
                                    <h4 class="font-black text-slate-900" :class="orderStep >= 2 ? 'opacity-100' : 'opacity-40'">Sedang Diproses</h4>
                                    <p class="text-xs text-slate-500 font-medium" :class="orderStep >= 2 ? 'opacity-100' : 'opacity-40'">Dapur sedang menyiapkan pesananmu</p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex gap-6 relative">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center z-10" :class="orderStep === 3 ? 'bg-green-500 text-white shadow-lg shadow-green-200' : 'bg-slate-200 text-slate-400'">
                                    <i data-lucide="utensils" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1 -mt-1">
                                    <h4 class="font-black text-slate-900" :class="orderStep >= 3 ? 'opacity-100' : 'opacity-40'">Siap Disajikan</h4>
                                    <p class="text-xs text-slate-500 font-medium" :class="orderStep >= 3 ? 'opacity-100' : 'opacity-40'">Pesanan kamu sudah siap!</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="bg-gray-50 rounded-4xl p-8 mb-10">
                            <h3 class="font-black text-slate-900 mb-6">Detail Pesanan</h3>
                            <div class="space-y-4 mb-8">
                                <template x-for="item in lastOrder.items" :key="item.id">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="font-bold text-slate-600" x-text="item.qty + 'x ' + item.name"></span>
                                        <span class="font-black text-slate-900" x-text="'Rp' + (item.price * item.qty).toLocaleString()"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="pt-6 border-t border-slate-200 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Metode</span>
                                    <span class="text-xs font-black text-slate-900 uppercase tracking-widest" x-text="lastOrder.paymentMethod"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-black text-slate-900 tracking-tight">Total Akhir</span>
                                    <span class="text-xl font-black text-brand tracking-tighter" x-text="'Rp' + lastOrder.total.toLocaleString()"></span>
                                </div>
                            </div>
                        </div>

                        <button @click="screen = 'home'" class="w-full py-5 border-2 border-brand text-brand rounded-3xl font-black text-lg transition active:scale-95 flex items-center justify-center gap-3">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                            Pesan Lagi
                        </button>
                    </div>
                </template>
            </div>
        </main>

        <!-- Bottom Navigation Bar -->
        <nav class="fixed bottom-0 left-0 right-0 max-w-[430px] mx-auto glass border-t border-slate-100 safe-area-inset-bottom z-40 px-8 py-4 flex items-center justify-between shadow-2xl rounded-t-4xl">
            <button @click="screen = 'home'" class="flex flex-col items-center gap-1.5 transition-all" :class="screen === 'home' ? 'text-brand scale-110 font-bold' : 'text-slate-400'">
                <i data-lucide="home" class="w-6 h-6"></i>
                <span class="text-[9px] font-black uppercase tracking-widest">Menu</span>
            </button>
            <button @click="screen = 'cart'" class="flex flex-col items-center gap-1.5 transition-all relative" :class="screen === 'cart' ? 'text-brand scale-110 font-bold' : 'text-slate-400'">
                <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                <span class="text-[9px] font-black uppercase tracking-widest">Keranjang</span>
                <div x-show="cartCount() > 0" class="absolute -top-1 right-0 w-4 h-4 bg-brand text-white text-[8px] font-black rounded-full flex items-center justify-center border border-white" x-text="cartCount()"></div>
            </button>
            <button @click="screen = 'status'" class="flex flex-col items-center gap-1.5 transition-all relative" :class="screen === 'status' ? 'text-brand scale-110 font-bold' : 'text-slate-400'">
                <i data-lucide="receipt" class="w-6 h-6"></i>
                <span class="text-[9px] font-black uppercase tracking-widest">Status</span>
            </button>
        </nav>

        <!-- Product Detail Overlay -->
        <div x-show="showProductModal" class="fixed inset-0 z-[60] glass animate-in fade-in duration-300 overflow-y-auto" style="display: none;">
            <div class="max-w-[430px] mx-auto min-h-screen bg-white flex flex-col relative pb-32">
                <button @click="showProductModal = false" class="absolute top-6 left-6 z-10 w-12 h-12 bg-white/80 backdrop-blur rounded-2xl flex items-center justify-center shadow-2xl active:scale-95 transition">
                    <i data-lucide="chevron-left" class="w-6 h-6 text-slate-900"></i>
                </button>
                <div class="h-[45vh] bg-gray-100 overflow-hidden relative">
                    <img :src="productDetails.image ? '{{ asset('storage') }}/' + productDetails.image : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800'" class="w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white to-transparent"></div>
                </div>
                <div class="p-8 -mt-12 bg-white rounded-t-5xl relative z-20 flex-1">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-3 py-1 bg-brand/10 text-brand text-[10px] font-black uppercase rounded-lg" x-show="productDetails.is_popular">Menu Favorit</span>
                        <span class="px-3 py-1 bg-blue-50 text-blue-500 text-[10px] font-black uppercase rounded-lg">~15 menit</span>
                    </div>
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h2 class="text-3xl font-black text-slate-900 leading-tight mb-2" x-text="productDetails.name"></h2>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in (productDetails.tags || [])" :key="tag">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[9px] font-black uppercase rounded" x-text="tag"></span>
                                </template>
                            </div>
                        </div>
                        <span class="text-2xl font-black text-brand tracking-tighter" x-text="'Rp ' + (productDetails.price ?? 0).toLocaleString()"></span>
                    </div>
                    <div class="mb-8">
                        <h3 class="font-black text-slate-900 mb-3">Tentang Menu</h3>
                        <p class="text-slate-500 leading-relaxed font-bold text-sm" x-text="productDetails.description || 'Nikmati hidangan lezat dengan bahan-bahan pilihan berkualitas tinggi.'"></p>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <h3 class="font-black text-slate-900 mb-3">Info Alergen</h3>
                            <div class="flex gap-2">
                                <span class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-slate-400 font-bold text-[10px] uppercase">Kacang</span>
                                <span class="px-3 py-2 bg-gray-50 border border-gray-100 rounded-xl text-slate-400 font-bold text-[10px] uppercase">Susu</span>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 mb-3">Porsi</h3>
                            <div class="flex gap-3">
                                <button class="px-5 py-3 bg-brand text-white rounded-2xl font-black text-xs shrink-0 shadow-lg shadow-brand/20">Sendiri</button>
                                <button class="px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-slate-400 font-bold text-xs shrink-0">Sharing</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fixed bottom-0 left-0 right-0 max-w-[430px] mx-auto glass p-6 safe-area-inset-bottom flex items-center justify-between gap-6 shadow-2xl border-t border-slate-100 z-[70]">
                    <div class="flex items-center p-1 bg-gray-50 rounded-2xl border border-gray-100">
                        <button @click="tempQty = Math.max(1, tempQty - 1)" class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-900 shadow-sm transition"><i data-lucide="minus" class="w-5 h-5"></i></button>
                        <span class="w-12 text-center font-black text-lg" x-text="tempQty">1</span>
                        <button @click="tempQty++" class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-lg transition"><i data-lucide="plus" class="w-5 h-5"></i></button>
                    </div>
                    <button @click="addToCart(productDetails, tempQty)" class="flex-1 py-4 bg-brand text-white rounded-2xl font-black text-lg shadow-xl shadow-brand/30 transition active:scale-95 text-center">Tambah ke Keranjang</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Confirmation Modal -->
    <div x-show="showConfirmation" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300" style="display: none;">
        <div class="bg-white rounded-[2.5rem] p-10 w-full max-w-sm text-center shadow-2xl animate-in zoom-in-95 duration-300">
            <div class="w-20 h-20 bg-green-100 text-green-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <i data-lucide="check-circle" class="w-10 h-10"></i>
            </div>
            <h4 class="text-2xl font-black text-slate-900 mb-2 tracking-tight">Pesanan Terkirim!</h4>
            <p class="text-slate-500 font-medium mb-8 leading-relaxed">Pesanan kamu sudah dikirim ke dapur. Nomor pesanan: <span class="font-black text-slate-900" x-text="lastOrder ? lastOrder.id : ''"></span></p>
            <button @click="showConfirmation = false; screen = 'status'" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black shadow-xl">Pantau Pesanan</button>
        </div>
    </div>

    <!-- Rating & Review Modal -->
    <div x-show="showRatingModal" class="fixed inset-0 z-[110] flex items-center justify-center p-6 bg-slate-900/70 backdrop-blur-md animate-in fade-in duration-500" style="display: none;">
        <div class="bg-white rounded-[3rem] p-8 w-full max-w-md shadow-2xl animate-in slide-in-from-bottom-10 duration-500 relative overflow-hidden">
            <!-- Decorative background -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-brand/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 text-center">
                <div class="w-24 h-24 bg-brand/10 text-brand rounded-[2rem] flex items-center justify-center mx-auto mb-6 rotate-3">
                    <i data-lucide="star" class="w-12 h-12 fill-brand"></i>
                </div>
                
                <h3 class="text-2xl font-black text-slate-900 mb-2 tracking-tight">Pesanan Selesai!</h3>
                <p class="text-slate-500 font-medium mb-8">Bagaimana pengalamanmu makan di <span class="text-slate-900 font-black">{{ $restoName }}</span>?</p>
                
                <!-- Stars -->
                <div class="flex items-center justify-center gap-3 mb-8">
                    <template x-for="i in 5">
                        <button @click="ratingValue = i" class="transition-all duration-300 transform hover:scale-125">
                            <i data-lucide="star" 
                               :class="i <= ratingValue ? 'fill-amber-400 text-amber-400' : 'text-slate-200'"
                               class="w-10 h-10 transition-colors"></i>
                        </button>
                    </template>
                </div>
                
                <!-- Review Text -->
                <div class="mb-8">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 text-left px-2">Ceritakan pengalamanmu (Opsional)</label>
                    <textarea x-model="reviewText" rows="3" 
                        class="w-full p-5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm transition"
                        placeholder="Makanannya enak, pelayanannya ramah..."></textarea>
                </div>
                
                <!-- Actions -->
                <div class="flex flex-col gap-3">
                    <button @click="submitRating()" :disabled="isLoading" 
                        class="w-full py-5 bg-brand text-white rounded-3xl font-black text-lg shadow-xl shadow-brand/20 transition active:scale-95 flex items-center justify-center gap-3">
                        <i x-show="isLoading" data-lucide="loader-2" class="w-5 h-5 animate-spin"></i>
                        <span x-text="isLoading ? 'Mengirim...' : 'Kirim Ulasan'"></span>
                    </button>
                    <button @click="showRatingModal = false" class="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] py-2 hover:text-slate-600 transition">Nanti Saja</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function appData() {
            return {
                screen: 'home',
                activeCategory: 'all',
                activeCuisine: 'all',
                activeTag: 'all',
                products: {!! json_encode($products) !!},
                cuisines: ['Indonesian Food', 'Korean Food', 'Japanese Food', 'Western Food', 'Chinese Food', 'Thai Food', 'Arabic Food'],
                
                get availableTags() {
                    let tags = new Set();
                    this.products.forEach(p => {
                        if (p.tags && Array.isArray(p.tags)) {
                            p.tags.forEach(t => tags.add(t));
                        }
                    });
                    return Array.from(tags).sort();
                },

                tableName: 'Meja Default',
                customerName: '',
                cartItems: [],
                showProductModal: false,
                productDetails: {},
                tempQty: 1,
                paymentMethod: 'qris',
                showConfirmation: false,
                showRatingModal: false,
                ratingValue: 5,
                reviewText: '',
                ratingSubmitted: false,
                isLoading: false,
                lastOrder: null,
                orderStep: 1,
                
                initApp(tableName) {
                    this.tableName = tableName;
                    lucide.createIcons();

                    // Poll for order status updates
                    setInterval(() => {
                        if (this.lastOrder && this.screen === 'status' && this.lastOrder.status !== 'completed') {
                            this.fetchOrderStatus();
                        }
                    }, 5000);
                },

                async fetchOrderStatus() {
                    try {
                        const orderId = String(this.lastOrder.id).replace('#', '');
                        const response = await fetch(`/api/orders/track/${orderId}`);
                        if (response.ok) {
                            const data = await response.json();
                            
                            // Update UI states based on real status
                            if (data.status === 'pending') this.orderStep = 1;
                            else if (data.status === 'processing') this.orderStep = 2;
                            else if (data.status === 'completed') {
                                this.orderStep = 3;
                                this.lastOrder.status = 'completed';
                                
                                // Auto show rating modal if not submitted yet
                                if (!this.ratingSubmitted && !this.showRatingModal) {
                                    setTimeout(() => {
                                        this.showRatingModal = true;
                                        this.$nextTick(() => lucide.createIcons());
                                    }, 1500);
                                }
                            } else if (data.status === 'cancelled') {
                                this.orderStep = 0;
                            }
                        }
                    } catch (error) {
                        console.error('Failed to fetch order status:', error);
                    }
                },
                
                openProduct(product) {
                    this.productDetails = product;
                    this.tempQty = 1;
                    this.showProductModal = true;
                    this.$nextTick(() => lucide.createIcons());
                },
                
                addToCart(product, qty) {
                    const existing = this.cartItems.find(i => i.id === product.id);
                    if (existing) {
                        existing.qty += qty;
                    } else {
                        this.cartItems.push({
                            id: product.id,
                            name: product.name,
                            price: product.price,
                            image: product.image,
                            qty: qty
                        });
                    }
                    this.showProductModal = false;
                },
                
                updateQty(id, delta) {
                    const item = this.cartItems.find(i => i.id === id);
                    if (item) {
                        item.qty += delta;
                        if (item.qty <= 0) {
                            this.removeFromCart(id);
                        }
                    }
                },

                removeFromCart(id) {
                    this.cartItems = this.cartItems.filter(i => i.id !== id);
                },
                
                cartCount() {
                    return this.cartItems.reduce((acc, i) => acc + i.qty, 0);
                },
                
                cartSubtotal() {
                    return this.cartItems.reduce((acc, i) => acc + (i.price * i.qty), 0);
                },

                async checkout() {
                    if (this.cartItems.length === 0) return;
                    if (!this.customerName) {
                        alert('Mohon masukkan nama Anda terlebih dahulu');
                        return;
                    }
                    
                    this.isLoading = true;

                    try {
                        const payload = {
                            payment_method: this.paymentMethod,
                            name: this.customerName + ' (' + this.tableName + ')',
                            address: 'Dine-in ' + this.tableName,
                            phone: '-',
                            email: 'dinein@' + this.tableName.replace(/\s+/g, '').toLowerCase() + '.com',
                            items: this.cartItems.map(item => ({
                                product_id: item.id,
                                quantity: item.qty
                            }))
                        };

                        const response = await fetch('/api/checkout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        if (!response.ok) throw new Error('Gagal proses pesanan');
                        
                        const orderData = await response.json();

                        this.lastOrder = {
                            id: '#' + orderData.id,
                            status: 'pending',
                            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                            total: parseFloat(orderData.total_price),
                            paymentMethod: orderData.payment_method === 'qris' ? 'QRIS' : 'Bayar di Kasir',
                            items: [...this.cartItems]
                        };
                        
                        this.cartItems = [];
                        this.showConfirmation = true;
                        this.orderStep = 1;
                        this.ratingSubmitted = false;

                        this.$nextTick(() => lucide.createIcons());
                    } catch (error) {
                        alert('Terjadi kesalahan koneksi server. Gagal memesan.');
                    } finally {
                        this.isLoading = false;
                    }
                },

                async submitRating() {
                    if (this.isLoading) return;
                    this.isLoading = true;

                    try {
                        const orderId = String(this.lastOrder.id).replace('#', '');
                        const response = await fetch(`/api/orders/${orderId}/review`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                rating: this.ratingValue,
                                review: this.reviewText
                            })
                        });

                        if (!response.ok) throw new Error('Gagal mengirim ulasan');

                        this.ratingSubmitted = true;
                        this.showRatingModal = false;
                        alert('Terima kasih! Ulasan Anda telah kami terima.');
                    } catch (error) {
                        alert('Gagal mengirim ulasan. Silakan coba lagi.');
                    } finally {
                        this.isLoading = false;
                    }
                }
            };
        }
    </script>
</body>
</html>
