<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pendaftaran Toko Bunga (Florist)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Dasar Toko</h3>
                        <p class="text-sm text-gray-500">Lengkapi data di bawah ini untuk mulai berjualan di FloraMart.</p>
                    </div>

                    <form method="POST" action="{{ route('shop.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">Nama Toko *</label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Contoh: Flora Indah Banjarnegara">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">Deskripsi Toko *</label>
                            <textarea name="description" id="description" rows="3" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Ceritakan keunggulan toko bunga Anda..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="block font-medium text-sm text-gray-700">Alasan Mendaftar *</label>
                            <textarea name="reason" id="reason" rows="2" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Tuliskan alasan mengapa Anda ingin membuka toko di FloraMart..."></textarea>
                        </div>

                        <!-- Alpine.js Component untuk Dynamic Dropdown -->
                        <div x-data="{
                            regencies: {{ Js::from($regencies) }},
                            selectedRegency: '',
                            districts: [],
                            selectedDistrict: '',
                            
                            updateDistricts() {
                                this.selectedDistrict = '';
                                if (!this.selectedRegency) {
                                    this.districts = [];
                                    return;
                                }
                                const regency = this.regencies.find(r => r.id == this.selectedRegency);
                                this.districts = regency ? regency.districts : [];
                            }
                        }">
                        
                            <div class="mb-4">
                                <label for="regency_id" class="block font-medium text-sm text-gray-700">Kabupaten/Kota (Lokasi Operasional) *</label>
                                <select x-model="selectedRegency" @change="updateDistricts()" id="regency_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="" disabled selected>-- Pilih Kabupaten/Kota --</option>
                                    <template x-for="regency in regencies" :key="regency.id">
                                        <option :value="regency.id" x-text="regency.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="district_id" class="block font-medium text-sm text-gray-700">Kecamatan (Lokasi Operasional) *</label>
                                <select x-model="selectedDistrict" name="district_id" id="district_id" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" :disabled="districts.length === 0">
                                    <option value="" disabled selected>-- Pilih Kecamatan --</option>
                                    <template x-for="district in districts" :key="district.id">
                                        <option :value="district.id" x-text="district.name"></option>
                                    </template>
                                </select>
                            </div>
                            
                        </div>

                        <div class="mb-4">
                            <label for="address_detail" class="block font-medium text-sm text-gray-700">Alamat Lengkap *</label>
                            <input type="text" name="address_detail" id="address_detail" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Nama jalan, nomor bangunan, RT/RW">
                        </div>

                        <div class="mb-6">
                            <label for="whatsapp_number" class="block font-medium text-sm text-gray-700">Nomor WhatsApp *</label>
                            <input type="text" name="whatsapp_number" id="whatsapp_number" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Contoh: 628123456789">
                        </div>

                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Butuh Bantuan Konfirmasi?</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Hubungi admin kami jika Anda mengalami kendala atau ingin mengonfirmasi pendaftaran melalui:</p>
                                        <ul class="list-disc pl-5 mt-1 space-y-1">
                                            <li>WhatsApp: <strong>089530123608</strong></li>
                                            <li>Email: <strong>akhyarmualif422006@gmail.com</strong></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4 pt-4 border-t border-gray-200">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Ajukan Pendaftaran Toko
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
