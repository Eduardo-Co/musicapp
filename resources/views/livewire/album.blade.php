<div class="container mx-auto p-6">
    @if(session()->has('message') || session()->has('message-deleted'))
        <div id="toastrMsg" class="mb-4 p-4 rounded-lg @if(session()->has('message')) bg-green-100 border-green-300 @elseif(session()->has('message-deleted')) bg-red-100 border-red-300 @endif">
            @if(session()->has('message'))
                <span class="text-green-600 inline-flex items-center">
                    <strong>{{ session('message') }}</strong>
                    <button class="ml-4 text-green-600 hover:text-green-900">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
            @endif
            
            @if(session()->has('message-deleted'))
                <span class="text-red-600 inline-flex items-center">
                    <strong>{{ session('message-deleted') }}</strong>
                    <button class="ml-4 text-red-600 hover:text-red-900">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
            @endif
        </div>
    @endif


    @if($isEditing || $isCreating)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-40" wire:click.self="resetInputFields">
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-lg" @click.stop>
                <button wire:click="resetInputFields" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900" wire:loading.attr="disabled">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">{{ $isCreating ? 'Create Album' : 'Edit Album' }}</h3>
                </div>
                <form wire:submit.prevent="save" enctype="multipart/form-data">
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Formulário -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    wire:model="name" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('name') <span class="text-red-50'z0">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="release_date" class="block text-sm font-medium text-gray-700">Release Date</label>
                                <input 
                                    type="date" 
                                    id="release_date" 
                                    wire:model="release_date" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('release_date') <span class="text-red-500">{{ $message }}</span> @enderror
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
                            <div>
                                <label for="artist_search" class="block text-sm font-medium text-gray-700">Search Artist</label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        id="artist_search" 
                                        wire:model.debounce.300ms="searchArtist" 
                                        placeholder="Search for artist..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    @error('searchArtist') <span class="text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label for="artist" class="block text-sm font-medium text-gray-700">Artist</label>
                                <select 
                                    id="artist" 
                                    wire:model="artist_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">Select Artist</option>
                                    @foreach($artists as $artist)
                                        <option value="{{ $artist->id }}">{{ $artist->nome }}</option>
                                    @endforeach
                                </select>
                                @error('selectedArtists') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Selected Artists</label>
                                <div class="border p-2 bg-gray-100 rounded-lg max-h-60 overflow-y-auto">
                                    @foreach($selectedArtists as $artist)
                                        <div class="flex items-center mb-1">
                                            <span class="text-black bg-white border border-gray-300 px-2 py-1 rounded-lg mr-2">
                                                {{ $artist['nome'] }}
                                            </span>
                                            <button type="button" wire:click="removeArtist({{ $artist['id'] }})" class="text-red-500">x</button>
                                        </div>
                                    @endforeach
                                </div>                                
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
                                {{ $isCreating ? 'Create Album' : 'Update Album' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    
    <!-- visualizar -->
    @if($viewingAlbum)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-40" wire:click.self="closeView">
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-lg" @click.stop>
                <!-- Botão de Fechar -->
                <button wire:click="closeView" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900" wire:loading.attr="disabled">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">View Music</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input 
                                type="text" 
                                value="{{ $viewingAlbum->name }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Release Date</label>
                            <input 
                                type="text" 
                                value="{{ $viewingAlbum->release_date->format('Y-m-d') }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                        <div>
                            <label for="foto_url" class="block text-sm font-medium text-gray-700">Foto</label>
                            <input 
                                disabled="disabled"
                                type="file" 
                                id="foto_url" 
                                wire:model="foto_url" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            @error('foto_url') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Artists</label>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($viewingAlbum->artistas as $artist)
                                    <div class="bg-gray-100 px-4 py-2 rounded-lg shadow-sm">
                                        <span>{{ $artist->nome }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
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
                Add Album
            </button>
        </div>
    @endunless

    <!-- tabela --> 
    <div class="rounded-lg border border-gray-300 shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300 bg-white text-sm">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                    <tr>             
                        <th class="whitespace-nowrap px-6 py-3 text-left">Title</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Release Date</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Foto</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($albuns as $album)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="whitespace-nowrap px-6 py-4 text-gray-800">{{ $album->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $album->release_date->format('Y-m-d') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                @if($album->foto_url)
                                    <img src="{{ asset('storage/' . $album->foto_url) }}" alt="{{ $album->nome }}" class="w-20 h-20 rounded-lg object-cover">
                                @else
                                    <span class="text-gray-500">No Image</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 flex space-x-2">
                                <button 
                                    wire:click="edit({{ $album->id }})" 
                                    class="bg-blue-600 text-white px-3 py-1 rounded-lg shadow hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    wire:loading.attr="disabled"
                                >
                                    Edit
                                </button>
                                <button 
                                    wire:click="delete({{ $album->id }})" 
                                    class="bg-red-600 text-white px-3 py-1 rounded-lg shadow hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    wire:loading.attr="disabled"
                                >
                                    Delete
                                </button>
                                <button 
                                    wire:click="view({{ $album->id }})" 
                                    class="bg-gray-600 text-white px-3 py-1 rounded-lg shadow hover:bg-gray-900"
                                    wire:loading.attr="disabled"
                                    >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5c4.28 0 7.71 2.88 8.8 6.5-1.09 3.62-4.52 6.5-8.8 6.5s-7.71-2.88-8.8-6.5C4.29 7.38 7.72 4.5 12 4.5zM12 12a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

            <div class="mt-4 px-4 py-2 border-t border-gray-300 bg-gray-50">
                @if ($albuns->hasPages())
                    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
                        @if ($albuns->onFirstPage())
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
                                @if ($page == $albuns->currentPage())
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-white bg-blue-600">{{ $page }}</span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">{{ $page }}</button>
                                @endif
                            @endfor

                            @if ($end < $albuns->lastPage())
                                @if ($end < $albuns->lastPage() - 1)
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white">...</span>
                                @endif
                                <button wire:click="gotoPage({{ $albuns->lastPage() }})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">{{ $albuns->lastPage() }}</button>
                            @endif
                        </div>

                        @if ($albuns->hasMorePages())
                            <button wire:click="nextPage" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">Next</button>
                        @else
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-500 bg-white cursor-not-allowed">Next</span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>    
    </div>
