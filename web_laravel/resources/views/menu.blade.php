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
                                if($promo->promo_type == 'bundling') $gradient = 'linear-gradient(135deg, #1D9E75 0%, #166534 100%)';
                                if($promo->promo_type == 'free_item') $gradient = 'linear-gradient(135deg, #4F46E5 0%, #3730A3 100%)';
                            @endphp
                            <div x-show="active === {{ $index }}" 
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 translate-x-8"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 class="w-full h-full flex-shrink-0 relative p-7 text-white overflow-hidden transition-all duration-500"
                                 style="background: {{ $gradient }}">
                                
                                <div class="relative z-10 w-full h-full flex flex-col justify-between">
                                    <!-- Header Info -->
                                    <div>
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded text-[8px] font-black uppercase tracking-widest">{{ str_replace('_', ' ', $promo->promo_type) }}</span>
                                            <span class="text-[8px] font-black uppercase tracking-[0.2em] opacity-60">PROMO AKTIF</span>
                                        </div>
                                        <h3 class="text-2xl font-black leading-tight mb-1">{{ $promo->name }}</h3>
                                        <p class="text-[10px] font-medium opacity-80 line-clamp-1">{{ $promo->description }}</p>
                                    </div>

                                    <!-- Value & Min Purchase -->
                                    <div class="flex items-end justify-between">
                                        <div class="flex flex-col">
                                            <span class="text-[8px] font-black uppercase tracking-widest opacity-60 mb-0.5">Potongan Hingga</span>
                                            <div class="flex items-baseline gap-1">
                                                @if($promo->type == 'percentage')
                                                <span class="text-5xl font-black tracking-tighter">{{ $promo->value }}%</span>
                                                @else
                                                <span class="text-sm font-black uppercase">Rp</span>
                                                <span class="text-5xl font-black tracking-tighter">{{ number_format($promo->value/1000, 0) }}k</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($promo->min_purchase > 0)
                                        <div class="bg-black/10 backdrop-blur-md px-3 py-2 rounded-2xl flex flex-col items-end border border-white/10">
                                            <span class="text-[7px] font-black uppercase tracking-widest opacity-60 leading-none mb-1">Min. Belanja</span>
                                            <span class="text-xs font-black">Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                @if($promo->image)
                                <img src="{{ asset('storage/promos/' . $promo->image) }}" class="absolute right-0 bottom-0 w-36 h-36 object-cover rounded-tl-[3.5rem] opacity-30 mix-blend-overlay rotate-12 translate-x-6 translate-y-6">
                                @else
                                <div class="absolute right-0 bottom-0 w-36 h-36 bg-white/10 rounded-tl-[3.5rem] flex items-center justify-center rotate-12 translate-x-6 translate-y-6">
                                    <i data-lucide="tag" class="w-16 h-16 opacity-20"></i>
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

                <!-- Special Promo Products Section -->
                @php $promoProducts = $products->where('discount_percentage', '>', 0); @endphp
                @if($promoProducts->count() > 0)
                <section class="mt-8">
                    <div class="px-6 flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-red-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-200">
                                <i data-lucide="percent" class="w-4 h-4"></i>
                            </div>
                            <h3 class="font-black text-slate-900">Promo Spesial</h3>
                        </div>
                        <span class="text-[10px] font-black text-red-500 uppercase tracking-widest animate-pulse">Terbatas!</span>
                    </div>
                    <div class="flex gap-4 overflow-x-auto no-scrollbar px-6 pb-4">
                        @foreach($promoProducts as $product)
                        <div @click="openProduct({{ json_encode($product) }})" class="w-40 shrink-0 bg-white p-3 rounded-[2rem] border border-red-100 shadow-sm relative group active:scale-95 transition bg-gradient-to-br from-white to-red-50/30">
                            <div class="w-full aspect-square rounded-[1.5rem] bg-gray-50 mb-3 overflow-hidden border border-gray-100 relative">
                                <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300' }}" class="w-full h-full object-cover">
                                
                                <div class="absolute top-1.5 right-1.5 bg-red-500 text-white px-1.5 py-0.5 rounded-lg font-black text-[8px] shadow-lg shadow-red-500/20">
                                    -{{ $product->discount_percentage }}%
                                </div>

                                <div class="absolute bottom-1.5 left-1.5">
                                    <span class="px-1.5 py-0.5 bg-black/60 backdrop-blur text-white text-[7px] font-black uppercase rounded shadow-sm">{{ $product->cuisine }}</span>
                                </div>
                            </div>
                            <h4 class="font-black text-slate-900 text-[11px] mb-1 line-clamp-1">{{ $product->name }}</h4>
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[8px] font-bold text-slate-400 line-through leading-none mb-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="font-black text-brand text-xs tracking-tight">Rp {{ number_format($product->price * (1 - $product->discount_percentage/100), 0, ',', '.') }}</span>
                                </div>
                                <div class="w-7 h-7 bg-red-500 rounded-lg flex items-center justify-center text-white shadow-lg shadow-red-200">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
                @if($products->where('is_popular', true)->count() > 0)
                <section class="mt-8">
                    <div class="px-6 flex items-center justify-between mb-4">
                        <h3 class="font-black text-slate-900">Menu Populer</h3>
                    </div>
                    <div class="flex gap-4 overflow-x-auto no-scrollbar px-6 pb-4">
                        @foreach($products->where('is_popular', true) as $product)
                        <div @click="openProduct({{ json_encode($product) }})" class="w-40 shrink-0 bg-white p-3 rounded-[2rem] border border-slate-100 shadow-sm relative group active:scale-95 transition">
                            <div class="w-full aspect-square rounded-[1.5rem] bg-gray-50 mb-3 overflow-hidden border border-gray-100 relative">
                                <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300' }}" class="w-full h-full object-cover">
                                
                                @if($product->discount_percentage > 0)
                                <div class="absolute top-1.5 right-1.5 bg-red-500 text-white px-1.5 py-0.5 rounded-lg font-black text-[8px] shadow-lg shadow-red-500/20">
                                    -{{ $product->discount_percentage }}%
                                </div>
                                @endif

                                <div class="absolute bottom-1.5 left-1.5">
                                    <span class="px-1.5 py-0.5 bg-black/60 backdrop-blur text-white text-[7px] font-black uppercase rounded shadow-sm">{{ $product->cuisine }}</span>
                                </div>
                            </div>
                            <h4 class="font-black text-slate-900 text-[11px] mb-1 line-clamp-1">{{ $product->name }}</h4>
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    @if($product->discount_percentage > 0)
                                    <span class="text-[8px] font-bold text-slate-400 line-through leading-none mb-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="font-black text-brand text-xs tracking-tight">Rp {{ number_format($product->price * (1 - $product->discount_percentage/100), 0, ',', '.') }}</span>
                                    @else
                                    <span class="font-black text-brand text-xs">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <div class="w-7 h-7 bg-brand rounded-lg flex items-center justify-center text-white shadow-lg shadow-brand/20">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- Search & Filters -->
                <section class="mt-6 space-y-4 px-6">
                    <!-- Search Bar -->
                    <div class="relative">
                        <input type="text" x-model="searchQuery" placeholder="Cari menu favoritmu..." 
                            class="w-full bg-white border border-slate-100 rounded-3xl py-4 pl-12 pr-6 text-sm font-medium shadow-sm outline-none focus:ring-2 focus:ring-brand transition">
                        <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    </div>

                    <!-- Consolidated Dropdown Filters -->
                    <div class="grid grid-cols-3 gap-2">
                        <!-- Category Selector -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                :class="(activeCategory !== 'all') ? 'bg-brand text-white border-brand' : 'bg-white text-slate-600 border-slate-100'"
                                class="w-full h-11 flex items-center justify-between px-3 border rounded-2xl transition shadow-sm active:scale-95">
                                <span class="text-[9px] font-black uppercase tracking-widest truncate mr-1" 
                                    x-text="activeCategory === 'all' ? 'Kategori' : document.querySelector('[data-cat-id=\'' + activeCategory + '\']')?.innerText || 'Kategori'"></span>
                                <i data-lucide="chevron-down" class="w-3 h-3 opacity-50"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition 
                                class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 max-h-60 overflow-y-auto py-2">
                                <button @click="activeCategory = 'all'; open = false" class="w-full text-left px-4 py-3 text-[9px] font-black uppercase hover:bg-slate-50 border-b border-slate-50">Semua</button>
                                @foreach($categories as $category)
                                <button @click="activeCategory = {{ $category->id }}; open = false" 
                                    data-cat-id="{{ $category->id }}"
                                    class="w-full text-left px-4 py-3 text-[9px] font-black uppercase hover:bg-slate-50 border-b border-slate-50">{{ $category->name }}</button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Cuisine Selector -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                :class="(activeCuisine !== 'all') ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-100'"
                                class="w-full h-11 flex items-center justify-between px-3 border rounded-2xl transition shadow-sm active:scale-95">
                                <span class="text-[9px] font-black uppercase tracking-widest truncate mr-1" 
                                    x-text="activeCuisine === 'all' ? 'Daerah' : activeCuisine"></span>
                                <i data-lucide="chevron-down" class="w-3 h-3 opacity-50"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition 
                                class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 max-h-60 overflow-y-auto py-2">
                                <button @click="activeCuisine = 'all'; open = false" class="w-full text-left px-4 py-3 text-[9px] font-black uppercase hover:bg-slate-50 border-b border-slate-50">Semua</button>
                                <template x-for="c in cuisines" :key="c">
                                    <button @click="activeCuisine = c; open = false" class="w-full text-left px-4 py-3 text-[9px] font-black uppercase hover:bg-slate-50 border-b border-slate-50" x-text="c"></button>
                                </template>
                            </div>
                        </div>

                        <!-- Taste Selector -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                :class="(activeTag !== 'all') ? 'bg-brand text-white border-brand' : 'bg-white text-slate-600 border-slate-100'"
                                class="w-full h-11 flex items-center justify-between px-3 border rounded-2xl transition shadow-sm active:scale-95">
                                <span class="text-[9px] font-black uppercase tracking-widest truncate mr-1" 
                                    x-text="activeTag === 'all' ? 'Rasa' : activeTag"></span>
                                <i data-lucide="chevron-down" class="w-3 h-3 opacity-50"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition 
                                class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 max-h-60 overflow-y-auto py-2">
                                <button @click="activeTag = 'all'; open = false" class="w-full text-left px-4 py-3 text-[9px] font-black uppercase hover:bg-slate-50 border-b border-slate-50">Semua</button>
                                <template x-for="tag in availableTags" :key="tag">
                                    <button @click="activeTag = tag; open = false" class="w-full text-left px-4 py-3 text-[9px] font-black uppercase hover:bg-slate-50 border-b border-slate-50" x-text="tag"></button>
                                </template>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Product Grid -->
                <section class="mt-8 px-6 pb-24">
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($products as $product)
                        <div x-show="(activeCategory === 'all' || activeCategory === {{ $product->category_id }}) && (activeCuisine === 'all' || activeCuisine === '{{ $product->cuisine }}') && (activeTag === 'all' || ({{ json_encode($product->tags) }} && {{ json_encode($product->tags) }}.includes(activeTag))) && (searchQuery === '' || '{{ strtolower($product->name) }}'.includes(searchQuery.toLowerCase()))" 
                             @click="openProduct({{ json_encode($product) }})" 
                             class="bg-white p-3 rounded-[2.5rem] border border-slate-100 shadow-sm relative group overflow-hidden transition active:scale-95 animate-in fade-in zoom-in duration-300">
                            
                            <!-- Product Image & Badges -->
                            <div class="w-full aspect-square rounded-[2rem] bg-gray-50 mb-3 overflow-hidden border border-gray-100 relative">
                                <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300' }}" class="w-full h-full object-cover {{ !$product->is_available ? 'grayscale opacity-40' : '' }} group-hover:scale-110 transition duration-500">
                                
                                <!-- Discount Badge -->
                                @if($product->discount_percentage > 0)
                                <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-xl font-black text-[9px] shadow-lg shadow-red-500/20 animate-pulse">
                                    -{{ $product->discount_percentage }}%
                                </div>
                                @endif

                                <!-- Cuisine Badge -->
                                <div class="absolute bottom-2 left-2">
                                    <span class="px-2 py-1 bg-black/60 backdrop-blur text-white text-[8px] font-black uppercase rounded-lg shadow-sm">{{ $product->cuisine }}</span>
                                </div>

                                @if(!$product->is_available)
                                <div class="absolute inset-0 flex items-center justify-center bg-white/40">
                                    <span class="bg-slate-900 text-white px-3 py-1.5 rounded-xl font-black text-[9px] uppercase tracking-widest shadow-xl">HABIS</span>
                                </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <h4 class="font-black text-slate-900 text-xs mb-1 line-clamp-1 px-1">{{ $product->name }}</h4>
                            
                            <!-- Tags (Limited to 2 for grid) -->
                            <div class="flex flex-wrap gap-1 mb-2 px-1">
                                @foreach(array_slice(($product->tags ?? []), 0, 2) as $tag)
                                <span class="px-1.5 py-0.5 bg-slate-50 text-slate-400 text-[7px] font-black uppercase rounded shadow-sm border border-slate-100">{{ $tag }}</span>
                                @endforeach
                            </div>

                            <div class="flex items-center justify-between px-1">
                                <div class="flex flex-col">
                                    @if($product->discount_percentage > 0)
                                    <span class="text-[9px] font-bold text-slate-400 line-through leading-none mb-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="font-black text-brand text-sm tracking-tight">Rp {{ number_format($product->price * (1 - $product->discount_percentage/100), 0, ',', '.') }}</span>
                                    @else
                                    <span class="font-black text-brand text-sm tracking-tight">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <button @click.stop="{{ $product->is_available ? 'addToCart('.json_encode($product).', 1)' : '' }}" class="w-8 h-8 rounded-xl flex items-center justify-center text-white shadow-lg transition {{ $product->is_available ? 'bg-brand shadow-brand/20 active:scale-90 hover:bg-brand/90' : 'bg-slate-200 cursor-not-allowed' }}">
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
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">Keranjang</h2>
                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg font-black text-[10px] uppercase tracking-widest" x-text="cartCount() + ' Items'"></span>
                </div>
                
                <!-- Cart Items List -->
                <div class="space-y-4 mb-10">
                    <template x-if="cartItems.length === 0">
                        <div class="flex flex-col items-center justify-center py-20 opacity-30">
                            <i data-lucide="shopping-bag" class="w-20 h-20 mb-4 text-slate-200"></i>
                            <p class="font-black uppercase tracking-widest text-xs text-slate-400">Keranjang Kosong</p>
                        </div>
                    </template>
                    <template x-for="item in cartItems" :key="item.id">
                        <div class="flex gap-4 p-4 bg-white rounded-4xl border border-slate-100 items-center shadow-sm">
                            <div class="w-20 h-20 rounded-3xl bg-slate-50 border border-slate-100 overflow-hidden shrink-0 relative">
                                <img :src="item.image ? (item.image.startsWith('http') ? item.image : '{{ asset('storage') }}/' + item.image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300'" class="w-full h-full object-cover">
                                <template x-if="item.discountPercentage > 0">
                                    <div class="absolute top-1 right-1 bg-red-500 text-white px-1.5 py-0.5 rounded-lg font-black text-[7px]">-<span x-text="item.discountPercentage"></span>%</div>
                                </template>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-black text-slate-900 text-sm mb-1" x-text="item.name"></h4>
                                <div class="flex items-center gap-2">
                                    <span class="font-black text-brand text-sm" x-text="formatMoney(item.price)"></span>
                                    <template x-if="item.discountPercentage > 0">
                                        <span class="text-[10px] font-bold text-slate-400 line-through" x-text="formatMoney(item.originalPrice)"></span>
                                    </template>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center p-1 bg-slate-50 rounded-[1.25rem] border border-slate-100 shadow-inner">
                                    <button @click="updateQty(item.id, -1)" class="w-10 h-10 rounded-[1rem] bg-white flex items-center justify-center text-slate-400 hover:text-red-500 shadow-sm border border-slate-100 transition active:scale-90">
                                        <i data-lucide="minus" class="w-5 h-5"></i>
                                    </button>
                                    <span class="w-10 text-center font-black text-slate-900 text-sm" x-text="item.qty"></span>
                                    <button @click="updateQty(item.id, 1)" class="w-10 h-10 rounded-[1rem] bg-slate-900 text-white flex items-center justify-center shadow-lg active:scale-95 transition">
                                        <i data-lucide="plus" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Auto Promo Alert -->
                <template x-if="activePromo">
                    <div class="mb-8 p-5 bg-emerald-50 border border-emerald-100 rounded-4xl flex items-center gap-4 animate-in zoom-in duration-500">
                        <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200 shrink-0">
                            <i data-lucide="party-popper" class="w-6 h-6"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Promo Otomatis Terpasang!</p>
                            <h4 class="font-black text-slate-900 text-sm" x-text="activePromo.name"></h4>
                        </div>
                        <div class="bg-white px-3 py-2 rounded-2xl border border-emerald-100 text-emerald-600 font-black text-xs">
                            -<span x-text="formatMoney(promoDiscount())"></span>
                        </div>
                    </div>
                </template>

                <!-- Summary -->
                <div x-show="cartItems.length > 0" class="pt-8 border-t border-dashed border-slate-200 mb-10 bg-slate-50/50 p-6 rounded-5xl border border-slate-100">
                    <h3 class="font-black text-slate-900 mb-6 tracking-tight flex items-center gap-2">
                        <i data-lucide="receipt" class="w-5 h-5 text-slate-400"></i>
                        Ringkasan Biaya
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Subtotal</span>
                            <span class="font-black text-slate-900 text-sm" x-text="formatMoney(cartSubtotal())"></span>
                        </div>
                        
                        <template x-if="promoDiscount() > 0">
                            <div class="flex items-center justify-between text-emerald-600">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em]">Diskon Promo</span>
                                <span class="font-black text-sm" x-text="'- ' + formatMoney(promoDiscount())"></span>
                            </div>
                        </template>

                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pajak (10%)</span>
                            <span class="font-black text-slate-900 text-sm" x-text="formatMoney(cartTax())"></span>
                        </div>
                        
                        <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-1">Total Pembayaran</span>
                                <span class="text-3xl font-black text-brand tracking-tighter" x-text="formatMoney(cartTotal())"></span>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1.5 bg-brand/10 text-brand rounded-xl font-black text-[9px] uppercase tracking-widest">Siap Dipesan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Name -->
                <div x-show="cartItems.length > 0" class="mb-8">
                    <h3 class="font-black text-slate-900 mb-4 tracking-tight">Nama Pelanggan</h3>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-6 flex items-center text-slate-400">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input type="text" x-model="customerName" placeholder="Masukkan nama kamu" class="w-full pl-16 pr-6 py-5 bg-white border border-slate-100 rounded-[2rem] focus:ring-2 focus:ring-brand outline-none font-bold shadow-sm">
                    </div>
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
                    <button @click="checkout()" :disabled="isLoading || !customerName" class="w-full py-5 bg-brand text-white rounded-3xl font-black text-xl shadow-2xl shadow-brand/30 transition active:scale-95 flex items-center justify-center gap-3 disabled:opacity-50">
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
        <div x-show="showProductModal" class="fixed inset-0 z-[60] bg-white animate-in fade-in duration-300 overflow-y-auto" style="display: none;">
            <div class="max-w-[430px] mx-auto min-h-screen bg-white flex flex-col relative pb-40">
                <button @click="showProductModal = false" class="absolute top-6 left-6 z-50 w-12 h-12 bg-white/90 backdrop-blur rounded-2xl flex items-center justify-center shadow-2xl active:scale-95 transition">
                    <i data-lucide="chevron-left" class="w-6 h-6 text-slate-900"></i>
                </button>
                <div class="h-[45vh] bg-gray-100 overflow-hidden relative">
                    <img :src="productDetails.image ? (productDetails.image.startsWith('http') ? productDetails.image : '{{ asset('storage') }}/' + productDetails.image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800'" class="w-full h-full object-cover">
                    
                    <!-- Discount Badge in Modal -->
                    <template x-if="productDetails.discount_percentage > 0">
                        <div class="absolute top-24 right-6 bg-red-500 text-white px-4 py-2 rounded-2xl font-black text-sm shadow-2xl animate-bounce">
                            HEMAT <span x-text="productDetails.discount_percentage"></span>%
                        </div>
                    </template>
                    
                    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white to-transparent"></div>
                </div>
                <div class="p-8 -mt-12 bg-white rounded-t-5xl relative z-20 flex-1">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-3 py-1 bg-brand/10 text-brand text-[10px] font-black uppercase rounded-lg" x-show="productDetails.is_popular">Menu Favorit</span>
                        <span class="px-3 py-1 bg-slate-900 text-white text-[10px] font-black uppercase rounded-lg" x-show="productDetails.cuisine" x-text="productDetails.cuisine"></span>
                        <span class="px-3 py-1 bg-blue-50 text-blue-500 text-[10px] font-black uppercase rounded-lg">~15 menit</span>
                    </div>
                    <div class="flex items-start justify-between mb-6">
                        <div class="max-w-[70%]">
                            <h2 class="text-3xl font-black text-slate-900 leading-tight mb-2" x-text="productDetails.name"></h2>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in (productDetails.tags || [])" :key="tag">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[9px] font-black uppercase rounded" x-text="tag"></span>
                                </template>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <template x-if="productDetails.discount_percentage > 0">
                                <span class="text-xs font-bold text-slate-400 line-through mb-1" x-text="formatMoney(productDetails.price ?? 0)"></span>
                            </template>
                            <span class="text-2xl font-black text-brand tracking-tighter" x-text="formatMoney(productDetails.discount_percentage > 0 ? (productDetails.price * (1 - productDetails.discount_percentage/100)) : (productDetails.price ?? 0))"></span>
                        </div>
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
                <div class="fixed bottom-0 left-0 right-0 max-w-[430px] mx-auto bg-white/95 backdrop-blur-md p-6 safe-area-inset-bottom flex items-center justify-between gap-6 shadow-[0_-20px_40px_rgba(0,0,0,0.05)] border-t border-slate-100 z-[70]">
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
                screen: 'home', // home, detail, cart, checkout, status
                activeCategory: 'all',
                activeCuisine: 'all',
                activeTag: 'all',
                searchQuery: '',
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
                    
                    // Load persistence
                    const savedCart = localStorage.getItem('cartItems');
                    if (savedCart) this.cartItems = JSON.parse(savedCart);
                    
                    const savedOrder = localStorage.getItem('lastOrder');
                    if (savedOrder) {
                        this.lastOrder = JSON.parse(savedOrder);
                        if (this.lastOrder.status !== 'completed') {
                            this.screen = 'status';
                        }
                    }

                    const savedName = localStorage.getItem('customerName');
                    if (savedName) this.customerName = savedName;

                    lucide.createIcons();

                    // Auto-save logic
                    this.$watch('cartItems', (value) => {
                        localStorage.setItem('cartItems', JSON.stringify(value));
                    });
                    this.$watch('lastOrder', (value) => {
                        if (value) localStorage.setItem('lastOrder', JSON.stringify(value));
                        else localStorage.removeItem('lastOrder');
                    });
                    this.$watch('customerName', (value) => {
                        localStorage.setItem('customerName', value);
                    });

                    this.$watch('screen', () => {
                        this.$nextTick(() => lucide.createIcons());
                    });

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
                    // Calculate effective price based on discount
                    const effectivePrice = product.discount_percentage > 0 
                        ? (product.price * (1 - product.discount_percentage/100)) 
                        : product.price;

                    if (existing) {
                        existing.qty += qty;
                    } else {
                        this.cartItems.push({
                            id: product.id,
                            name: product.name,
                            price: effectivePrice,
                            originalPrice: product.price,
                            discountPercentage: product.discount_percentage || 0,
                            image: product.image,
                            qty: qty
                        });
                    }
                    this.showProductModal = false;
                    this.$nextTick(() => lucide.createIcons());
                },
                
                updateQty(id, delta) {
                    const item = this.cartItems.find(i => i.id === id);
                    if (item) {
                        item.qty += delta;
                        if (item.qty <= 0) {
                            this.removeFromCart(id);
                        }
                        this.$nextTick(() => lucide.createIcons());
                    }
                },

                removeFromCart(id) {
                    this.cartItems = this.cartItems.filter(i => i.id !== id);
                    this.$nextTick(() => lucide.createIcons());
                },
                
                cartCount() {
                    return this.cartItems.reduce((acc, i) => acc + i.qty, 0);
                },
                
                cartSubtotal() {
                    return this.cartItems.reduce((acc, i) => acc + (i.price * i.qty), 0);
                },

                get activePromo() {
                    const subtotal = this.cartSubtotal();
                    if (subtotal === 0) return null;
                    
                    const availablePromos = {!! json_encode($promos) !!}.filter(p => {
                        return subtotal >= (p.min_purchase || 0);
                    });

                    if (availablePromos.length === 0) return null;

                    // Return promo with highest discount value
                    return availablePromos.reduce((prev, curr) => {
                        let prevVal = prev.type === 'percentage' ? (subtotal * prev.value / 100) : prev.value;
                        let currVal = curr.type === 'percentage' ? (subtotal * curr.value / 100) : curr.value;
                        return currVal > prevVal ? curr : prev;
                    });
                },

                promoDiscount() {
                    const promo = this.activePromo;
                    if (!promo) return 0;
                    const subtotal = this.cartSubtotal();
                    return promo.type === 'percentage' ? (subtotal * promo.value / 100) : promo.value;
                },

                cartTax() {
                    const base = this.cartSubtotal() - this.promoDiscount();
                    return Math.round(base * 0.1);
                },

                cartTotal() {
                    return this.cartSubtotal() - this.promoDiscount() + this.cartTax();
                },
                
                formatMoney(val) {
                    return 'Rp ' + Math.round(val).toLocaleString('id-ID');
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
                            promo_code: this.activePromo ? this.activePromo.code : null,
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
