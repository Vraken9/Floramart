<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Data Bunga - FloraMart</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 font-sans flex flex-col min-h-screen">
    <x-navigation />

    <main class="flex-grow py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center">
                <a href="{{ route('owner.products.index') }}" class="text-gray-500 hover:text-[#7c4959] mr-4 transition-colors" title="Kembali"><i class="fa-solid fa-arrow-left text-xl"></i></a>
                <h2 class="font-bold text-2xl text-[#7c4959] leading-tight">
                    {{ __('Ubah Data Bunga') }}
                </h2>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-[#d4ccc0]/50">
                <div class="p-4 md:p-6 lg:p-8 text-gray-900">

                    <form method="POST" action="{{ route('owner.products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') 
                        <div class="mb-5">
                            <label for="name" class="block font-bold text-sm text-gray-700 mb-1">Nama Bunga/Buket <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ $product->name }}" required class="mt-1 block w-full border-gray-300 focus:border-[#7c4959] focus:ring-[#7c4959] rounded-md shadow-sm">
                        </div>

                        <div class="mb-5">
                            <label for="category_id" class="block font-bold text-sm text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" required class="mt-1 block w-full border-gray-300 focus:border-[#7c4959] focus:ring-[#7c4959] rounded-md shadow-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label for="price" class="block font-bold text-sm text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" id="price" value="{{ $product->price }}" required class="mt-1 block w-full border-gray-300 focus:border-[#7c4959] focus:ring-[#7c4959] rounded-md shadow-sm">
                        </div>

                        <div class="mb-5">
                            <label for="description" class="block font-bold text-sm text-gray-700 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="description" id="description" rows="4" required class="mt-1 block w-full border-gray-300 focus:border-[#7c4959] focus:ring-[#7c4959] rounded-md shadow-sm">{{ $product->description }}</textarea>
                        </div>

                        <div x-data="{ uploadType: '{{ str_starts_with($product->image_path, 'http') ? 'url' : 'file' }}', filePreview: null, urlPreview: '{{ str_starts_with($product->image_path, 'http') ? $product->image_path : '' }}' }" class="mb-8 p-5 border border-[#d4ccc0]/50 rounded-lg bg-[#d4ccc0]/10">
                            <label class="block font-bold text-sm text-[#7c4959] mb-3">Metode Pengisian Foto <span class="text-red-500">*</span></label>

                            <div class="flex flex-col sm:flex-row gap-4 mb-5">
                                <label class="inline-flex items-center cursor-pointer bg-white px-4 py-2 rounded-md border border-gray-200 shadow-sm flex-1">
                                    <input type="radio" x-model="uploadType" value="file" class="text-[#7c4959] border-gray-300 focus:ring-[#7c4959]">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">Upload dari Komputer</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer bg-white px-4 py-2 rounded-md border border-gray-200 shadow-sm flex-1">
                                    <input type="radio" x-model="uploadType" value="url" class="text-[#7c4959] border-gray-300 focus:ring-[#7c4959]">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">Gunakan Link (URL)</span>
                                </label>
                            </div>

                            <div x-show="uploadType === 'file'" x-transition>
                                <input type="file" name="image_file" id="image_file" accept="image/*"
                                    @change="filePreview = URL.createObjectURL($event.target.files[0])"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#7c4959]/10 file:text-[#7c4959] hover:file:bg-[#7c4959]/20 transition-colors">

                                <div class="mt-4">
                                    <p class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Preview Foto Saat Ini/Baru:</p>
                                    <template x-if="filePreview">
                                        <img :src="filePreview" class="h-48 w-auto object-cover rounded-lg border border-gray-200 shadow-sm">
                                    </template>
                                    <template x-if="!filePreview && '{{ str_starts_with($product->image_path, 'http') }}' == '' && '{{ $product->image_path }}' != ''">
                                        <img src="{{ asset('images/' . $product->image_path) }}" class="h-48 w-auto object-cover rounded-lg border border-gray-200 shadow-sm">
                                    </template>
                                </div>
                            </div>

                            <div x-show="uploadType === 'url'" x-transition style="display: none;">
                                <input type="url" name="image_url" id="image_url" x-model="urlPreview" class="mt-1 block w-full border-gray-300 focus:border-[#7c4959] focus:ring-[#7c4959] rounded-md shadow-sm">

                                <div class="mt-4">
                                    <p class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Preview Foto Link:</p>
                                    <div x-show="urlPreview">
                                        <img :src="urlPreview" x-on:error="$el.style.display='none'" x-on:load="$el.style.display='block'" class="h-48 w-auto object-cover rounded-lg border border-gray-200 shadow-sm">
                                    </div>
                                    <div x-show="!urlPreview && '{{ str_starts_with($product->image_path, 'http') }}' == '1'">
                                        <img src="{{ $product->image_path }}" class="h-48 w-auto object-cover rounded-lg border border-gray-200 shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4 pt-5 border-t border-gray-200">
                            <a href="{{ route('owner.products.index') }}" class="mr-4 text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">Batal</a>
                            <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent rounded-md font-bold text-sm text-white bg-[#7c4959] hover:bg-[#5d3642] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#7c4959] transition-colors shadow-sm">
                                <i class="fa-solid fa-save mr-2"></i> Perbarui Bunga
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>
</body>
</html>
