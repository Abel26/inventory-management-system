<x-app-layout>
    <x-slot name="title">Edit Gedung</x-slot>
    
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Edit Gedung</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Perbarui informasi gedung di bawah.
                </p>
            </div>

            <div class="mt-8">
                <form class="space-y-6" action="{{ route('master-data.gedungs.update', $gedung->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <label for="gedung_id" class="block text-sm font-medium text-gray-700 mb-1.5">Kode Gedung</label>
                            <div class="mt-1">
                                <input type="text" id="gedung_id" name="gedung_id" required
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-ebara-500 focus:ring-ebara-500 sm:text-sm py-2.5"
                                    placeholder="Contoh: GDG-001"
                                    value="{{ old('gedung_id', $gedung->gedung_id) }}">
                                @error('gedung_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Gedung</label>
                            <div class="mt-1">
                                <input type="text" id="nama" name="nama" required
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-ebara-500 focus:ring-ebara-500 sm:text-sm py-2.5"
                                    placeholder="Contoh: Gedung A"
                                    value="{{ old('nama', $gedung->nama) }}">
                                @error('nama')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a href="{{ route('master-data.gedungs.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2.5 px-4 rounded-xl transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl transition">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>