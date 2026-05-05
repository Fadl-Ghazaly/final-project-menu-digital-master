@extends('layouts.admin')

@section('title', 'Pesanan Masuk')
@section('page_title', 'Pesanan Masuk')

@section('content')
<div class="flex flex-col h-full" x-data="orderManager()" x-init="init()">
    <!-- Top Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 shrink-0">
        <!-- Filter Tabs -->
        <div class="flex p-1 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-x-auto no-scrollbar">
            <button @click="setFilter('semua')" :class="filterStatus === 'semua' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition shrink-0">Semua</button>
            <button @click="setFilter('pending')" :class="filterStatus === 'pending' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'text-slate-500 hover:text-brand'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition shrink-0">Baru</button>
            <button @click="setFilter('processing')" :class="filterStatus === 'processing' ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/20' : 'text-slate-500 hover:text-brand'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition shrink-0">Diproses</button>
            <button @click="setFilter('completed')" :class="filterStatus === 'completed' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'text-slate-500 hover:text-brand'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition shrink-0">Selesai</button>
            <button @click="setFilter('cancelled')" :class="filterStatus === 'cancelled' ? 'bg-red-500 text-white shadow-lg shadow-red-500/20' : 'text-slate-500 hover:text-brand'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition shrink-0">Batal</button>
        </div>

        <!-- Search -->
        <div class="relative group w-full md:w-80">
            <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari nomor pesanan..." 
                class="w-full pl-12 pr-6 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand focus:border-brand transition outline-none text-sm font-medium shadow-sm">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-brand transition"></i>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 flex-1 overflow-hidden h-[calc(100vh-200px)]">
        <!-- Order List (Left) -->
        <div class="lg:col-span-7 flex flex-col h-full overflow-hidden">
            <div class="flex-1 space-y-4 overflow-y-scroll pr-4 custom-scrollbar">
                <template x-for="order in paginatedOrders" :key="order.id">
                    <div @click="selectOrder(order)" 
                        :class="selectedOrder && selectedOrder.id === order.id ? 'border-brand ring-4 ring-brand/5 bg-brand/[0.02]' : 'border-transparent bg-white'"
                        class="p-5 rounded-[2.5rem] border-2 cursor-pointer transition-all shadow-sm group active:scale-[0.98]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-900 group-hover:bg-brand/10 transition">
                                    <i data-lucide="hash" class="w-4 h-4"></i>
                                </div>
                                <span class="text-sm font-black text-slate-900" x-text="String(order.id).padStart(3, '0')"></span>
                                <span :class="statusBadgeClass(order.status)" class="px-3 py-1 text-[9px] font-black uppercase rounded-lg" x-text="statusLabel(order.status)"></span>
                                <template x-if="order.rating">
                                    <div class="flex items-center gap-0.5 ml-1">
                                        <i data-lucide="star" class="w-2.5 h-2.5 fill-amber-400 text-amber-400"></i>
                                        <span class="text-[9px] font-black text-amber-600" x-text="order.rating"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                                <span x-text="formatTime(order.created_at)"></span>
                                <span class="opacity-30">•</span>
                                <span x-text="order.name || 'Meja -'"></span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex gap-4 items-center">
                                <div class="px-3 py-2 bg-slate-50 rounded-xl flex flex-col items-center justify-center min-w-[50px]">
                                    <span class="text-xs font-black text-slate-900" x-text="order.items.length"></span>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Items</span>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest line-clamp-1 max-w-[200px]" x-text="order.items.map(i => i.product.name).join(', ')"></p>
                                    <p class="text-[10px] text-slate-400 mt-0.5" x-text="'Dipesan pada ' + formatDate(order.created_at)"></p>
                                </div>
                            </div>
                            <span class="text-xl font-black text-slate-900 tracking-tighter" x-text="formatPrice(order.total_price)"></span>
                        </div>
                    </div>
                </template>
                
                <template x-if="filteredOrders.length === 0">
                    <div class="flex flex-col items-center justify-center py-24 text-slate-200">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                            <i data-lucide="package-search" class="w-10 h-10"></i>
                        </div>
                        <p class="font-black uppercase tracking-widest text-[10px]">Tidak ada pesanan ditemukan</p>
                    </div>
                </template>
            </div>

            <!-- Pagination Controls (Centered & Tidy) -->
            <div class="mt-6 flex justify-center shrink-0" x-show="totalPages > 1">
                <div class="flex items-center gap-6 bg-white p-2 px-6 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Halaman</span>
                        <p class="text-xs font-black text-slate-900"><span x-text="currentPage" class="text-brand"></span> <span class="text-slate-300 mx-1">/</span> <span x-text="totalPages"></span></p>
                    </div>
                    
                    <div class="w-px h-4 bg-slate-100"></div>

                    <div class="flex items-center gap-1">
                        <button @click="prevPage()" :disabled="currentPage === 1" class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-brand disabled:opacity-20 transition">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </button>
                        
                        <div class="flex items-center gap-1 mx-1">
                            <template x-for="p in visiblePages" :key="p === '...' ? Math.random() : p">
                                <div class="flex items-center">
                                    <template x-if="p === '...'">
                                        <span class="px-1.5 text-slate-300 font-black text-[10px]">...</span>
                                    </template>
                                    <template x-if="p !== '...'">
                                        <button @click="currentPage = p" 
                                            :class="currentPage === p ? 'bg-brand text-white shadow-md' : 'bg-white text-slate-500 hover:bg-slate-50'"
                                            class="w-8 h-8 rounded-lg border border-slate-100 text-[10px] font-black transition" x-text="p"></button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <button @click="nextPage()" :disabled="currentPage === totalPages" class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-brand disabled:opacity-20 transition">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Detail (Right) -->
        <div class="lg:col-span-5 hidden lg:block h-full overflow-hidden">
            <template x-if="selectedOrder">
                <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl overflow-y-auto h-full animate-in fade-in slide-in-from-right-4 duration-300 custom-scrollbar flex flex-col">
                    <!-- Detail Header -->
                    <div class="p-8 border-b border-slate-100 bg-slate-50/30 sticky top-0 bg-white z-10 shrink-0">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-brand/5 flex items-center justify-center text-brand">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                </div>
                                <h3 class="text-2xl font-black text-slate-900 tracking-tighter" x-text="'#ORD-' + String(selectedOrder.id).padStart(3, '0')"></h3>
                            </div>
                            <span :class="statusBadgeClass(selectedOrder.status)" class="px-4 py-1.5 text-[10px] font-black uppercase rounded-xl" x-text="statusLabel(selectedOrder.status)"></span>
                        </div>
                        <div class="flex items-center gap-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-brand"></i>
                                <span x-text="formatTime(selectedOrder.created_at)"></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="armchair" class="w-3.5 h-3.5 text-brand"></i>
                                <span x-text="selectedOrder.name || 'Meja -'"></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-brand"></i>
                                <span x-text="formatDate(selectedOrder.created_at)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Content -->
                    <div class="p-8 flex-1">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-brand rounded-full"></div>
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Daftar Pesanan</h4>
                        </div>
                        
                        <div class="space-y-6 mb-8">
                            <template x-for="item in selectedOrder.items" :key="item.id">
                                <div class="flex items-start justify-between group">
                                    <div class="flex gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center font-black text-brand text-xs group-hover:bg-brand group-hover:text-white transition duration-300" x-text="item.quantity + 'x'"></div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800" x-text="item.product.name"></p>
                                            <p class="text-[10px] text-slate-400 font-medium italic" x-text="item.notes || 'Tanpa catatan'"></p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-black text-slate-900" x-text="formatPrice(item.price * item.quantity)"></span>
                                </div>
                            </template>
                        </div>

                        <div class="pt-6 border-t border-slate-100 space-y-4 bg-slate-50/50 p-6 rounded-3xl border border-slate-100">
                            <div class="flex justify-between text-xs font-bold text-slate-400 uppercase tracking-widest">
                                <span>Subtotal</span>
                                <span class="text-slate-900" x-text="formatPrice(selectedOrder.total_price)"></span>
                            </div>
                            <div class="flex justify-between text-xs font-bold text-slate-400 uppercase tracking-widest">
                                <span>Pajak (10%)</span>
                                <span class="text-slate-900" x-text="formatPrice(selectedOrder.total_price * 0.1)"></span>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">TOTAL AKHIR</span>
                                <span class="text-2xl font-black text-brand tracking-tighter" x-text="formatPrice(selectedOrder.total_price * 1.1)"></span>
                            </div>
                        </div>

                        <!-- Rating & Review Display -->
                        <template x-if="selectedOrder.rating">
                            <div class="mt-8 p-6 bg-amber-50 rounded-3xl border border-amber-100 animate-in zoom-in-95 duration-300">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="flex gap-0.5">
                                        <template x-for="i in 5">
                                            <i data-lucide="star" 
                                               :class="i <= selectedOrder.rating ? 'fill-amber-400 text-amber-400' : 'text-slate-200'"
                                               class="w-4 h-4"></i>
                                        </template>
                                    </div>
                                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Ulasan Pelanggan</span>
                                </div>
                                <p class="text-sm font-bold text-slate-700 italic" x-text="selectedOrder.review || 'Pelanggan tidak memberikan komentar.'"></p>
                            </div>
                        </template>

                        <!-- Actions -->
                        <div class="mt-8 grid grid-cols-2 gap-4 shrink-0" x-show="selectedOrder.status === 'pending'">
                            <button @click="updateStatus(selectedOrder, 'processing')" class="w-full py-4 bg-emerald-500 text-white rounded-2xl font-black text-sm shadow-xl shadow-emerald-500/20 hover:scale-[1.02] active:scale-95 transition disabled:opacity-50" :disabled="loading">TERIMA</button>
                            <button @click="updateStatus(selectedOrder, 'cancelled')" class="w-full py-4 border-2 border-red-100 text-red-500 rounded-2xl font-black text-sm hover:bg-red-50 active:scale-95 transition disabled:opacity-50" :disabled="loading">TOLAK</button>
                        </div>
                        <div class="mt-8 shrink-0" x-show="selectedOrder.status === 'processing'">
                            <button @click="updateStatus(selectedOrder, 'completed')" class="w-full py-4 bg-blue-500 text-white rounded-2xl font-black text-sm shadow-xl shadow-blue-500/20 hover:scale-[1.02] active:scale-95 transition disabled:opacity-50" :disabled="loading">SELESAIKAN PESANAN</button>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="!selectedOrder">
                <div class="h-full flex flex-col items-center justify-center text-slate-200 border-4 border-dashed border-slate-50 rounded-[3rem]">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="mouse-pointer-2" class="w-10 h-10"></i>
                    </div>
                    <p class="font-black uppercase tracking-widest text-[10px]">Pilih pesanan untuk melihat detail</p>
                </div>
            </template>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Forced & Visible Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { 
        background: #E8781A; 
        border-radius: 10px;
        border: 2px solid #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d66b15; }
</style>

<script>
    function orderManager() {
        return {
            orders: {!! json_encode($orders) !!},
            selectedOrder: null,
            filterStatus: 'semua',
            searchQuery: '',
            loading: false,
            
            // Pagination States
            currentPage: 1,
            itemsPerPage: 5,

            init() {
                if (this.orders.length > 0) {
                    this.selectedOrder = this.orders[0];
                }
                this.$nextTick(() => lucide.createIcons());
                
                // Watch for changes to refresh icons
                this.$watch('filterStatus', () => this.$nextTick(() => lucide.createIcons()));
                this.$watch('searchQuery', () => this.$nextTick(() => lucide.createIcons()));
                this.$watch('currentPage', () => this.$nextTick(() => lucide.createIcons()));

                // Auto Refresh Polling
                setInterval(() => {
                    this.fetchOrders();
                }, 5000);
            },

            async fetchOrders() {
                try {
                    const response = await fetch('/admin/orders?status=' + this.filterStatus, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (response.ok) {
                        const newOrders = await response.json();
                        
                        // Detect if there are new orders (optional but good for UX)
                        if (JSON.stringify(this.orders) !== JSON.stringify(newOrders)) {
                            this.orders = newOrders;
                            
                            // Keep selected order updated
                            if (this.selectedOrder) {
                                const updatedSelected = this.orders.find(o => o.id === this.selectedOrder.id);
                                if (updatedSelected) {
                                    this.selectedOrder = updatedSelected;
                                }
                            }
                            
                            this.$nextTick(() => lucide.createIcons());
                        }
                    }
                } catch (error) {
                    console.error('Auto-refresh failed:', error);
                }
            },

            setFilter(status) {
                this.filterStatus = status;
                this.currentPage = 1;
            },

            get filteredOrders() {
                return this.orders.filter(order => {
                    const matchStatus = this.filterStatus === 'semua' || order.status === this.filterStatus;
                    const matchSearch = String(order.id).includes(this.searchQuery);
                    return matchStatus && matchSearch;
                });
            },

            get totalPages() {
                return Math.ceil(this.filteredOrders.length / this.itemsPerPage);
            },

            get paginatedOrders() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredOrders.slice(start, end);
            },

            get visiblePages() {
                const total = this.totalPages;
                const current = this.currentPage;
                const pages = [];

                if (total <= 5) {
                    for (let i = 1; i <= total; i++) pages.push(i);
                } else {
                    pages.push(1);
                    if (current > 3) pages.push('...');
                    
                    const start = Math.max(2, current - 1);
                    const end = Math.min(total - 1, current + 1);
                    
                    for (let i = start; i <= end; i++) {
                        if (!pages.includes(i)) pages.push(i);
                    }
                    
                    if (current < total - 2) pages.push('...');
                    if (!pages.includes(total)) pages.push(total);
                }
                return pages;
            },

            prevPage() {
                if (this.currentPage > 1) this.currentPage--;
            },

            nextPage() {
                if (this.currentPage < this.totalPages) this.currentPage++;
            },

            selectOrder(order) {
                this.selectedOrder = order;
                this.$nextTick(() => lucide.createIcons());
            },

            statusBadgeClass(status) {
                const colors = {
                    'pending': 'bg-amber-100 text-amber-600',
                    'processing': 'bg-blue-100 text-blue-600',
                    'completed': 'bg-emerald-100 text-emerald-700',
                    'cancelled': 'bg-red-100 text-red-600'
                };
                return colors[status] || 'bg-slate-100 text-slate-600';
            },

            statusLabel(status) {
                const labels = {
                    'pending': 'BARU',
                    'processing': 'DIPROSES',
                    'completed': 'SELESAI',
                    'cancelled': 'BATAL'
                };
                return labels[status] || status.toUpperCase();
            },

            formatPrice(price) {
                return 'Rp ' + Number(price).toLocaleString('id-ID');
            },

            formatTime(timestamp) {
                const date = new Date(timestamp);
                return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            },

            formatDate(timestamp) {
                const date = new Date(timestamp);
                return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            },

            async updateStatus(order, newStatus) {
                if (!confirm(`Ubah status pesanan ke ${newStatus}?`)) return;
                
                this.loading = true;
                try {
                    const response = await fetch(`/admin/orders/${order.id}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: newStatus })
                    });

                    const result = await response.json();
                    if (result.success) {
                        const targetOrder = this.orders.find(o => o.id === order.id);
                        if (targetOrder) targetOrder.status = newStatus;
                        
                        if (this.selectedOrder && this.selectedOrder.id === order.id) {
                            this.selectedOrder.status = newStatus;
                        }
                    }
                } catch (error) {
                    alert('Gagal memperbarui status');
                } finally {
                    this.loading = false;
                    this.$nextTick(() => lucide.createIcons());
                }
            }
        }
    }
</script>
@endsection
