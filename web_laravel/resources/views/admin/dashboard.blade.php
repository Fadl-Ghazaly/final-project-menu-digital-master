@extends('layouts.admin')

@section('title', 'Dashboard Overview')
@section('page_title', 'Ringkasan Dashboard')

@section('content')
<div x-data="dashboardManager()" x-init="init()">
    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Card 1: Total Orders Today -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-brand/10 text-brand rounded-xl flex items-center justify-center">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Hari Ini</span>
            </div>
            <span class="block text-3xl font-black text-slate-900 leading-none mb-1" x-text="numberFormat(stats.totalOrdersToday)"></span>
            <p class="text-xs font-bold text-slate-500">Total Pesanan</p>
        </div>

        <!-- Card 2: Income Today -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Income</span>
            </div>
            <span class="block text-2xl font-black text-slate-900 leading-none mb-1" x-text="moneyFormat(stats.totalIncomeToday)"></span>
            <p class="text-xs font-bold text-slate-500">Pendapatan Hari Ini</p>
        </div>

        <!-- Card 3: Best Selling Product -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="heart" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Favorite</span>
            </div>
            <span class="block text-xl font-black text-slate-900 leading-none mb-1 line-clamp-1" x-text="stats.popularProduct"></span>
            <p class="text-xs font-bold text-slate-500">Menu Terlaris</p>
        </div>

        <!-- Card 4: Table Occupancy -->
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="armchair" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Occupancy</span>
            </div>
            <span class="block text-3xl font-black text-slate-900 leading-none mb-1">
                <span x-text="stats.activeTables"></span> 
                <span class="text-lg text-slate-400 font-medium" x-text="'/ ' + stats.totalTables"></span>
            </span>
            <p class="text-xs font-bold text-slate-500">Meja Aktif</p>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Grafik Penjualan</h3>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Pantau tren performa bisnismu</p>
            </div>
            <div class="flex p-1 bg-slate-100 rounded-xl">
                <button @click="setChartFilter('daily')" 
                    :class="chartFilter === 'daily' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                    class="px-4 py-2 text-xs font-black rounded-lg transition">Harian</button>
                <button @click="setChartFilter('weekly')" 
                    :class="chartFilter === 'weekly' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                    class="px-4 py-2 text-xs font-bold rounded-lg transition">Mingguan</button>
                <button @click="setChartFilter('monthly')" 
                    :class="chartFilter === 'monthly' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                    class="px-4 py-2 text-xs font-bold rounded-lg transition">Bulanan</button>
            </div>
        </div>
        <div class="h-[350px] w-full">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Recent Orders (Left Column - 2/3 width) -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">5 Pesanan Terbaru</h3>
                <a href="/admin/orders" class="text-xs font-bold text-brand hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No. Pesanan</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Meja</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Items</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="order in recentOrders" :key="order.id">
                            <tr>
                                <td class="px-8 py-4 font-bold text-sm text-slate-900" x-text="'#ORD-' + String(order.id).padStart(3, '0')"></td>
                                <td class="px-8 py-4 text-sm font-medium text-slate-600" x-text="order.name || 'Meja -'"></td>
                                <td class="px-8 py-4 text-sm text-slate-500">
                                    <span x-text="order.items.map(i => i.quantity + 'x ' + i.product.name).join(', ')"></span>
                                </td>
                                <td class="px-8 py-4 font-black text-slate-900" x-text="moneyFormat(order.total_price)"></td>
                                <td class="px-8 py-4 text-center">
                                    <span :class="statusBadgeClass(order.status)" class="px-3 py-1 text-[10px] font-black uppercase rounded-lg" x-text="order.status"></span>
                                </td>
                            </tr>
                        </template>
                        <template x-if="recentOrders.length === 0">
                            <tr>
                                <td colspan="5" class="px-8 py-10 text-center text-slate-400 font-medium">Belum ada pesanan masuk.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Products (Right Column - 1/3 width) -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
            <h3 class="text-xl font-black text-slate-900 tracking-tight mb-8">Top 5 Produk Terpopuler</h3>
            <div class="space-y-6">
                <template x-for="(item, index) in topProducts" :key="index">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 flex items-center justify-center font-black rounded-lg text-sm" 
                                  :class="index === 0 ? 'text-brand bg-brand/10' : 'text-slate-400 bg-slate-50'"
                                  x-text="index + 1"></span>
                            <span class="font-bold text-sm text-slate-700 line-clamp-1" x-text="item.product.name"></span>
                        </div>
                        <span class="text-xs font-black text-slate-400 shrink-0" x-text="item.total_sold + ' terjual'"></span>
                    </div>
                </template>
                <template x-if="topProducts.length === 0">
                    <p class="text-center text-slate-400 text-sm py-10 font-medium">Data belum tersedia.</p>
                </template>
            </div>
            
            <template x-if="topProducts.length > 0">
                <div class="mt-10 p-5 bg-brand/5 rounded-3xl border border-brand/10">
                    <p class="text-xs font-bold text-brand leading-relaxed italic">Tip: Terus pantau menu terlaris untuk optimasi stok bahan baku Anda.</p>
                </div>
            </template>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function dashboardManager() {
        return {
            stats: {!! json_encode($stats) !!},
            labels: {!! json_encode($labels) !!},
            salesValues: {!! json_encode($salesValues) !!},
            recentOrders: {!! json_encode($recentOrders) !!},
            topProducts: {!! json_encode($topProducts) !!},
            chartFilter: 'daily',
            chart: null,

            init() {
                this.initChart();
                this.$nextTick(() => lucide.createIcons());
                
                // Polling for updates every 10 seconds
                setInterval(() => {
                    this.refreshData();
                }, 10000);
            },

            async refreshData() {
                try {
                    const timestamp = new Date().getTime();
                    const response = await fetch(`/admin?filter=${this.chartFilter}&t=${timestamp}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        console.log('Dashboard Data Received:', data);
                        this.stats = data.stats;
                        this.labels = data.labels;
                        this.salesValues = data.salesValues;
                        this.recentOrders = data.recentOrders;
                        this.topProducts = data.topProducts;
                        
                        this.updateChart();
                        this.$nextTick(() => lucide.createIcons());
                    }
                } catch (error) {
                    console.error('Failed to refresh dashboard data:', error);
                }
            },

            setChartFilter(filter) {
                this.chartFilter = filter;
                this.refreshData();
            },

            initChart() {
                const canvas = document.getElementById('salesChart');
                if (!canvas) return;

                // Ensure data exists
                if (!this.labels || this.labels.length === 0) {
                    console.warn('Cannot init chart: labels are empty');
                    return;
                }

                const ctx = canvas.getContext('2d');
                this.chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: this.labels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: this.salesValues,
                            borderColor: '#E8781A',
                            backgroundColor: 'rgba(232, 120, 26, 0.1)',
                            borderWidth: 4,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#E8781A',
                            pointBorderWidth: 2,
                        }]
                    },
                    options: this.getChartOptions()
                });
            },

            updateChart() {
                if (!this.chart) {
                    this.initChart();
                    return;
                }

                // If labels count changed, recreate to avoid rendering issues
                if (this.chart.data.labels.length !== this.labels.length) {
                    this.chart.destroy();
                    this.initChart();
                } else {
                    // Just update the data points
                    this.chart.data.labels = this.labels;
                    this.chart.data.datasets[0].data = this.salesValues;
                    this.chart.update('none'); // Update without animation for polling
                }
            },

            getChartOptions() {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.03)' },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'jt';
                                    if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'rb';
                                    return 'Rp ' + value;
                                },
                                font: { family: 'Outfit', weight: '700', size: 11 }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Outfit', weight: '700', size: 11 } }
                        }
                    }
                };
            },

            moneyFormat(value) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
            },

            numberFormat(value) {
                return new Intl.NumberFormat('id-ID').format(value);
            },

            statusBadgeClass(status) {
                const colors = {
                    'pending': 'bg-amber-100 text-amber-700',
                    'processing': 'bg-blue-100 text-blue-700',
                    'completed': 'bg-emerald-100 text-emerald-700',
                    'cancelled': 'bg-red-100 text-red-700'
                };
                return colors[status.toLowerCase()] || 'bg-slate-100 text-slate-700';
            }
        }
    }
</script>
@endsection
