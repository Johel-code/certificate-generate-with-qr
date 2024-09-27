<div>
    @if (session()->has('message'))
        <div class="bg-green-500 text-white p-2 mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="import">
        <input type="file" wire:model="file" class="mb-4">

        @error('file') <span class="text-red-500">{{ $message }}</span> @enderror

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Subir Archivo
        </button>
    </form>
</div>
