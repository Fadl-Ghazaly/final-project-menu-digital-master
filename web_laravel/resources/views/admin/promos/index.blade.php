@extends('layouts.admin')

@section('title', 'Promo & Jadwal')
@section('page_title', 'Promo & Jadwal')

@section('content')
<div class="flex flex-col h-full" x-data="promoManager()" x-init="init()">
    <!-- Banner Management Section -->
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm p-10 mb-10 overflow-hidden relative group">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Banner Promo Utama</h3>
                <p class="text-xs font-bold text-slate-500">Foto yang akan muncul di tampilan awal menu digital pelanggan</p>
            </div>
            <button @click="openAddModal(true)" class="px-5 py-3 bg-slate-900 text-white rounded-xl font-black text-xs transition hover:bg-black active:scale-95 flex items-center gap-2">
                <i data-lucide="image-plus" class="w-4 h-4"></i>
                <span>TAMBAH FOTO</span>
            </button>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <template x-for="banner in banners" :key="banner.id">
                <div class="relative group/item aspect-[16/9] rounded-2xl overflow-hidden border border-slate-200">
                    <img :src="'/storage/promos/' + banner.image" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/item:opacity-100 transition-opacity flex items-center justify-center gap-2">
                        <button @click="deletePromo(banner.id)" class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:scale-110 transition">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </template>
            <template x-if="banners.length === 0">
                <div class="col-span-full py-10 flex flex-col items-center justify-center text-slate-300 border-2 border-dashed border-slate-100 rounded-3xl">
                    <i data-lucide="image" class="w-10 h-10 mb-2 opacity-20"></i>
                    <p class="text-xs font-bold opacity-50">Belum ada banner aktif</p>
                </div>
            </template>
        </div>
    </div>

    <!-- Header & Filter -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex p-1 bg-white border border-slate-200 rounded-2xl shadow-sm">
                <button @click="filterStatus = 'all'" :class="filterStatus === 'all' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand bg-transparent'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition">Semua</button>
                <button @click="filterStatus = 'AKTIF'" :class="filterStatus === 'AKTIF' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand bg-transparent'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition">Aktif</button>
                <button @click="filterStatus = 'TERJADWAL'" :class="filterStatus === 'TERJADWAL' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand bg-transparent'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition">Terjadwal</button>
            </div>

            <div class="flex p-1 bg-white border border-slate-200 rounded-2xl shadow-sm">
                <template x-for="t in [ {id:'all_type', label:'Semua Tipe'}, {id:'diskon', label:'Diskon'}, {id:'bundling', label:'Bundling'}, {id:'free_item', label:'Free Item'} ]">
                    <button @click="filterType = t.id" :class="filterType === t.id ? 'bg-slate-900 text-white' : 'text-slate-500 hover:text-slate-900'" class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition" x-text="t.label"></button>
                </template>
            </div>
        </div>

        <button @click="openAddModal(false)" class="px-6 py-4 bg-brand text-white rounded-2xl font-black text-sm transition shadow-xl shadow-brand/20 hover:-translate-y-1 active:scale-95 flex items-center gap-2">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>TAMBAH PROMO</span>
        </button>
    </div>

    <!-- Promo Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        <template x-for="promo in filteredPromos" :key="promo.id">
            <div :class="{
                    'bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden group hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 relative': true,
                    'opacity-70 grayscale': getStatus(promo) === 'KADALUARSA'
                }">
                <div class="p-8 relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex gap-2">
                            <span class="px-3 py-1 text-[9px] font-black uppercase rounded-lg"
                                  :class="{
                                      'bg-emerald-100 text-emerald-600': getStatus(promo) === 'AKTIF',
                                      'bg-blue-100 text-blue-600': getStatus(promo) === 'TERJADWAL',
                                      'bg-red-100 text-red-600': getStatus(promo) === 'KADALUARSA'
                                  }"
                                  x-text="getStatus(promo)">
                            </span>
                            <button @click="filterType = promo.promo_type" class="px-3 py-1 text-[9px] font-black uppercase rounded-lg bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-900 hover:text-white transition" x-text="promo.promo_type.replace('_', ' ')"></button>
                        </div>
                        <label class="relative inline-flex items-center" :class="getStatus(promo) === 'KADALUARSA' && !promo.is_active ? 'cursor-not-allowed' : 'cursor-pointer'">
                            <input type="checkbox" class="sr-only peer" :checked="promo.is_active" @change="toggleStatus(promo)" :disabled="getStatus(promo) === 'KADALUARSA' && !promo.is_active && promo.quota && promo.used >= promo.quota">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-900 mb-2 leading-tight" x-text="promo.name"></h3>
                    <p class="text-xs font-bold text-slate-400 mb-6 line-clamp-2" x-text="promo.description || 'Tidak ada deskripsi'"></p>
                    
                    <div class="flex items-center gap-2 mb-6">
                        <span class="text-3xl font-black text-brand tracking-tighter" x-text="promo.type === 'percentage' ? parseInt(promo.value) + '%' : formatRupiah(promo.value)"></span>
                        <span class="px-2 py-0.5 bg-brand/10 text-brand text-[8px] font-black uppercase rounded border border-brand/10" x-text="promo.type === 'percentage' ? 'DISKON' : 'POTONGAN'"></span>
                    </div>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                            <i data-lucide="tag" class="w-4 h-4 text-slate-400"></i>
                            <span class="font-black text-slate-700" x-text="promo.code"></span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                            <i data-lucide="shopping-cart" class="w-4 h-4 text-slate-400"></i>
                            <span>Min. Belanja: <span x-text="formatRupiah(promo.min_purchase)"></span></span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                            <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                            <span x-text="(promo.formatted_start || '-') + ' s/d ' + (promo.formatted_end || '-')"></span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                            <i data-lucide="users" class="w-4 h-4 text-slate-400"></i>
                            <span>Terpakai: <span x-text="promo.used"></span> / <span x-text="promo.quota ? promo.quota : 'Tanpa Batas'"></span></span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                        <div class="flex gap-2">
                            <button @click="openEditModal(promo)" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition"><i data-lucide="pencil" class="w-5 h-5"></i></button>
                            <button @click="deletePromo(promo.id)" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest"
                              :class="{
                                  'text-brand': getStatus(promo) === 'AKTIF',
                                  'text-blue-500': getStatus(promo) === 'TERJADWAL',
                                  'text-red-500': getStatus(promo) === 'KADALUARSA'
                              }"
                              x-text="getStatus(promo) === 'AKTIF' ? 'LANGSUNG AKTIF' : getStatus(promo)">
                        </span>
                    </div>
                </div>
                <!-- Decorative circle for active promos -->
                <div x-show="getStatus(promo) === 'AKTIF'" class="absolute -right-10 -bottom-10 w-32 h-32 bg-brand/5 rounded-full z-0"></div>
            </div>
        </template>
        
        <template x-if="filteredPromos.length === 0">
            <div class="col-span-full py-20 flex flex-col items-center justify-center text-slate-300 bg-white rounded-[2.5rem] border border-slate-200 border-dashed">
                <i data-lucide="tag" class="w-16 h-16 mb-4"></i>
                <p class="font-bold text-slate-400">Tidak ada promo ditemukan.</p>
            </div>
        </template>
    </div>

    <!-- Form Modal (Tambah/Edit) -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-6">
        <div @click.outside="closeModal()" class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300 flex flex-col max-h-[90vh]">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-2xl font-black tracking-tight" x-text="editMode ? 'Edit Promo' : 'Buat Promo Baru'"></h3>
                <button @click="closeModal()" class="p-2 hover:bg-gray-100 rounded-full transition"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>
            
            <div class="p-8 overflow-y-auto flex-1 inner-scroll">
                <form :action="editMode ? '/admin/promos/' + formData.id : '/admin/promos'" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <template x-if="editMode">
                        @method('PUT')
                    </template>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Nama Promo *</label>
                            <input type="text" name="name" x-model="formData.name" required placeholder="e.g. Diskon Weekend" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm transition">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Deskripsi Promo</label>
                            <textarea name="description" x-model="formData.description" placeholder="Jelaskan detail promo ini..." class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm transition min-h-[100px]"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Kode Promo * (Unik)</label>
                            <input type="text" name="code" x-model="formData.code" required placeholder="e.g. WEEKEND20" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm transition uppercase">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Tipe Promo</label>
                            <select name="promo_type" x-model="formData.promo_type" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm transition appearance-none">
                                <option value="diskon">Diskon</option>
                                <option value="bundling">Bundling</option>
                                <option value="free_item">Free Item</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Tipe Diskon</label>
                            <div class="flex p-1 bg-slate-50 border border-slate-200 rounded-2xl">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="type" value="percentage" x-model="formData.type" class="sr-only peer">
                                    <span class="block text-center py-2 text-xs font-black rounded-xl peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-brand text-slate-400 transition">PERSEN (%)</span>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="type" value="fixed" x-model="formData.type" class="sr-only peer">
                                    <span class="block text-center py-2 text-xs font-black rounded-xl peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-brand text-slate-400 transition">NOMINAL (Rp)</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Nilai Diskon *</label>
                            <input type="number" name="value" x-model="formData.value" required placeholder="20" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Min. Pembelian (Rp)</label>
                            <input type="number" name="min_purchase" x-model="formData.min_purchase" placeholder="0" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Batas Pemakaian (Kuota)</label>
                            <input type="number" name="quota" x-model="formData.quota" placeholder="Kosong = Tanpa Batas" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Tanggal Mulai *</label>
                            <input type="datetime-local" name="start_date" x-model="formData.start_date" required class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Tanggal Berakhir *</label>
                            <input type="datetime-local" name="end_date" x-model="formData.end_date" required class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm transition">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex items-center gap-3 pt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" x-model="formData.is_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                <span class="ms-3 text-sm font-bold text-slate-600">Promo Aktif</span>
                            </label>
                        </div>
                        <div class="flex items-center gap-3 pt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_banner" value="1" x-model="formData.is_banner" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                                <span class="ms-3 text-sm font-bold text-slate-600">Jadikan Banner</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Foto Promo (Wajib jika jadi Banner)</label>
                        <input type="file" name="image" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm transition">
                        <p class="text-[10px] font-bold text-slate-400 mt-2 px-2 italic" x-show="formData.image">File saat ini: <span x-text="formData.image"></span></p>
                    </div>

                    <button type="submit" class="w-full py-5 bg-brand text-white rounded-[1.5rem] font-black text-lg transition shadow-2xl shadow-brand/30 hover:scale-[1.02] active:scale-[0.98] mt-4" x-text="editMode ? 'SIMPAN PERUBAHAN' : 'SIMPAN PROMO'"></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function promoManager() {
        return {
            promos: {!! json_encode($promos->map(function($promo) {
                $promo->formatted_start = $promo->start_date ? $promo->start_date->translatedFormat('d M Y') : null;
                $promo->formatted_end = $promo->end_date ? $promo->end_date->translatedFormat('d M Y') : null;
                $promo->iso_start = $promo->start_date ? $promo->start_date->format('Y-m-d\TH:i') : null;
                $promo->iso_end = $promo->end_date ? $promo->end_date->format('Y-m-d\TH:i') : null;
                return $promo;
            })) !!},
            filterStatus: 'all',
            filterType: 'all_type',
            isModalOpen: false,
            editMode: false,
            formData: {
                id: null,
                name: '',
                description: '',
                code: '',
                type: 'percentage',
                promo_type: 'diskon',
                value: '',
                min_purchase: '',
                quota: '',
                start_date: '',
                end_date: '',
                image: null,
                is_banner: false,
                is_active: true
            },

            init() {
                this.$nextTick(() => {
                    lucide.createIcons();
                });
                
                this.$watch('filterStatus', () => {
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                });
            },

            getStatus(promo) {
                const now = new Date();
                const start = promo.start_date ? new Date(promo.start_date) : null;
                const end = promo.end_date ? new Date(promo.end_date) : null;
                
                if (!promo.is_active) return 'KADALUARSA';
                if (promo.quota !== null && promo.used >= promo.quota) return 'KADALUARSA';
                if (end && end < now) return 'KADALUARSA';
                if (start && start > now) return 'TERJADWAL';
                
                return 'AKTIF';
            },

            get filteredPromos() {
                return this.promos.filter(p => {
                    const matchStatus = this.filterStatus === 'all' || this.getStatus(p) === this.filterStatus;
                    const matchType = this.filterType === 'all_type' || p.promo_type === this.filterType;
                    return matchStatus && matchType && !p.is_banner;
                });
            },

            get banners() {
                return this.promos.filter(p => p.is_banner);
            },

            formatRupiah(value) {
                if(!value) return 'Rp 0';
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
            },

            openAddModal(asBanner = false) {
                this.editMode = false;
                this.formData = {
                    id: null,
                    name: '',
                    description: '',
                    code: '',
                    type: 'percentage',
                    promo_type: 'diskon',
                    value: '',
                    min_purchase: '',
                    quota: '',
                    start_date: '',
                    end_date: '',
                    image: null,
                    is_banner: asBanner,
                    is_active: true
                };
                this.isModalOpen = true;
            },

            openEditModal(promo) {
                this.editMode = true;
                this.formData = {
                    id: promo.id,
                    name: promo.name,
                    description: promo.description,
                    code: promo.code,
                    type: promo.type,
                    promo_type: promo.promo_type,
                    value: promo.value,
                    min_purchase: promo.min_purchase == 0 ? '' : promo.min_purchase,
                    quota: promo.quota === null ? '' : promo.quota,
                    start_date: promo.iso_start,
                    end_date: promo.iso_end,
                    image: promo.image,
                    is_banner: promo.is_banner,
                    is_active: promo.is_active
                };
                this.isModalOpen = true;
            },

            closeModal() {
                this.isModalOpen = false;
            },

            async toggleStatus(promo) {
                try {
                    const response = await fetch(`/admin/promos/${promo.id}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    if(result.success) {
                        promo.is_active = result.is_active;
                        const index = this.promos.findIndex(p => p.id === promo.id);
                        if(index !== -1) this.promos[index].is_active = result.is_active;
                    } else {
                        promo.is_active = !promo.is_active;
                    }
                } catch (error) {
                    promo.is_active = !promo.is_active;
                    alert('Gagal mengubah status promo.');
                }
            },

            async deletePromo(id) {
                if(!confirm('Hapus promo ini? Pelanggan tidak akan bisa menggunakan kode ini lagi.')) return;
                
                try {
                    const response = await fetch(`/admin/promos/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    if(result.success) {
                        this.promos = this.promos.filter(p => p.id !== id);
                    }
                } catch (error) {
                    alert('Gagal menghapus promo.');
                }
            }
        }
    }
</script>

<style>
    .inner-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .inner-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .inner-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 20px;
    }
    .inner-scroll:hover::-webkit-scrollbar-thumb {
        background: #94a3b8;
    }
</style>
@endsection
