@extends('layouts.admin')

@section('title', 'Kelola Menu')
@section('page_title', 'Kelola Menu')

@section('content')
<div class="flex flex-col h-full" x-data="productManager()" x-init="init()">
    <!-- Header Row -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10 shrink-0">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <button @click="openAddModal()" class="px-6 py-4 bg-brand text-white rounded-2xl font-black text-sm transition shadow-xl shadow-brand/20 hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>TAMBAH MENU</span>
            </button>
            
            <div class="flex p-1 bg-white border border-slate-200 rounded-xl shadow-sm">
                <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-slate-100 text-slate-900' : 'text-slate-400 hover:text-brand'" class="p-2.5 rounded-lg transition"><i data-lucide="layout-grid" class="w-5 h-5"></i></button>
                <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-slate-100 text-slate-900' : 'text-slate-400 hover:text-brand'" class="p-2.5 rounded-lg transition"><i data-lucide="list" class="w-5 h-5"></i></button>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <!-- Filter Kategori -->
            <div x-data="{ open: false }" class="relative z-20">
                <button @click="open = !open" @click.outside="open = false" type="button" class="px-5 py-3.5 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-600 outline-none hover:border-brand shadow-sm flex items-center justify-between gap-4 min-w-[180px] transition">
                    <span x-text="filterCategory === '' ? 'Semua Kategori' : categories.find(c => String(c.id) === String(filterCategory))?.name || 'Semua Kategori'"></span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute top-full mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden py-2">
                    <button @click="filterCategory = ''; open = false" class="w-full text-left px-5 py-3 text-xs font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Semua Kategori</button>
                    <template x-for="category in categories" :key="category.id">
                        <button @click="filterCategory = category.id; open = false" class="w-full text-left px-5 py-3 text-xs font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition" x-text="category.name"></button>
                    </template>
                </div>
            </div>

            <!-- Filter Asal Makanan -->
            <div x-data="{ open: false }" class="relative z-20">
                <button @click="open = !open" @click.outside="open = false" type="button" class="px-5 py-3.5 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-600 outline-none hover:border-brand shadow-sm flex items-center justify-between gap-4 min-w-[170px] transition">
                    <span x-text="filterCuisine === '' ? 'Semua Asal' : filterCuisine"></span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute top-full mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden py-2">
                    <button @click="filterCuisine = ''; open = false" class="w-full text-left px-5 py-3 text-xs font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Semua Asal</button>
                    <template x-for="c in cuisines" :key="c">
                        <button @click="filterCuisine = c; open = false" class="w-full text-left px-5 py-3 text-xs font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition" x-text="c"></button>
                    </template>
                </div>
            </div>

            <!-- Filter Status -->
            <div x-data="{ open: false }" class="relative z-20">
                <button @click="open = !open" @click.outside="open = false" type="button" class="px-5 py-3.5 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-600 outline-none hover:border-brand shadow-sm flex items-center justify-between gap-4 min-w-[160px] transition">
                    <span x-text="filterStatus === '' ? 'Semua Status' : (filterStatus === '1' ? 'Tersedia' : 'Habis')"></span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute top-full mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden py-2">
                    <button @click="filterStatus = ''; open = false" class="w-full text-left px-5 py-3 text-xs font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Semua Status</button>
                    <button @click="filterStatus = '1'; open = false" class="w-full text-left px-5 py-3 text-xs font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Tersedia</button>
                    <button @click="filterStatus = '0'; open = false" class="w-full text-left px-5 py-3 text-xs font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Habis</button>
                </div>
            </div>

            <!-- Filter Tag Rasa -->
            <div x-data="{ open: false }" class="relative z-20">
                <button @click="open = !open" @click.outside="open = false" type="button" class="px-5 py-3.5 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-600 outline-none hover:border-brand shadow-sm flex items-center justify-between gap-4 min-w-[160px] transition">
                    <span x-text="filterTag === '' ? 'Semua Rasa' : filterTag"></span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute top-full mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden py-2 z-30 max-h-60 overflow-y-auto custom-scrollbar">
                    <button @click="filterTag = ''; open = false" class="w-full text-left px-5 py-3 text-xs font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Semua Rasa</button>
                    <template x-for="tag in availableTags" :key="tag">
                        <button @click="filterTag = tag; open = false" class="w-full text-left px-5 py-3 text-xs font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition" x-text="tag"></button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Grid/List -->
    <div class="flex-1 overflow-y-auto custom-scrollbar pr-4 pb-10">
        <div :class="viewMode === 'grid' ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8' : 'flex flex-col gap-4'">
            
            <template x-for="product in filteredProducts" :key="product.id">
                <div :class="viewMode === 'grid' ? 'flex-col' : 'flex-row items-center'" class="flex bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 relative" :class="!product.is_available ? 'opacity-80' : ''">
                    
                    <div :class="viewMode === 'grid' ? 'h-48 w-full' : 'h-32 w-48 shrink-0'" class="relative overflow-hidden" :class="!product.is_available ? 'grayscale' : ''">
                        <img :src="product.image ? (product.image.startsWith('http') ? product.image : '/storage/' + product.image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&auto=format&fit=crop'" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        
                        <template x-if="!product.is_available">
                            <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center">
                                <span class="px-4 py-2 bg-white text-slate-900 text-[10px] font-black uppercase rounded-xl tracking-widest shadow-xl">STOK HABIS</span>
                            </div>
                        </template>

                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur text-brand text-[9px] font-black uppercase rounded-lg shadow-sm border border-brand/10" x-text="product.category?.name || 'Umum'"></span>
                        </div>

                        <div class="absolute top-4 right-4 flex gap-2">
                            <button @click="openEditModal(product)" class="p-2 bg-white/90 backdrop-blur text-slate-400 hover:text-blue-500 rounded-lg shadow-sm transition"><i data-lucide="pencil" class="w-4 h-4"></i></button>
                            <button @click="deleteProduct(product.id)" class="p-2 bg-white/90 backdrop-blur text-slate-400 hover:text-red-500 rounded-lg shadow-sm transition"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-0.5 bg-red-50 text-red-500 text-[8px] font-black uppercase rounded-md border border-red-100" x-show="product.is_popular">Populer</span>
                                <span class="px-2 py-0.5 bg-rose-500 text-white text-[8px] font-black uppercase rounded-md shadow-sm" x-show="product.discount_percentage > 0" x-text="'-' + product.discount_percentage + '%'"></span>
                                <span class="px-2 py-0.5 bg-brand/5 text-brand text-[8px] font-black uppercase rounded-md border border-brand/10" x-show="product.cuisine" x-text="product.cuisine"></span>
                            </div>
                            <h3 class="text-base font-black text-slate-900 mb-1 leading-tight" :class="!product.is_available ? 'text-slate-400' : ''" x-text="product.name"></h3>
                            
                            <!-- Tags Rasa -->
                            <div class="flex flex-wrap gap-1.5 mb-3" x-show="product.tags && product.tags.length > 0">
                                <template x-for="tag in product.tags" :key="tag">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[8px] font-bold rounded-md border border-slate-200" x-text="tag"></span>
                                </template>
                            </div>

                            <div class="flex flex-col mb-4">
                                <template x-if="product.discount_percentage > 0">
                                    <span class="text-[10px] font-bold text-slate-400 line-through leading-none mb-1" x-text="formatPrice(product.price)"></span>
                                </template>
                                <p class="text-lg font-black text-brand tracking-tighter leading-none" :class="!product.is_available ? 'opacity-50' : ''" x-text="formatPrice(product.discount_percentage > 0 ? (product.price * (1 - product.discount_percentage/100)) : product.price)"></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ketersediaan</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" :checked="product.is_available" @change="toggleAvailability(product)">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="filteredProducts.length === 0">
                <div class="col-span-full flex flex-col items-center justify-center py-24 text-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="package-search" class="w-10 h-10"></i>
                    </div>
                    <p class="font-black uppercase tracking-widest text-[10px]">Tidak ada menu ditemukan</p>
                </div>
            </template>

        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-10 px-4 pb-10 shrink-0" x-show="totalPages > 1">
        <div class="flex flex-col gap-1">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                Menampilkan <span class="text-slate-900" x-text="((currentPage - 1) * itemsPerPage) + 1"></span> - 
                <span class="text-slate-900" x-text="Math.min(currentPage * itemsPerPage, baseFilteredProducts.length)"></span> 
                dari <span class="text-slate-900" x-text="baseFilteredProducts.length"></span> menu
            </p>
            <div class="h-1 w-20 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-brand transition-all duration-500" :style="'width: ' + (currentPage / totalPages * 100) + '%'"></div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button @click="currentPage--; $nextTick(() => lucide.createIcons())" :disabled="currentPage === 1" class="p-3 bg-white border border-slate-200 rounded-2xl text-slate-400 hover:text-brand hover:border-brand transition shadow-sm disabled:opacity-30 disabled:cursor-not-allowed">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </button>
            <div class="flex gap-1.5">
                <template x-for="page in totalPages" :key="page">
                    <button @click="currentPage = page; $nextTick(() => lucide.createIcons())" :class="currentPage === page ? 'bg-brand text-white shadow-xl shadow-brand/20' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-100'" class="w-11 h-11 rounded-2xl font-black text-xs transition" x-text="page"></button>
                </template>
            </div>
            <button @click="currentPage++; $nextTick(() => lucide.createIcons())" :disabled="currentPage === totalPages" class="p-3 bg-white border border-slate-200 rounded-2xl text-slate-400 hover:text-brand hover:border-brand transition shadow-sm disabled:opacity-30 disabled:cursor-not-allowed">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <!-- Modal Tambah Menu -->
    <div x-show="isAddModalOpen" style="display: none;" class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex justify-end">
        <div @click.outside="closeAddModal()" class="bg-white w-full max-w-2xl h-full shadow-2xl overflow-y-auto custom-scrollbar animate-in slide-in-from-right duration-300">
            <div class="p-10">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Tambah Produk Baru</h3>
                        <p class="text-sm font-medium text-slate-500">Lengkapi detail produk dengan benar.</p>
                    </div>
                    <button @click="closeAddModal()" class="p-3 bg-slate-50 text-slate-400 hover:text-slate-600 rounded-2xl transition"><i data-lucide="x" class="w-6 h-6"></i></button>
                </div>

                <form action="/admin/products" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
                    @csrf
                    
                    <!-- Foto Upload / Link -->
                    <div class="relative group">
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Foto Produk</label>
                            <div class="flex bg-slate-100 p-1 rounded-lg">
                                <button type="button" @click="addImageMode = 'upload'" :class="addImageMode === 'upload' ? 'bg-white text-brand shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-3 py-1 text-[10px] font-bold rounded-md transition">Upload</button>
                                <button type="button" @click="addImageMode = 'url'" :class="addImageMode === 'url' ? 'bg-white text-brand shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-3 py-1 text-[10px] font-bold rounded-md transition">Link URL</button>
                            </div>
                        </div>

                        <!-- Mode Upload -->
                        <div x-show="addImageMode === 'upload'">
                            <div class="w-full h-48 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] flex flex-col items-center justify-center gap-2 cursor-pointer hover:border-brand hover:bg-brand/5 transition group relative overflow-hidden" onclick="document.getElementById('add_image_input').click()">
                                <img id="add_image_preview" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                                <i id="add_image_icon" data-lucide="camera" class="w-10 h-10 text-slate-300 group-hover:text-brand transition z-10"></i>
                                <span id="add_image_text" class="text-xs font-bold text-slate-400 group-hover:text-brand z-10 bg-white/80 px-3 py-1 rounded-full">Klik untuk upload foto</span>
                            </div>
                            <input type="file" name="image" id="add_image_input" class="hidden" accept="image/*" onchange="previewImage(this, 'add_image_preview', 'add_image_icon', 'add_image_text')" :disabled="addImageMode === 'url'">
                        </div>

                        <!-- Mode URL -->
                        <div x-show="addImageMode === 'url'" style="display: none;" class="space-y-4">
                            <input type="url" name="image_url" x-model="addPreviewUrl" placeholder="https://contoh.com/gambar.jpg" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm transition" :disabled="addImageMode === 'upload'">
                            <div class="w-full h-48 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] flex flex-col items-center justify-center relative overflow-hidden">
                                <template x-if="addPreviewUrl">
                                    <img :src="addPreviewUrl" class="absolute inset-0 w-full h-full object-cover" onerror="this.src=''; this.onerror=null; this.parentElement.classList.add('bg-red-50');">
                                </template>
                                <template x-if="!addPreviewUrl">
                                    <span class="text-xs font-bold text-slate-400">Preview gambar akan muncul di sini</span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Nama Produk *</label>
                            <input type="text" name="name" required placeholder="Contoh: Es Teh Manis" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm transition">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Deskripsi</label>
                            <textarea name="description" required rows="2" placeholder="Jelaskan kelezatan produk ini..." class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm transition"></textarea>
                        </div>

                        <div class="col-span-2" x-data="{ open: false, selectedTags: [] }">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Tag Rasa <span class="text-[10px] text-slate-400 font-normal">(Pilih satu atau lebih)</span></label>
                            <input type="hidden" name="tags" :value="selectedTags.join(', ')">
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl hover:border-brand outline-none font-bold text-sm text-slate-700 flex items-center justify-between transition text-left">
                                    <span class="truncate pr-4" x-text="selectedTags.length > 0 ? selectedTags.join(', ') : 'Pilih Tag Rasa'"></span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 shrink-0 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute top-full mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden py-2 z-30 max-h-60 overflow-y-auto custom-scrollbar">
                                    <template x-for="tag in tasteTags" :key="tag">
                                        <button type="button" @click="selectedTags.includes(tag) ? selectedTags = selectedTags.filter(t => t !== tag) : selectedTags.push(tag); $nextTick(() => lucide.createIcons())" 
                                                class="w-full text-left px-6 py-4 text-sm font-bold flex items-center justify-between hover:bg-brand/5 transition"
                                                :class="selectedTags.includes(tag) ? 'text-brand bg-brand/5' : 'text-slate-600'">
                                            <span x-text="tag"></span>
                                            <i data-lucide="check" class="w-4 h-4" x-show="selectedTags.includes(tag)"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Harga *</label>
                            <div class="relative">
                                <input type="number" name="price" required placeholder="25000" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm pl-12 transition">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Diskon (%)</label>
                            <div class="relative">
                                <input type="number" name="discount_percentage" min="0" max="100" placeholder="0" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm pr-12 transition">
                                <span class="absolute right-6 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">%</span>
                            </div>
                        </div>

                        <div x-data="{ open: false, selectedId: categories.length > 0 ? categories[0].id : '', selectedName: categories.length > 0 ? categories[0].name : '' }">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Kategori *</label>
                            <input type="hidden" name="category_id" :value="selectedId" required>
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl hover:border-brand outline-none font-bold text-sm text-slate-700 flex items-center justify-between transition">
                                    <span x-text="selectedName || 'Pilih Kategori'"></span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute top-full mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden py-2 z-30 max-h-48 overflow-y-auto custom-scrollbar">
                                    <template x-for="category in categories" :key="category.id">
                                        <button type="button" @click="selectedId = category.id; selectedName = category.name; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition" x-text="category.name"></button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div x-data="{ open: false, selected: '' }">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Asal Makanan (Daerah)</label>
                            <input type="hidden" name="cuisine" :value="selected">
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl hover:border-brand outline-none font-bold text-sm text-slate-700 flex items-center justify-between transition">
                                    <span x-text="selected || 'Pilih Asal'"></span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute top-full mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden py-2 z-30 max-h-48 overflow-y-auto custom-scrollbar">
                                    <button type="button" @click="selected = ''; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Pilih Asal</button>
                                    <template x-for="c in cuisines" :key="c">
                                        <button type="button" @click="selected = c; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition" x-text="c"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-span-2 flex gap-6">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_popular" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                                <span class="ml-3 text-sm font-bold text-slate-700">Tandai Populer</span>
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_available" value="1" checked class="sr-only peer">
                                <input type="hidden" name="from_form" value="1">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                <span class="ml-3 text-sm font-bold text-slate-700">Tersedia</span>
                            </label>
                        </div>
                        
                        <!-- Dummy Tags (Not saved to DB but kept for exact UI match) -->
                        <div class="col-span-2 border-t border-slate-100 pt-6 mt-4">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Pengaturan Tambahan (Opsional)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div x-data="{ open: false, selected: 'Sendiri' }">
                                    <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Porsi</label>
                                    <div class="relative">
                                        <button @click="open = !open" @click.outside="open = false" type="button" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl hover:border-brand outline-none font-bold text-sm text-slate-600 flex items-center justify-between transition">
                                            <span x-text="selected"></span>
                                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                        </button>
                                        <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute bottom-full mb-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden py-2 z-30">
                                            <button type="button" @click="selected = 'Sendiri'; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Sendiri</button>
                                            <button type="button" @click="selected = 'Sharing'; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Sharing</button>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Waktu Saji (menit)</label>
                                    <input type="number" placeholder="15" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm transition">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="pt-10 flex gap-4">
                        <button type="submit" class="flex-1 py-5 bg-brand text-white rounded-2xl font-black text-lg shadow-2xl shadow-brand/30 hover:opacity-90 transition">SIMPAN PRODUK</button>
                        <button type="button" @click="closeAddModal()" class="px-8 py-5 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition">BATAL</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Menu -->
    <div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex justify-end">
        <div @click.outside="closeEditModal()" class="bg-white w-full max-w-2xl h-full shadow-2xl overflow-y-auto custom-scrollbar animate-in slide-in-from-right duration-300">
            <div class="p-10">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Edit Produk</h3>
                        <p class="text-sm font-medium text-slate-500" x-text="selectedProduct?.name"></p>
                    </div>
                    <button @click="closeEditModal()" class="p-3 bg-slate-50 text-slate-400 hover:text-slate-600 rounded-2xl transition"><i data-lucide="x" class="w-6 h-6"></i></button>
                </div>

                <form :action="'/admin/products/' + selectedProduct?.id" method="POST" enctype="multipart/form-data" class="space-y-8 pb-20">
                    @csrf
                    @method('PUT')
                    
                    <!-- Foto Upload / Link -->
                    <div class="relative group">
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Ubah Foto Produk</label>
                            <div class="flex bg-slate-100 p-1 rounded-lg">
                                <button type="button" @click="editImageMode = 'upload'" :class="editImageMode === 'upload' ? 'bg-white text-brand shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-3 py-1 text-[10px] font-bold rounded-md transition">Upload</button>
                                <button type="button" @click="editImageMode = 'url'" :class="editImageMode === 'url' ? 'bg-white text-brand shadow-sm' : 'text-slate-400 hover:text-slate-600'" class="px-3 py-1 text-[10px] font-bold rounded-md transition">Link URL</button>
                            </div>
                        </div>

                        <!-- Mode Upload -->
                        <div x-show="editImageMode === 'upload'">
                            <div class="w-full h-48 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] flex flex-col items-center justify-center gap-2 cursor-pointer hover:border-brand hover:bg-brand/5 transition group relative overflow-hidden" onclick="document.getElementById('edit_image_input').click()">
                                <img id="edit_image_preview" :src="selectedProduct?.image && !selectedProduct.image.startsWith('http') ? '/storage/' + selectedProduct.image : ''" :class="selectedProduct?.image && !selectedProduct.image.startsWith('http') ? 'block' : 'hidden'" class="absolute inset-0 w-full h-full object-cover">
                                <i id="edit_image_icon" data-lucide="camera" class="w-10 h-10 text-slate-300 group-hover:text-brand transition z-10" :class="selectedProduct?.image && !selectedProduct.image.startsWith('http') ? 'hidden' : 'block'"></i>
                                <span id="edit_image_text" class="text-xs font-bold text-slate-400 group-hover:text-brand z-10 bg-white/80 px-3 py-1 rounded-full" :class="selectedProduct?.image && !selectedProduct.image.startsWith('http') ? 'hidden' : 'block'">Klik untuk ubah foto</span>
                            </div>
                            <input type="file" name="image" id="edit_image_input" class="hidden" accept="image/*" onchange="previewImage(this, 'edit_image_preview', 'edit_image_icon', 'edit_image_text')" :disabled="editImageMode === 'url'">
                        </div>

                        <!-- Mode URL -->
                        <div x-show="editImageMode === 'url'" style="display: none;" class="space-y-4">
                            <input type="url" name="image_url" x-model="editPreviewUrl" placeholder="https://contoh.com/gambar.jpg" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm transition" :disabled="editImageMode === 'upload'">
                            <div class="w-full h-48 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] flex flex-col items-center justify-center relative overflow-hidden">
                                <template x-if="editPreviewUrl">
                                    <img :src="editPreviewUrl" class="absolute inset-0 w-full h-full object-cover" onerror="this.src=''; this.onerror=null; this.parentElement.classList.add('bg-red-50');">
                                </template>
                                <template x-if="!editPreviewUrl">
                                    <span class="text-xs font-bold text-slate-400">Preview gambar akan muncul di sini</span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Nama Produk *</label>
                            <input type="text" name="name" :value="selectedProduct?.name" required class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm transition">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Deskripsi</label>
                            <textarea name="description" :value="selectedProduct?.description" required rows="2" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm transition"></textarea>
                        </div>

                        <div class="col-span-2" x-data="{ open: false }">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Tag Rasa <span class="text-[10px] text-slate-400 font-normal">(Pilih satu atau lebih)</span></label>
                            <input type="hidden" name="tags" :value="editSelectedTags.join(', ')">
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl hover:border-brand outline-none font-bold text-sm text-slate-700 flex items-center justify-between transition text-left">
                                    <span class="truncate pr-4" x-text="editSelectedTags.length > 0 ? editSelectedTags.join(', ') : 'Pilih Tag Rasa'"></span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 shrink-0 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute top-full mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden py-2 z-30 max-h-60 overflow-y-auto custom-scrollbar">
                                    <template x-for="tag in tasteTags" :key="tag">
                                        <button type="button" @click="editSelectedTags.includes(tag) ? editSelectedTags = editSelectedTags.filter(t => t !== tag) : editSelectedTags.push(tag); $nextTick(() => lucide.createIcons())" 
                                                class="w-full text-left px-6 py-4 text-sm font-bold flex items-center justify-between hover:bg-brand/5 transition"
                                                :class="editSelectedTags.includes(tag) ? 'text-brand bg-brand/5' : 'text-slate-600'">
                                            <span x-text="tag"></span>
                                            <i data-lucide="check" class="w-4 h-4" x-show="editSelectedTags.includes(tag)"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Harga *</label>
                            <div class="relative">
                                <input type="number" name="price" :value="selectedProduct?.price" required class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm pl-12 transition">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Diskon (%)</label>
                            <div class="relative">
                                <input type="number" name="discount_percentage" :value="selectedProduct?.discount_percentage" min="0" max="100" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-medium text-sm pr-12 transition">
                                <span class="absolute right-6 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">%</span>
                            </div>
                        </div>

                        <div x-data="{ open: false }">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Kategori *</label>
                            <input type="hidden" name="category_id" :value="selectedProduct?.category_id" required>
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl hover:border-brand outline-none font-bold text-sm text-slate-700 flex items-center justify-between transition">
                                    <span x-text="categories.find(c => c.id == selectedProduct?.category_id)?.name || 'Pilih Kategori'"></span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute top-full mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden py-2 z-30 max-h-48 overflow-y-auto custom-scrollbar">
                                    <template x-for="category in categories" :key="category.id">
                                        <button type="button" @click="selectedProduct.category_id = category.id; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition" x-text="category.name"></button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div x-data="{ open: false }">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Asal Makanan (Daerah)</label>
                            <input type="hidden" name="cuisine" :value="selectedProduct?.cuisine">
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl hover:border-brand outline-none font-bold text-sm text-slate-700 flex items-center justify-between transition">
                                    <span x-text="selectedProduct?.cuisine || 'Pilih Asal'"></span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute top-full mt-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden py-2 z-30 max-h-48 overflow-y-auto custom-scrollbar">
                                    <button type="button" @click="selectedProduct.cuisine = ''; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Pilih Asal</button>
                                    <template x-for="c in cuisines" :key="c">
                                        <button type="button" @click="selectedProduct.cuisine = c; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition" x-text="c"></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-span-2 flex gap-6">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_popular" value="1" :checked="selectedProduct?.is_popular" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                                <span class="ml-3 text-sm font-bold text-slate-700">Tandai Populer</span>
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_available" value="1" :checked="selectedProduct?.is_available" class="sr-only peer">
                                <input type="hidden" name="from_form" value="1">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                <span class="ml-3 text-sm font-bold text-slate-700">Tersedia</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-10 flex gap-4">
                        <button type="submit" class="flex-1 py-5 bg-brand text-white rounded-2xl font-black text-lg shadow-2xl shadow-brand/30 hover:opacity-90 transition">SIMPAN PERUBAHAN</button>
                        <button type="button" @click="closeEditModal()" class="px-8 py-5 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition">BATAL</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input, previewId, iconId, textId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
                document.getElementById(previewId).classList.remove('hidden');
                document.getElementById(iconId).classList.add('hidden');
                document.getElementById(textId).classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function productManager() {
        return {
            products: {!! json_encode($products) !!},
            categories: {!! json_encode($categories) !!},
            viewMode: 'grid',
            filterCategory: '',
            filterCuisine: '',
            filterStatus: '',
            filterTag: '',
            currentPage: 1,
            itemsPerPage: 6,
            cuisines: ['Indonesian Food', 'Korean Food', 'Japanese Food', 'Western Food', 'Chinese Food', 'Thai Food', 'Arabic Food'],
            tasteTags: ['Pedas', 'Manis', 'Gurih', 'Asin', 'Asam', 'Pahit', 'Segar'],
            
            isAddModalOpen: false,
            isEditModalOpen: false,
            selectedProduct: null,
            editSelectedTags: [],
            
            addImageMode: 'upload',
            addPreviewUrl: '',
            editImageMode: 'upload',
            editPreviewUrl: '',

            init() {
                this.$nextTick(() => lucide.createIcons());
                this.$watch('filterCategory', () => { this.currentPage = 1; this.$nextTick(() => lucide.createIcons()); });
                this.$watch('filterCuisine', () => { this.currentPage = 1; this.$nextTick(() => lucide.createIcons()); });
                this.$watch('filterStatus', () => { this.currentPage = 1; this.$nextTick(() => lucide.createIcons()); });
                this.$watch('filterTag', () => { this.currentPage = 1; this.$nextTick(() => lucide.createIcons()); });
                this.$watch('currentPage', () => this.$nextTick(() => lucide.createIcons()));
                this.$watch('viewMode', () => this.$nextTick(() => lucide.createIcons()));
            },

            get availableTags() {
                let tags = new Set();
                this.products.forEach(p => {
                    if (p.tags && Array.isArray(p.tags)) {
                        p.tags.forEach(t => tags.add(t));
                    }
                });
                return Array.from(tags).sort();
            },

            get baseFilteredProducts() {
                return this.products.filter(p => {
                    const matchCategory = this.filterCategory === '' || String(p.category_id) === String(this.filterCategory);
                    const matchCuisine = this.filterCuisine === '' || String(p.cuisine) === String(this.filterCuisine);
                    const matchStatus = this.filterStatus === '' || String(p.is_available ? 1 : 0) === String(this.filterStatus);
                    const matchTag = this.filterTag === '' || (p.tags && Array.isArray(p.tags) && p.tags.includes(this.filterTag));
                    return matchCategory && matchCuisine && matchStatus && matchTag;
                });
            },

            get filteredProducts() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.baseFilteredProducts.slice(start, end);
            },

            get totalPages() {
                return Math.ceil(this.baseFilteredProducts.length / this.itemsPerPage);
            },

            formatPrice(price) {
                return 'Rp ' + Number(price).toLocaleString('id-ID');
            },

            openAddModal() {
                this.isAddModalOpen = true;
                this.addImageMode = 'upload';
                this.addPreviewUrl = '';
                document.body.classList.add('overflow-hidden');
            },
            
            closeAddModal() {
                this.isAddModalOpen = false;
                document.body.classList.remove('overflow-hidden');
            },

            openEditModal(product) {
                this.selectedProduct = product;
                this.editSelectedTags = product.tags ? [...product.tags] : [];
                this.isEditModalOpen = true;
                
                if (product.image && product.image.startsWith('http')) {
                    this.editImageMode = 'url';
                    this.editPreviewUrl = product.image;
                } else {
                    this.editImageMode = 'upload';
                    this.editPreviewUrl = '';
                }

                document.body.classList.add('overflow-hidden');
                // Reset preview
                setTimeout(() => {
                    if(!product.image) {
                        document.getElementById('edit_image_preview').classList.add('hidden');
                        document.getElementById('edit_image_icon').classList.remove('hidden');
                        document.getElementById('edit_image_text').classList.remove('hidden');
                    }
                    lucide.createIcons();
                }, 100);
            },
            
            closeEditModal() {
                this.isEditModalOpen = false;
                this.selectedProduct = null;
                this.editSelectedTags = [];
                document.body.classList.remove('overflow-hidden');
            },

            async deleteProduct(id) {
                if(!confirm('Yakin ingin menghapus produk ini? Tindakan tidak dapat dibatalkan.')) return;
                
                try {
                    const response = await fetch(`/admin/products/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    if(result.success) {
                        this.products = this.products.filter(p => p.id !== id);
                        this.$nextTick(() => lucide.createIcons());
                    }
                } catch (error) {
                    alert('Gagal menghapus produk.');
                }
            },

            async toggleAvailability(product) {
                try {
                    const response = await fetch(`/admin/products/${product.id}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    if(result.success) {
                        product.is_available = result.is_available;
                    } else {
                        product.is_available = !product.is_available; // revert UI
                    }
                } catch (error) {
                    product.is_available = !product.is_available; // revert UI
                    alert('Gagal mengubah status.');
                }
            }
        }
    }
</script>
@endsection
