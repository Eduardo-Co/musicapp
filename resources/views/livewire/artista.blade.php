<div class="container mx-auto p-6">
    @if($isEditing || $isCreating)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-40" wire:click.self="resetInputFields">
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-lg" @click.stop>
                <!-- Botão de Fechar -->
                <button wire:click="resetInputFields" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900" wire:loading.attr="disabled">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">{{ $isCreating ? 'Create Artist' : 'Edit Artist' }}</h3>
                </div>
                <form wire:submit.prevent="save" enctype="multipart/form-data">
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Formulário -->
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
                                <input 
                                    type="text" 
                                    id="nome" 
                                    wire:model="nome" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('nome') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="genero" class="block text-sm font-medium text-gray-700">Gênero Musical</label>
                                <select 
                                    id="genero" 
                                    wire:model="genero" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">Selecione um gênero</option>
                                    @foreach($generos as $genero)
                                        <option value="{{ $genero }}">{{ $genero }}</option>
                                    @endforeach
                                </select>
                                @error('genero') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="foto_url" class="block text-sm font-medium text-gray-700">Foto</label>
                                <input 
                                    type="file" 
                                    id="foto_url" 
                                    wire:model="foto_url" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('foto_url') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border-t border-gray-200 flex justify-end">
                        <button 
                            type="submit" 
                            class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                            wire:loading.attr="disabled"
                            wire:loading.class="bg-gray-400"
                        >
                            <span wire:loading.block>
                                {{ $isCreating ? 'Create Artist' : 'Update Artist' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @unless($isEditing || $isCreating)
        <div class="mb-4 flex items-center space-x-4">
            <input 
                type="text" 
                placeholder="Search for name..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline focus:ring-2 focus:ring-blue-500"
                wire:model.debounce.300ms="searchTerm"  
            />
            <button 
                wire:click="create" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                style="width: 150px;"
                wire:loading.attr="disabled"
            >
                Add Artist
            </button>
        </div>
    @endunless

    @if (session()->has('message'))
        <div class="mb-4 text-green-600">
            {{ session('message') }}
        </div>
    @endif

    <div class="rounded-lg border border-gray-300 shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300 bg-white text-sm">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                    <tr>             
                        <th class="whitespace-nowrap px-6 py-3 text-left">Nome</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Gênero Musical</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Foto</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($artistas as $artista)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="whitespace-nowrap px-6 py-4 text-gray-800">{{ $artista->nome }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $artista->genero }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                @if($artista->foto_url)
                                    <img src="{{ asset('storage/' . $artista->foto_url) }}" alt="{{ $artista->nome }}" class="w-20 h-20 rounded-lg object-cover">
                                @else
                                    <span class="text-gray-500">No Image</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 flex space-x-2">
                                <button 
                                    wire:click="edit({{ $artista->id }})" 
                                    class="bg-blue-600 text-white px-3 py-1 rounded-lg shadow hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    wire:loading.attr="disabled"
                                >
                                    Edit
                                </button>
                                <button 
                                    wire:click="delete({{ $artista->id }})" 
                                    class="bg-red-600 text-white px-3 py-1 rounded-lg shadow hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    wire:loading.attr="disabled"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 px-4 py-2 border-t border-gray-300 bg-gray-50">
            @if ($artistas->hasPages())
                <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
                    @if ($artistas->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-500 bg-white cursor-not-allowed">Previous</span>
                    @else
                        <button wire:click="previousPage" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">Previous</button>
                    @endif

                    <div class="hidden md:-mt-px md:flex">
                        @if ($start > 1)
                            <button wire:click="gotoPage(1)" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">1</button>
                            @if ($start > 2)
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white">...</span>
                            @endif
                        @endif

                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page == $artistas->currentPage())
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-white bg-blue-600">{{ $page }}</span>
                            @else
                                <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">{{ $page }}</button>
                            @endif
                        @endfor

                        @if ($end < $artistas->lastPage())
                            @if ($end < $artistas->lastPage() - 1)
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white">...</span>
                            @endif
                            <button wire:click="gotoPage({{ $artistas->lastPage() }})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">{{ $artistas->lastPage() }}</button>
                        @endif
                    </div>

                    @if ($artistas->hasMorePages())
                        <button wire:click="nextPage" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">Next</button>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-500 bg-white cursor-not-allowed">Next</span>
                    @endif
                </nav>
            @endif
        </div>
    </div>
</div>
