@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page_title', 'Laporan Penjualan')

@section('content')
<div class="flex flex-col h-full" x-data="reportManager()" x-init="init()">
    <!-- Header with Export & Filter -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex p-1 bg-white border border-slate-200 rounded-2xl shadow-sm relative">
                <!-- Loading indicator overlay -->
                <div x-show="isLoading" class="absolute inset-0 bg-white/50 rounded-2xl flex items-center justify-center backdrop-blur-[1px] z-10" style="display: none;">
                    <i data-lucide="loader-2" class="w-5 h-5 text-brand animate-spin"></i>
                </div>
                
                <button @click="fetchData('day')" :class="period === 'day' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition">Harian</button>
                <button @click="fetchData('week')" :class="period === 'week' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition">Mingguan</button>
                <button @click="fetchData('month')" :class="period === 'month' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition">Bulanan</button>
                <button @click="period = 'custom'" :class="period === 'custom' ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-500 hover:text-brand'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition">Kustom</button>
            </div>

            <!-- Custom Date Inputs -->
            <div x-show="period === 'custom'" x-transition class="flex items-center gap-2 p-1 bg-white border border-slate-200 rounded-2xl shadow-sm">
                <input type="date" x-model="startDate" class="bg-transparent border-none focus:ring-0 text-xs font-bold text-slate-600 px-3 py-1">
                <span class="text-slate-300 font-black">-</span>
                <input type="date" x-model="endDate" class="bg-transparent border-none focus:ring-0 text-xs font-bold text-slate-600 px-3 py-1">
                <button @click="fetchData('custom')" class="p-2 bg-slate-900 text-white rounded-xl hover:bg-black transition">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button @click="window.open('/admin/reports/export/csv?period=' + period, '_blank')" class="px-5 py-3.5 border-2 border-brand/20 text-brand hover:bg-brand hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                Export CSV
            </button>
            <button @click="window.open('/admin/reports/export/pdf?period=' + period, '_blank')" class="px-5 py-3.5 border-2 border-brand/20 text-brand hover:bg-brand hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition flex items-center gap-2">
                <i data-lucide="file-type-2" class="w-4 h-4"></i>
                Export PDF
            </button>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Total Pendapatan</span>
            <span class="block text-2xl font-black text-slate-900 leading-none mb-2" x-text="formatRupiah(data.totalRevenue)"></span>
            <div class="flex items-center gap-1 text-emerald-500 text-[10px] font-black">
                <i data-lucide="trending-up" class="w-3 h-3"></i>
                <span x-text="period === 'day' ? 'HARI INI' : (period === 'week' ? 'MINGGU INI' : 'BULAN INI')"></span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Jumlah Pesanan</span>
            <span class="block text-3xl font-black text-slate-900 leading-none mb-1" x-text="data.totalOrders"></span>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Transaksi Berhasil</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Rata-rata / Hari</span>
            <span class="block text-2xl font-black text-slate-900 leading-none mb-1" x-text="formatRupiah(data.averagePerDay)"></span>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Gross Revenue</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Menu Terlaris</span>
            <span class="block text-lg font-black text-slate-900 leading-none mb-1 truncate" x-text="data.topMenuName"></span>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Most Ordered Item</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Sales Chart (Left - 2/3) -->
        <div class="lg:col-span-8 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm relative">
            <div x-show="isLoading" class="absolute inset-0 bg-white/60 rounded-[2.5rem] flex items-center justify-center backdrop-blur-sm z-10" style="display: none;">
                <i data-lucide="loader-2" class="w-8 h-8 text-brand animate-spin"></i>
            </div>
            
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Grafik Penjualan</h3>
                <span class="px-3 py-1 bg-brand/10 text-brand text-[10px] font-black uppercase rounded-lg border border-brand/10" x-text="period === 'day' ? 'Harian (Jam)' : (period === 'week' ? 'Mingguan (Hari)' : 'Bulanan (Tgl)')"></span>
            </div>
            
            <div class="h-[400px] w-full">
                <canvas id="reportsChart"></canvas>
            </div>
        </div>

        <!-- Top Products (Right - 1/3) -->
        <div class="lg:col-span-4 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm relative">
            <div x-show="isLoading" class="absolute inset-0 bg-white/60 rounded-[2.5rem] flex items-center justify-center backdrop-blur-sm z-10" style="display: none;"></div>
            
            <h3 class="text-xl font-black text-slate-900 tracking-tight mb-8">Top 5 Menu Terlaris</h3>
            
            <div class="space-y-6">
                <template x-for="(menu, index) in data.topMenus" :key="index">
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <span :class="index === 0 ? 'bg-brand/10 text-brand' : 'bg-slate-50 text-slate-400'" class="w-8 h-8 flex items-center justify-center font-black rounded-lg text-sm transition group-hover:bg-brand group-hover:text-white" x-text="index + 1"></span>
                            <div>
                                <p class="text-sm font-bold text-slate-800" x-text="menu.name"></p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase" x-text="menu.qty + ' Porsi'"></p>
                            </div>
                        </div>
                        <span class="text-sm font-black text-slate-900" x-text="formatShortRupiah(menu.revenue)"></span>
                    </div>
                </template>
                
                <template x-if="data.topMenus.length === 0">
                    <div class="py-10 text-center flex flex-col items-center">
                        <i data-lucide="inbox" class="w-10 h-10 text-slate-200 mb-3"></i>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum Ada Data</p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.initialReportData = {!! json_encode($reportData) !!};
    function reportManager() {
        return {
            period: '{{ $period }}',
            data: window.initialReportData,
            startDate: new Date().toISOString().split('T')[0],
            endDate: new Date().toISOString().split('T')[0],

            chartInstance: null,
            isLoading: false,

            init() {
                this.$nextTick(() => {
                    lucide.createIcons();
                    this.initChart();
                });
            },

            formatRupiah(value) {
                if(!value) return 'Rp 0';
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
            },
            
            formatShortRupiah(value) {
                if(!value) return 'Rp 0';
                if(value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1).replace('.0', '') + 'M';
                if(value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'k';
                return 'Rp ' + value;
            },

            async fetchData(selectedPeriod) {
                if (this.isLoading) return;
                
                this.period = selectedPeriod;
                this.isLoading = true;

                try {
                    let url = `/admin/reports?period=${selectedPeriod}`;
                    if (selectedPeriod === 'custom') {
                        url += `&start_date=${this.startDate}&end_date=${this.endDate}`;
                    }
                    
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const newData = await response.json();
                    this.data = newData;
                    this.updateChart();
                } catch (error) {
                    console.error("Gagal memuat laporan", error);
                } finally {
                    this.isLoading = false;
                    this.$nextTick(() => lucide.createIcons());
                }
            },

            initChart() {
                const ctx = document.getElementById('reportsChart').getContext('2d');
                
                this.chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: this.data.chartLabels,
                        datasets: [{
                            label: 'Pendapatan',
                            data: this.data.chartData,
                            borderColor: '#E8781A',
                            backgroundColor: 'rgba(232, 120, 26, 0.05)',
                            borderWidth: 4,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: '#E8781A',
                            pointHoverBorderWidth: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
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
                                        if(value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'M';
                                        if(value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'k';
                                        return 'Rp ' + value;
                                    },
                                    font: { family: 'Outfit', weight: '700', size: 10 }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Outfit', weight: '700', size: 10 } }
                            }
                        }
                    }
                });
            },
            
            updateChart() {
                if (!this.chartInstance) return;
                this.chartInstance.data.labels = this.data.chartLabels;
                this.chartInstance.data.datasets[0].data = this.data.chartData;
                this.chartInstance.update();
            }
        }
    }
</script>
@endsection
