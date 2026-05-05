@extends('layouts.admin')

@section('title', 'Meja & QR Code')
@section('page_title', 'Meja & QR Code')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<div class="flex flex-col h-full" x-data="tableManager()" x-init="init()">
    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center">
                    <i data-lucide="armchair" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</span>
            </div>
            <span class="block text-3xl font-black text-slate-900 leading-none mb-1" x-text="tables.length"></span>
            <p class="text-xs font-bold text-slate-500">Total Meja</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Tersedia</span>
            </div>
            <span class="block text-3xl font-black text-slate-900 leading-none mb-1" x-text="tables.filter(t => t.status === 'available').length"></span>
            <p class="text-xs font-bold text-slate-500">Siap Digunakan</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Terisi</span>
            </div>
            <span class="block text-3xl font-black text-slate-900 leading-none mb-1" x-text="tables.filter(t => t.status === 'occupied').length"></span>
            <p class="text-xs font-bold text-slate-500">Sedang Makan</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                    <i data-lucide="calendar-days" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Booking</span>
            </div>
            <span class="block text-3xl font-black text-slate-900 leading-none mb-1" x-text="tables.filter(t => t.status === 'booked').length"></span>
            <p class="text-xs font-bold text-slate-500">Telah Dipesan</p>
        </div>
    </div>

    <!-- Header Row -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex p-1 bg-white border border-slate-200 rounded-2xl shadow-sm">
                <button @click="filterStatus = 'all'" :class="filterStatus === 'all' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand bg-transparent'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition">Semua</button>
                <button @click="filterStatus = 'active'" :class="filterStatus === 'active' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand bg-transparent'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition">Aktif</button>
                <button @click="filterStatus = 'inactive'" :class="filterStatus === 'inactive' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand bg-transparent'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition">Nonaktif</button>
            </div>

            <!-- Search Bar -->
            <div class="relative min-w-[300px]">
                <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-5 top-1/2 -translate-y-1/2"></i>
                <input type="text" x-model="searchQuery" placeholder="Cari nomor atau nama meja..." class="w-full pl-14 pr-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-xs transition shadow-sm">
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button @click="openAddModal()" class="px-6 py-4 bg-brand text-white rounded-2xl font-black text-sm transition shadow-xl shadow-brand/20 hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>TAMBAH MEJA</span>
            </button>
            <button onclick="window.print()" class="px-6 py-4 border-2 border-slate-200 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-50 transition flex items-center gap-2">
                <i data-lucide="download" class="w-5 h-5"></i>
                <span>DOWNLOAD SEMUA QR</span>
            </button>
        </div>
    </div>

    <!-- Meja Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">No. Meja</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Meja</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tipe</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kapasitas</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">QR Preview</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="table in paginatedTables" :key="table.id">
                        <tr>
                            <td class="px-8 py-6 font-black text-slate-900 text-lg leading-none" x-text="String(table.number).padStart(2, '0')"></td>
                            <td class="px-8 py-6 font-bold text-slate-700 text-sm" x-text="table.name || '-'"></td>
                            <td class="px-8 py-6">
                                <span :class="table.type === 'VIP' ? 'bg-purple-50 text-purple-600 border-purple-100' : (table.type === 'Outdoor' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-blue-50 text-blue-600 border-blue-100')" class="px-3 py-1 text-[9px] font-black uppercase rounded-lg border" x-text="table.type"></span>
                            </td>
                            <td class="px-8 py-6 font-bold text-slate-500 text-sm" x-text="table.capacity + ' Orang'"></td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-1">
                                    <span :class="table.status === 'available' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : (table.status === 'occupied' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-blue-50 text-blue-600 border-blue-100')" class="px-3 py-1 text-[9px] font-black uppercase rounded-lg border w-fit" x-text="table.status"></span>
                                    <span class="text-[10px] font-bold text-slate-400" x-show="table.customer_name" x-text="table.customer_name"></span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" :checked="table.is_active" @change="toggleAvailability(table)">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                            </td>
                            <td class="px-8 py-6">
                                <div @click="openQRModal(table)" class="w-10 h-10 bg-white border border-slate-200 rounded-lg flex items-center justify-center cursor-pointer hover:border-brand transition p-1">
                                    <div :id="'qr-' + table.id + '-thumb'" class="w-full h-full" :class="!table.is_active ? 'grayscale opacity-30' : ''"></div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openQRModal(table)" class="p-2 text-slate-400 hover:text-brand transition"><i data-lucide="download" class="w-5 h-5"></i></button>
                                    <button @click="openEditModal(table)" class="p-2 text-slate-400 hover:text-blue-500 transition"><i data-lucide="pencil" class="w-5 h-5"></i></button>
                                    <button @click="deleteTable(table.id)" class="p-2 text-slate-400 hover:text-red-500 transition"><i data-lucide="trash-2" class="w-5 h-5"></i></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredTables.length === 0">
                        <tr>
                            <td colspan="7" class="px-8 py-10 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-300">
                                    <i data-lucide="armchair" class="w-12 h-12 mb-3"></i>
                                    <p class="font-bold text-sm">Tidak ada meja ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination UI -->
        <div class="p-6 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4" x-show="filteredTables.length > 0">
            <span class="text-xs font-bold text-slate-400">Menampilkan <span x-text="(currentPage - 1) * itemsPerPage + 1"></span> - <span x-text="Math.min(currentPage * itemsPerPage, filteredTables.length)"></span> dari <span x-text="filteredTables.length"></span> meja</span>
            <div class="flex items-center gap-2">
                <button @click="if(currentPage > 1) currentPage--" class="p-2 rounded-xl border border-slate-200 text-slate-400 hover:text-brand hover:border-brand/30 hover:bg-brand/5 transition disabled:opacity-50" :disabled="currentPage === 1"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
                
                <template x-for="page in totalPages" :key="page">
                    <button @click="currentPage = page" :class="currentPage === page ? 'bg-brand text-white shadow-md shadow-brand/20' : 'text-slate-500 hover:bg-slate-100'" class="w-8 h-8 rounded-xl text-xs font-black transition flex items-center justify-center" x-text="page"></button>
                </template>

                <button @click="if(currentPage < totalPages) currentPage++" class="p-2 rounded-xl border border-slate-200 text-slate-400 hover:text-brand hover:border-brand/30 hover:bg-brand/5 transition disabled:opacity-50" :disabled="currentPage === totalPages"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Meja -->
    <div x-show="isAddModalOpen" style="display: none;" class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-6">
        <div @click.outside="closeAddModal()" class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <div class="p-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black tracking-tight">Tambah Meja Baru</h3>
                    <button @click="closeAddModal()" class="p-2 hover:bg-gray-100 rounded-full transition"><i data-lucide="x" class="w-6 h-6"></i></button>
                </div>
                
                <form action="/admin/tables" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Nomor Meja *</label>
                            <input type="text" name="number" required placeholder="e.g. 16" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Kapasitas</label>
                            <input type="number" name="capacity" required placeholder="4" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Nama Meja</label>
                        <input type="text" name="name" placeholder="e.g. Meja Outdoor Bunga" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm">
                    </div>

                    <div x-data="{ open: false, selectedType: 'Regular' }">
                        <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Tipe Meja</label>
                        <input type="hidden" name="type" :value="selectedType">
                        <div class="relative">
                            <button @click="open = !open" @click.outside="open = false" type="button" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl hover:border-brand outline-none font-bold text-sm text-slate-700 flex items-center justify-between transition">
                                <span x-text="selectedType"></span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="open" x-transition style="display: none;" class="absolute bottom-full mb-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden py-2 z-30">
                                <template x-for="t in ['Regular', 'VIP', 'Outdoor', 'Private']">
                                    <button type="button" @click="selectedType = t; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition" x-text="t"></button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            <span class="ms-3 text-sm font-bold text-slate-600">Status Aktif</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-5 bg-brand text-white rounded-[1.5rem] font-black text-lg transition shadow-2xl shadow-brand/30 hover:scale-[1.02] active:scale-[0.98] mt-4">SIMPAN MEJA</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Meja -->
    <div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-6">
        <div @click.outside="closeEditModal()" class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
            <div class="p-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black tracking-tight">Edit Meja</h3>
                    <button @click="closeEditModal()" class="p-2 hover:bg-gray-100 rounded-full transition"><i data-lucide="x" class="w-6 h-6"></i></button>
                </div>
                
                <form :action="'/admin/tables/' + selectedTable?.id" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Nomor Meja *</label>
                            <input type="text" name="number" :value="selectedTable?.number" required class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Kapasitas</label>
                            <input type="number" name="capacity" :value="selectedTable?.capacity" required class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Nama Meja</label>
                        <input type="text" name="name" :value="selectedTable?.name" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div x-data="{ open: false }">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Tipe Meja</label>
                            <input type="hidden" name="type" :value="selectedTable?.type">
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl hover:border-brand outline-none font-bold text-sm text-slate-700 flex items-center justify-between transition">
                                    <span x-text="selectedTable?.type || 'Regular'"></span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition style="display: none;" class="absolute bottom-full mb-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden py-2 z-30">
                                    <template x-for="t in ['Regular', 'VIP', 'Outdoor', 'Private']">
                                        <button type="button" @click="selectedTable.type = t; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition" x-text="t"></button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div x-data="{ open: false }">
                            <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Status Meja</label>
                            <input type="hidden" name="status" :value="selectedTable?.status">
                            <div class="relative">
                                <button @click="open = !open" @click.outside="open = false" type="button" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl hover:border-brand outline-none font-bold text-sm text-slate-700 flex items-center justify-between transition">
                                    <span x-text="selectedTable?.status === 'available' ? 'Tersedia' : (selectedTable?.status === 'occupied' ? 'Terisi' : 'Booking')"></span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-transition style="display: none;" class="absolute bottom-full mb-2 w-full bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden py-2 z-30">
                                    <button type="button" @click="selectedTable.status = 'available'; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Tersedia</button>
                                    <button type="button" @click="selectedTable.status = 'occupied'; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Terisi</button>
                                    <button type="button" @click="selectedTable.status = 'booked'; open = false" class="w-full text-left px-6 py-4 text-sm font-bold text-slate-600 hover:bg-brand/5 hover:text-brand transition">Booking</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 px-1">Nama Pemesan <span class="text-[10px] text-slate-400 font-normal tracking-normal">(Hanya jika meja terisi/booked)</span></label>
                        <input type="text" name="customer_name" :value="selectedTable?.customer_name" placeholder="Contoh: Pak Budi" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm">
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" :checked="selectedTable?.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            <span class="ms-3 text-sm font-bold text-slate-600">Status Aktif</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-5 bg-brand text-white rounded-[1.5rem] font-black text-lg transition shadow-2xl shadow-brand/30 hover:scale-[1.02] active:scale-[0.98] mt-4">SIMPAN PERUBAHAN</button>
                </form>
            </div>
        </div>
    </div>

    <!-- QR Preview Modal -->
    <div x-show="isQRModalOpen" style="display: none;" class="fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-6">
        <div @click.outside="closeQRModal()" class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300 flex flex-col md:flex-row">
            <!-- QR Section -->
            <div class="bg-slate-50 p-12 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-slate-200">
                <div id="large-qr" class="bg-white p-4 rounded-3xl shadow-xl mb-6 print-qr"></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] print-hide">Scan to Order</p>
            </div>

            <!-- Info Section -->
            <div class="p-12 flex-1 flex flex-col print-hide">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-brand rounded-2xl flex items-center justify-center text-white font-black text-xl italic">
                            M
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-900 leading-tight" x-text="'Meja ' + String(selectedTable?.number).padStart(2, '0')"></h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest" x-text="selectedTable?.type"></p>
                        </div>
                    </div>
                    <button @click="closeQRModal()" class="p-2 text-slate-400 hover:text-slate-600 transition"><i data-lucide="x" class="w-6 h-6"></i></button>
                </div>

                <div class="space-y-4 mb-10 flex-1">
                    <div class="flex justify-between items-center py-3 border-b border-slate-100">
                        <span class="text-sm font-bold text-slate-400">Nama Meja</span>
                        <span class="text-sm font-black text-slate-900" x-text="selectedTable?.name || '-'"></span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-slate-100">
                        <span class="text-sm font-bold text-slate-400">Kapasitas</span>
                        <span class="text-sm font-black text-slate-900" x-text="selectedTable?.capacity + ' Orang'"></span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-slate-100">
                        <span class="text-sm font-bold text-slate-400">Dibuat Pada</span>
                        <span class="text-sm font-black text-slate-900">{{ date('d M Y') }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button @click="downloadQR()" class="py-4 bg-brand text-white rounded-2xl font-black text-sm shadow-lg shadow-brand/20 hover:opacity-90 transition flex items-center justify-center gap-2">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span>PNG</span>
                    </button>
                    <button onclick="window.print()" class="py-4 border-2 border-slate-200 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-50 transition flex items-center justify-center gap-2">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        <span>CETAK</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function tableManager() {
        return {
            tables: {!! json_encode($tables) !!},
            searchQuery: '',
            filterStatus: 'all',
            currentPage: 1,
            itemsPerPage: 5,
            isAddModalOpen: false,
            isEditModalOpen: false,
            isQRModalOpen: false,
            selectedTable: null,
            baseUrl: window.location.origin + "/menu?meja=",

            init() {
                this.$nextTick(() => {
                    lucide.createIcons();
                    this.generateThumbnails();
                });
                
                this.$watch('searchQuery', () => {
                    this.currentPage = 1;
                    this.$nextTick(() => {
                        lucide.createIcons();
                        this.generateThumbnails();
                    });
                });

                this.$watch('filterStatus', () => {
                    this.currentPage = 1;
                    this.$nextTick(() => {
                        lucide.createIcons();
                        this.generateThumbnails();
                    });
                });

                this.$watch('currentPage', () => {
                    this.$nextTick(() => {
                        lucide.createIcons();
                        this.generateThumbnails();
                    });
                });
            },

            get filteredTables() {
                return this.tables.filter(t => {
                    const matchStatus = this.filterStatus === 'active' ? t.is_active : (this.filterStatus === 'inactive' ? !t.is_active : true);
                    const query = this.searchQuery.toLowerCase();
                    const matchSearch = String(t.number).includes(query) || 
                                      (t.name && t.name.toLowerCase().includes(query)) ||
                                      (t.customer_name && t.customer_name.toLowerCase().includes(query));
                    return matchStatus && matchSearch;
                });
            },

            get totalPages() {
                return Math.ceil(this.filteredTables.length / this.itemsPerPage) || 1;
            },

            get paginatedTables() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredTables.slice(start, end);
            },

            generateThumbnails() {
                this.paginatedTables.forEach(t => {
                    const el = document.getElementById('qr-' + t.id + '-thumb');
                    if (el && !el.hasChildNodes()) {
                        new QRCode(el, {
                            text: this.baseUrl + t.number,
                            width: 32,
                            height: 32,
                            colorDark : "#000000",
                            colorLight : "#ffffff",
                            correctLevel : QRCode.CorrectLevel.H
                        });
                    }
                });
            },

            openAddModal() {
                this.isAddModalOpen = true;
            },
            
            closeAddModal() {
                this.isAddModalOpen = false;
            },

            openEditModal(table) {
                this.selectedTable = Object.assign({}, table);
                this.isEditModalOpen = true;
            },
            
            closeEditModal() {
                this.isEditModalOpen = false;
                this.selectedTable = null;
            },

            openQRModal(table) {
                this.selectedTable = table;
                this.isQRModalOpen = true;
                
                this.$nextTick(() => {
                    const container = document.getElementById('large-qr');
                    container.innerHTML = '';
                    new QRCode(container, {
                        text: this.baseUrl + table.number,
                        width: 200,
                        height: 200,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                });
            },

            closeQRModal() {
                this.isQRModalOpen = false;
            },

            downloadQR() {
                const img = document.querySelector('#large-qr img');
                if(!img) return;
                const link = document.createElement('a');
                const tableName = this.selectedTable.name ? this.selectedTable.name.replace(/\s+/g, '_') : this.selectedTable.type;
                link.download = `QR_Meja_${this.selectedTable.number}_${tableName}.png`;
                link.href = img.src;
                link.click();
            },

            async toggleAvailability(table) {
                try {
                    const response = await fetch(`/admin/tables/${table.id}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    if(result.success) {
                        table.is_active = result.is_active;
                        const index = this.tables.findIndex(t => t.id === table.id);
                        if(index !== -1) this.tables[index].is_active = result.is_active;
                    } else {
                        table.is_active = !table.is_active;
                    }
                } catch (error) {
                    table.is_active = !table.is_active;
                    alert('Gagal mengubah status meja.');
                }
            },

            async deleteTable(id) {
                if(!confirm('Hapus meja ini? QR code meja ini tidak bisa digunakan lagi.')) return;
                
                try {
                    const response = await fetch(`/admin/tables/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    if(result.success) {
                        this.tables = this.tables.filter(t => t.id !== id);
                        this.$nextTick(() => this.generateThumbnails());
                    }
                } catch (error) {
                    alert('Gagal menghapus meja.');
                }
            }
        }
    }
</script>

<style>
    @media print {
        body * { visibility: hidden; }
        .print-qr, .print-qr * { visibility: visible; }
        .print-qr { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); border: none; box-shadow: none; }
        .print-hide { display: none !important; }
        #qrModal { background: white; }
    }
</style>
@endsection
