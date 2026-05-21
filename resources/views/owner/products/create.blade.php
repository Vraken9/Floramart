<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Bunga Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('owner.products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">Nama Bunga/Buket *</label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Contoh: Buket Mawar Merah Premium">
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="block font-medium text-sm text-gray-700">Kategori *</label>
                            <select name="category_id" id="category_id" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="block font-medium text-sm text-gray-700">Harga (Rp) *</label>
                            <input type="number" name="price" id="price" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Contoh: 150000">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">Deskripsi Lengkap *</label>
                            <textarea name="description" id="description" rows="4" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Jelaskan detail bunga, ukuran, dan bonus jika ada..."></textarea>
                        </div>

                        <div x-data="{ uploadType: 'file', filePreview: null, urlPreview: '' }" class="mb-6 p-4 border border-gray-200 rounded-md bg-gray-50">
                            <label class="block font-medium text-sm text-gray-700 mb-3">Metode Pengisian Foto *</label>

                            <div class="flex items-center space-x-6 mb-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" x-model="uploadType" value="file" class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">Upload dari Komputer</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" x-model="uploadType" value="url" class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">Gunakan Link (URL)</span>
                                </label>
                            </div>

                            <div x-show="uploadType === 'file'" x-transition>
                                <input type="file" name="image_file" id="image_file" accept="image/*"
                                    @change="filePreview = URL.createObjectURL($event.target.files[0])"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">

                                <div x-show="filePreview" class="mt-4" style="display: none;">
                                    <p class="text-xs text-gray-500 mb-1">Preview Foto:</p>
                                    <img :src="filePreview" class="h-40 w-auto object-cover rounded-md border border-gray-300 shadow-sm">
                                </div>
                            </div>

                            <div x-show="uploadType === 'url'" x-transition style="display: none;">
                                <input type="url" name="image_url" id="image_url" x-model="urlPreview"
                                    placeholder="Contoh: https://i.ibb.co/gambar-bunga.jpg"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <p class="text-xs text-gray-500 mt-1">Gunakan link dari Google Drive (Direct), ImgBB, atau Cloud Storage lainnya.</p>

                                <div x-show="urlPreview" class="mt-4" style="display: none;">
                                    <p class="text-xs text-gray-500 mb-1">Preview Foto:</p>
                                    <img :src="urlPreview" x-on:error="$el.style.display='none'" x-on:load="$el.style.display='block'" class="h-40 w-auto object-cover rounded-md border border-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('owner.products.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900 underline">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700">
                                Simpan Bunga
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
