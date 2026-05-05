@extends('layouts.admin')

@section('title', 'Branding & Visual Identity')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Branding & Visual Identity</h1>
        <p class="text-slate-500 font-medium mt-1">Customize the aesthetic experience of your digital restaurant.</p>
    </div>
</div>

@if(session('success'))
<div class="mb-8 p-6 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-4 animate-in slide-in-from-top-4">
    <div class="w-10 h-10 bg-emerald-500 text-white flex items-center justify-center rounded-xl shadow-lg shadow-emerald-500/20 shrink-0">
        <i data-lucide="check" class="w-5 h-5"></i>
    </div>
    <div>
        <h4 class="font-black text-emerald-900 text-sm">Success!</h4>
        <p class="text-emerald-700 text-xs font-medium">{{ session('success') }}</p>
    </div>
</div>
@endif

<form action="{{ route('admin.branding.update') }}" method="POST" enctype="multipart/form-data" x-data="brandingManager()">
    @csrf
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-10">
        <!-- Configuration Side -->
        <div class="xl:col-span-2 space-y-10">
            <!-- Core Identity -->
            <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm relative overflow-hidden group/card hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                <h3 class="text-xl font-black text-slate-900 tracking-tight mb-8">Core Identity</h3>
                
                <div class="space-y-8 relative z-10">
                    <div>
                        <label class="inline-flex px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-200 mb-3">Restaurant Name</label>
                        <input type="text" name="site_name" x-model="siteName" required class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-black text-xl text-slate-800 transition shadow-inner">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="inline-flex px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-200 mb-4">Main Brand Logo</label>
                            <div class="flex flex-col gap-4">
                                <div class="w-full h-40 bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200 flex items-center justify-center relative group overflow-hidden hover:border-brand transition">
                                    <template x-if="logoPreview">
                                        <img :src="logoPreview" class="max-h-24 max-w-[80%] object-contain drop-shadow-xl animate-in zoom-in">
                                    </template>
                                    <template x-if="!logoPreview">
                                        <div class="flex flex-col items-center gap-2">
                                            <i data-lucide="image-plus" class="w-8 h-8 text-slate-300 group-hover:text-brand transition transform group-hover:scale-110"></i>
                                            <span class="text-[10px] font-bold text-slate-400">Pilih Logo</span>
                                        </div>
                                    </template>
                                    <input type="file" name="site_logo" @change="handleFile('logoPreview', $event)" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                                <span class="text-[9px] text-center font-bold text-slate-400 uppercase tracking-widest">SVG, PNG or WEBP (Max 2MB)</span>
                            </div>
                        </div>
                        <div>
                            <label class="inline-flex px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-200 mb-4">Browser Favicon</label>
                            <div class="flex flex-col gap-4">
                                <div class="w-full h-40 bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200 flex items-center justify-center relative group overflow-hidden hover:border-brand transition">
                                    <template x-if="faviconPreview">
                                        <img :src="faviconPreview" class="w-16 h-16 object-contain drop-shadow-lg animate-in zoom-in">
                                    </template>
                                    <template x-if="!faviconPreview">
                                        <div class="flex flex-col items-center gap-2">
                                            <i data-lucide="layout" class="w-8 h-8 text-slate-300 group-hover:text-brand transition transform group-hover:scale-110"></i>
                                            <span class="text-[10px] font-bold text-slate-400">Pilih Favicon</span>
                                        </div>
                                    </template>
                                    <input type="file" name="site_favicon" @change="handleFile('faviconPreview', $event)" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                                <span class="text-[9px] text-center font-bold text-slate-400 uppercase tracking-widest">ICO, PNG or SVG (32x32px)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-slate-50 rounded-full blur-3xl -z-0"></div>
            </div>

            <!-- Theming -->
            <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm relative overflow-hidden group/card hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                <h3 class="text-xl font-black text-slate-900 tracking-tight mb-8">Visual Theming</h3>
                
                <div class="space-y-8 relative z-10">
                    <div>
                        <label class="inline-flex px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-200 mb-4">Primary Brand Color</label>
                        <div class="flex flex-col sm:flex-row items-center gap-6 p-4 bg-gray-50 rounded-3xl border border-gray-100">
                            <div class="relative w-24 h-24 rounded-[1.5rem] overflow-hidden shadow-inner border-4 border-white shrink-0 group">
                                <div class="absolute inset-0" :style="`background-color: ${primaryColor}`"></div>
                                <input type="color" name="primary_color" x-model="primaryColor" class="absolute inset-0 w-[200%] h-[200%] -top-1/2 -left-1/2 opacity-0 cursor-pointer">
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/20 pointer-events-none">
                                    <i data-lucide="pipette" class="w-6 h-6 text-white drop-shadow-md"></i>
                                </div>
                            </div>
                            <div class="flex flex-col flex-1 w-full text-center sm:text-left">
                                <span class="font-black text-slate-900 text-2xl font-mono uppercase tracking-widest" x-text="primaryColor"></span>
                                <span class="text-xs text-slate-500 font-medium mt-1">Warna ini akan digunakan pada semua tombol, ikon, dan aksen UI di aplikasi kasir & pelanggan.</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="inline-flex px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-200 mb-4">Staff Portal Background (Login)</label>
                        <div class="relative w-full h-64 bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-200 overflow-hidden group flex items-center justify-center hover:border-brand transition">
                            <template x-if="bgPreview">
                                <img :src="bgPreview" class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-105">
                            </template>
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition flex items-center justify-center" :class="!bgPreview ? 'bg-transparent group-hover:bg-gray-100/50' : ''">
                                <div class="relative z-10 flex flex-col items-center gap-3 text-white" :class="!bgPreview ? 'text-slate-400' : ''">
                                    <i data-lucide="upload-cloud" class="w-12 h-12 drop-shadow-lg group-hover:-translate-y-1 transition transform duration-300" :class="!bgPreview ? 'drop-shadow-none' : ''"></i>
                                    <span class="font-black text-[11px] uppercase tracking-widest drop-shadow-md" :class="!bgPreview ? 'drop-shadow-none' : ''">Upload Background Halaman Login</span>
                                </div>
                            </div>
                            <input type="file" name="login_background" @change="handleFile('bgPreview', $event)" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Integration -->
            <div class="bg-white p-8 rounded-[3rem] border border-slate-200 shadow-sm relative overflow-hidden group/card hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-[1.5rem] flex items-center justify-center shrink-0">
                        <i data-lucide="wallet" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Payment Details</h3>
                        <p class="text-[11px] font-black text-emerald-500 uppercase tracking-widest mt-1">Konfigurasi Rekening & QRIS</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
                    <!-- QRIS Upload -->
                    <div class="lg:col-span-2">
                        <label class="inline-flex px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-200 mb-4">QRIS Official Code</label>
                        <div class="relative aspect-square w-full bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-200 overflow-hidden group flex items-center justify-center hover:border-emerald-400 transition">
                            <template x-if="qrisPreview">
                                <img :src="qrisPreview" class="absolute inset-0 w-full h-full object-contain p-6 bg-white">
                            </template>
                            
                            <div class="absolute inset-0 transition flex flex-col items-center justify-center gap-3" 
                                 :class="qrisPreview ? 'bg-black/0 group-hover:bg-black/60 opacity-0 group-hover:opacity-100 text-white' : 'text-slate-300 group-hover:text-emerald-500'">
                                <i data-lucide="qr-code" class="w-16 h-16 drop-shadow-md" :class="qrisPreview ? '' : 'drop-shadow-none'"></i>
                                <span class="font-black text-[10px] uppercase tracking-widest text-center px-6 leading-relaxed drop-shadow-md" :class="qrisPreview ? '' : 'drop-shadow-none'" x-text="qrisPreview ? 'GANTI QRIS' : 'Upload Gambar QRIS Merchant Anda'"></span>
                            </div>
                            <input type="file" name="qris_image" @change="handleFile('qrisPreview', $event)" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="lg:col-span-3 space-y-6">
                        <div>
                            <label class="inline-flex px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-200 mb-2">Bank Name</label>
                            <input type="text" name="bank_name" value="{{ $setting->bank_name }}" placeholder="e.g. Bank Central Asia (BCA)" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none font-bold transition">
                        </div>
                        <div>
                            <label class="inline-flex px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-200 mb-2">Account Number</label>
                            <input type="text" name="account_number" value="{{ $setting->account_number }}" placeholder="e.g. 1234567890" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none font-black text-xl tracking-wider text-slate-800 transition">
                        </div>
                        <div>
                            <label class="inline-flex px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-200 mb-2">Account Holder Name</label>
                            <input type="text" name="account_name" value="{{ $setting->account_name }}" placeholder="e.g. Flavora Kitchen" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-emerald-500 outline-none font-bold uppercase transition">
                        </div>

                        <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-100 flex gap-4 mt-8">
                            <i data-lucide="shield-check" class="w-6 h-6 text-emerald-600 shrink-0"></i>
                            <p class="text-[11px] font-bold text-emerald-800 leading-relaxed">
                                Detail bank dan QRIS ini akan otomatis ditampilkan kepada pelanggan saat mereka memilih metode pembayaran Transfer/QRIS di menu digital.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview / Control Side -->
        <div class="space-y-8 sticky top-10 h-max">
            <!-- Save Button -->
            <button type="submit" class="w-full py-6 bg-slate-900 text-white rounded-[2rem] font-black text-lg transition-all duration-300 shadow-2xl shadow-slate-900/30 hover:shadow-brand/40 hover:bg-brand hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-3 group">
                <i data-lucide="check-circle-2" class="w-6 h-6 group-hover:scale-110 transition"></i>
                <span class="tracking-widest">SIMPAN PERUBAHAN</span>
            </button>

            <!-- Live Mockup Preview -->
            <div class="bg-white p-6 rounded-[3rem] border border-slate-200 shadow-xl relative overflow-hidden flex flex-col items-center justify-center">
                <div class="w-full text-center mb-6 relative z-10">
                    <h4 class="font-black text-sm uppercase tracking-widest text-slate-800">Live Preview</h4>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Tampilan di HP Pelanggan</p>
                </div>

                <!-- Phone Frame -->
                <div class="relative w-[260px] h-[520px] bg-slate-900 rounded-[3rem] p-3 shadow-2xl shadow-slate-300 border-4 border-slate-100 z-10">
                    <!-- Screen -->
                    <div class="w-full h-full bg-white rounded-[2rem] overflow-hidden relative flex flex-col">
                        <!-- Top Bar (Mock) -->
                        <div class="h-6 w-full flex justify-between items-center px-4 pt-1 bg-white shrink-0 z-20">
                            <span class="text-[9px] font-bold">12:00</span>
                            <div class="flex gap-1">
                                <div class="w-3 h-3 rounded-full border border-black"></div>
                            </div>
                        </div>

                        <!-- App Header Preview -->
                        <div class="px-5 pt-4 pb-6 flex flex-col items-center border-b border-gray-100 bg-white z-10 relative">
                            <template x-if="logoPreview">
                                <img :src="logoPreview" class="h-12 w-auto object-contain mb-3 drop-shadow-sm">
                            </template>
                            <template x-if="!logoPreview">
                                <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                    <i data-lucide="store" class="w-5 h-5 text-slate-400"></i>
                                </div>
                            </template>
                            <h2 class="font-black text-lg text-slate-800 text-center leading-tight line-clamp-1" x-text="siteName || 'Nama Resto'"></h2>
                            <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Digital Menu</span>
                        </div>

                        <!-- App Content Preview -->
                        <div class="flex-1 bg-slate-50 p-4 space-y-4 relative overflow-hidden">
                            <!-- Mock Product Card -->
                            <div class="bg-white p-3 rounded-2xl shadow-sm flex gap-3 items-center">
                                <div class="w-16 h-16 bg-slate-200 rounded-xl shrink-0"></div>
                                <div class="flex-1">
                                    <div class="h-3 w-3/4 bg-slate-200 rounded-full mb-2"></div>
                                    <div class="h-3 w-1/2 bg-slate-100 rounded-full"></div>
                                </div>
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-white" :style="`background-color: ${primaryColor}`">
                                    <i data-lucide="plus" class="w-3 h-3"></i>
                                </div>
                            </div>
                            <!-- Mock Product Card 2 -->
                            <div class="bg-white p-3 rounded-2xl shadow-sm flex gap-3 items-center">
                                <div class="w-16 h-16 bg-slate-200 rounded-xl shrink-0"></div>
                                <div class="flex-1">
                                    <div class="h-3 w-2/3 bg-slate-200 rounded-full mb-2"></div>
                                    <div class="h-3 w-1/3 bg-slate-100 rounded-full"></div>
                                </div>
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-white" :style="`background-color: ${primaryColor}`">
                                    <i data-lucide="plus" class="w-3 h-3"></i>
                                </div>
                            </div>

                            <!-- Decorative bg for preview -->
                            <div class="absolute -bottom-20 -right-20 w-40 h-40 rounded-full opacity-10 blur-2xl" :style="`background-color: ${primaryColor}`"></div>
                        </div>

                        <!-- App Bottom Bar Preview -->
                        <div class="h-16 bg-white border-t border-gray-100 flex items-center justify-around px-2 pb-1 shrink-0 z-20">
                            <div class="flex flex-col items-center gap-1" :style="`color: ${primaryColor}`">
                                <i data-lucide="home" class="w-5 h-5"></i>
                                <div class="w-1 h-1 rounded-full" :style="`background-color: ${primaryColor}`"></div>
                            </div>
                            <div class="flex flex-col items-center gap-1 text-slate-300">
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </div>
                            <div class="flex flex-col items-center gap-1 text-slate-300">
                                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Abstract Decorative Shapes -->
                <div class="absolute inset-0 bg-gradient-to-br from-slate-50 to-slate-100 opacity-50 -z-10"></div>
            </div>
        </div>
    </div>
</form>

<script>
    function brandingManager() {
        return {
            siteName: '{{ $setting->site_name ?? "" }}',
            primaryColor: '{{ $setting->primary_color ?? "#f97316" }}',
            logoPreview: '{{ $setting->site_logo ? Storage::url($setting->site_logo) : "" }}',
            faviconPreview: '{{ $setting->site_favicon ? Storage::url($setting->site_favicon) : "" }}',
            bgPreview: '{{ $setting->login_background ? Storage::url($setting->login_background) : "" }}',
            qrisPreview: '{{ $setting->qris_image ? Storage::url($setting->qris_image) : "" }}',

            init() {
                this.$nextTick(() => {
                    lucide.createIcons();
                });
            },

            handleFile(previewKey, event) {
                const file = event.target.files[0];
                if (file) {
                    this[previewKey] = URL.createObjectURL(file);
                }
            }
        }
    }
</script>
@endsection

