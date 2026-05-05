@extends('layouts.admin')

@section('title', 'Arsitektur Kategori')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Arsitektur Kategori</h1>
        <p class="text-slate-500 font-medium mt-1">Atur menu Anda ke dalam segmen yang logis dan mudah dinavigasi.</p>
    </div>
    <button onclick="document.getElementById('catModal').classList.remove('hidden')" class="px-6 py-3.5 bg-brand text-white rounded-2xl font-black text-sm transition shadow-xl shadow-brand/20 hover:scale-[1.02] active:scale-[0.98] flex items-center gap-2">
        <i data-lucide="plus-circle" class="w-5 h-5 text-white/50"></i>
        <span>BUAT KATEGORI BARU</span>
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($categories as $category)
    <div class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm relative group overflow-hidden flex flex-col">
        <div class="w-full h-40 bg-gray-50 rounded-[2rem] overflow-hidden mb-6 border border-gray-100 flex items-center justify-center">
            @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
            @else
                <div class="flex flex-col items-center gap-2 text-slate-300">
                    <i data-lucide="tag" class="w-10 h-10"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Tanpa Visual</span>
                </div>
            @endif
        </div>

        <div class="flex flex-col mb-6">
            <span class="text-xl font-black text-slate-900 tracking-tight">{{ $category->name }}</span>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">{{ count($category->products ?? []) }} Produk Terdaftar</span>
        </div>

        <div class="mt-auto flex items-center gap-3">
            <button onclick="openEditModal({{ json_encode($category) }})" class="flex-1 py-3 bg-gray-50 text-slate-500 hover:text-white hover:bg-brand transition rounded-2xl text-[10px] font-black uppercase tracking-widest border border-gray-100 hover:border-brand">Ubah Segmen</button>
            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini dan semua produk di dalamnya?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-3 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-2xl transition">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
        
        <div class="absolute -right-4 top-0 w-24 h-24 bg-brand/5 blur-[40px] rounded-full translate-x-10 -translate-y-10 group-hover:bg-brand/10 transition duration-500"></div>
    </div>
    @endforeach
</div>

<!-- Add Category Modal -->
<div id="catModal" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl relative overflow-hidden">
        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black tracking-tight text-slate-900">Tambah Kategori Baru</h3>
                    <button type="button" onclick="document.getElementById('catModal').classList.add('hidden')" class="p-2 hover:bg-gray-100 rounded-full transition">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Category Name</label>
                        <input type="text" name="name" required placeholder="Makanan Penutup, Menu Utama, dll." class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Visual Asset (Optional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm">
                    </div>
                </div>

                <button type="submit" class="w-full mt-10 py-5 bg-slate-900 text-white rounded-[1.5rem] font-black text-lg transition shadow-2xl shadow-slate-900/30 hover:bg-brand hover:scale-[1.02] active:scale-[0.98]">
                    BUAT SEGMEN
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCatModal" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm hidden">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl relative overflow-hidden">
        <form id="editCatForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="p-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl font-black tracking-tight text-slate-900">Ubah Kategori</h3>
                    <button type="button" onclick="document.getElementById('editCatModal').classList.add('hidden')" class="p-2 hover:bg-gray-100 rounded-full transition">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Category Name</label>
                        <input type="text" name="name" id="edit_cat_name" required class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Visual Asset (Optional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-brand outline-none font-bold text-sm">
                        <p class="text-[10px] text-slate-400 mt-2 italic px-1">Biarkan kosong untuk mempertahankan gambar saat ini</p>
                    </div>
                </div>

                <button type="submit" class="w-full mt-10 py-5 bg-brand text-white rounded-[1.5rem] font-black text-lg transition shadow-2xl shadow-brand/30 hover:bg-slate-900 hover:scale-[1.02] active:scale-[0.98]">
                    PERBARUI KATEGORI
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(category) {
    const modal = document.getElementById('editCatModal');
    const form = document.getElementById('editCatForm');
    const nameInput = document.getElementById('edit_cat_name');
    
    form.action = `/admin/categories/${category.id}`;
    nameInput.value = category.name;
    
    modal.classList.remove('hidden');
}
</script>
@endsection
