<div class="container mx-auto p-6">
    @if(session()->has('message') || session()->has('message-deleted'))
        <div id="toastrMsg" class="mb-4 p-4 rounded-lg @if(session()->has('message')) bg-green-100 border-green-300 @elseif(session()->has('message-deleted')) bg-red-100 border-red-300 @endif">
            @if(session()->has('message'))
                <span class="text-green-600 inline-flex items-center">
                    <strong>{{ session('message') }}</strong>
                    <button onclick="closeToastrMsg()" class="ml-4 text-green-600 hover:text-green-900">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </span>
            @endif
            
            @if(session()->has('message-deleted'))
                <span class="text-red-600 inline-flex items-center">
                    <strong>{{ session('message-deleted') }}</strong>
                    <button onclick="closeToastrMsg()" class="ml-4 text-red-600 hover:text-red-900">
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
                    <h3 class="text-lg font-semibold">{{ $isCreating ? 'Create Music' : 'Edit Music' }}</h3>
                </div>
                <form wire:submit.prevent="save" enctype="multipart/form-data">
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Formulário -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input 
                                    type="text" 
                                    id="title" 
                                    wire:model="title" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('title') <span class="text-red-50'z0">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
                                <select 
                                    id="genre" 
                                    wire:model="genre" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">Select Genre</option>
                                    <option value="Rock">Rock</option>
                                    <option value="Pop">Pop</option>
                                    <option value="Hip Hop">Hip Hop</option>
                                    <option value="R&B">R&B</option>
                                    <option value="Country">Country</option>
                                    <option value="Jazz">Jazz</option>
                                    <option value="Reggae">Reggae</option>
                                    <option value="Electronic">Electronic</option>
                                    <option value="Classical">Classical</option>
                                </select>
                                @error('genre') <span class="text-red-500">{{ $message }}</span> @enderror
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
                                <label for="duration" class="block text-sm font-medium text-gray-700">Duration (seconds)</label>
                                <input 
                                    type="number" 
                                    id="duration" 
                                    wire:model="duration" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('duration') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select 
                                    id="status" 
                                    wire:model="status" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="actived">Ativo</option>
                                    <option value="inactived">Inativo</option>
                                </select>
                                @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="file_url" class="block text-sm font-medium text-gray-700">Music File</label>
                                <input 
                                    type="file" 
                                    id="file_url" 
                                    wire:model="file_url" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('file_url') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="album_search" class="block text-sm font-medium text-gray-700">Search Album</label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        id="album_search" 
                                        wire:model.debounce.300ms="searchAlbum" 
                                        placeholder="Search for Albuns..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    @error('album') <span class="text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label for="album" class="block text-sm font-medium text-gray-700">Album</label>
                                <select 
                                    id="album" 
                                    wire:model="album_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">Select Album</option>
                                    @foreach($albuns as $album)
                                        <option value="{{ $album->id }}">{{ $album->name }}</option>
                                    @endforeach
                                </select>
                                @error('album') <span class="text-red-500">{{ $message }}</span> @enderror
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
                                {{ $isCreating ? 'Create Music' : 'Update Music' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showDeleteModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" wire:click.self="closeDeleteModal">
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md" @click.stop>
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Confirm Deletion</h3>
                </div>
                <form wire:submit.prevent="delete({{ $musicToDelete }})">
                    <div class="p-4">
                        <p class="text-gray-600">Are you sure you want to delete this music?</p>
                        <div class="mt-4 flex justify-end space-x-2">
                            <button 
                                type="button" 
                                wire:click="closeDeleteModal" 
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div x-data="deleteHandler" x-init="init()" x-show="isDeleting" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <p>Deletando...</p>
        </div>
    </div>

    <!-- visualizar -->
    @if($viewingMusic)
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
                                value="{{ $viewingMusic->title }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Genre</label>
                            <input 
                                type="text" 
                                value="{{ $viewingMusic->genre }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Release Date</label>
                            <input 
                                type="text" 
                                value="{{ $viewingMusic->release_date->format('Y-m-d') }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duration (seconds)</label>
                            <input 
                                type="text" 
                                value="{{ $viewingMusic->duration }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <input 
                                type="text" 
                                value="{{ $viewingMusic->status === 'actived' ? 'Active' : 'Inactive' }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File URL</label>
                            <input 
                                type="text" 
                                value="{{ $viewingMusic->file_url }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                    </div>
                    <div class="my-1">
                        <label class="block text-sm font-medium text-gray-700">Album</label>
                        <input 
                            type="text" 
                            value="{{ $viewingMusic->album->name}}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                            disabled
                        >
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @unless($isEditing || $isCreating)
        <div class="mb-4 flex items-center space-x-4">
            <input 
                type="text" 
                placeholder="Search for title..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline focus:ring-2 focus:ring-blue-500"
                wire:model.debounce.300ms="searchTerm"
            />
            <button 
                wire:click="create" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                style="width: 150px;"
                wire:loading.attr="disabled"
            >
                Add Music
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
                        <th class="whitespace-nowrap px-6 py-3 text-left">Genre</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Release Date</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Duration</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">File</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($musics as $music)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="whitespace-nowrap px-6 py-4 text-gray-800">{{ $music->title }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $music->genre }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $music->release_date->format('Y-m-d') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $music->duration }} seconds</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                @if($music->file_url)
                                    <audio controls>
                                        <source src="{{ asset('storage/' . $music->file_url) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @else
                                    <span class="text-gray-500">No File</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 flex space-x-2">
                                <button 
                                    wire:click="edit({{ $music->id }})" 
                                    class="bg-blue-600 text-white px-3 py-1 rounded-lg shadow hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    wire:loading.attr="disabled"
                                >
                                    Edit
                                </button>
                                <button 
                                    wire:click="confirmDelete({{ $music->id }})" 
                                    class="bg-red-600 text-white px-3 py-1 rounded-lg shadow hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    wire:loading.attr="disabled"
                                >
                                    Delete
                                </button>
                                <button 
                                    wire:click="view({{ $music->id }})" 
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
                @if ($musics->hasPages())
                    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
                        @if ($musics->onFirstPage())
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
                                @if ($page == $musics->currentPage())
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-white bg-blue-600">{{ $page }}</span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">{{ $page }}</button>
                                @endif
                            @endfor

                            @if ($end < $musics->lastPage())
                                @if ($end < $musics->lastPage() - 1)
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white">...</span>
                                @endif
                                <button wire:click="gotoPage({{ $musics->lastPage() }})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">{{ $musics->lastPage() }}</button>
                            @endif
                        </div>

                        @if ($musics->hasMorePages())
                            <button wire:click="nextPage" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">Next</button>
                        @else
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-500 bg-white cursor-not-allowed">Next</span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>
        <script src="https://unpkg.com/alpinejs" defer></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('deleteHandler', () => ({
                    isDeleting: false,
                    init() {
                        Livewire.on('delete-start', () => {
                            this.isDeleting = true;
                        });

                        Livewire.on('delete-finish', () => {
                            this.isDeleting = false;
                            alert('Música deletada com sucesso!');
                        });

                        Livewire.on('delete-error', () => {
                            this.isDeleting = false;
                            alert('Erro ao deletar a música!');
                        });
                    }
                }));
            });
        function closeToastrMsg() {
            const toastrMsg = document.getElementById('toastrMsg');
            if (toastrMsg) {
                toastrMsg.style.display = 'none';
            }
        }
    </script>   
</div>
