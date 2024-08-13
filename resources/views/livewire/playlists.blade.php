<div class="p-6">
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

    @if($showDeleteModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" wire:click.self="hideDeleteModal">
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md" @click.stop>
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Confirm Deletion</h3>
                </div>
                <form wire:submit.prevent="deletePlaylist({{ $playlistToDeleteId }})">
                    <div class="p-4">
                        <p class="text-gray-600">Are you sure you want to delete this playlist?</p>
                        <div class="mt-4 flex justify-end space-x-2">
                            <button 
                                type="button" 
                                wire:click="hideDeleteModal" 
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

    <!-- Create/Edit Playlist Modal -->
    @if ($showCreatePlaylistModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full md:w-1/2 lg:w-1/3 mx-auto">
                <h2 class="text-2xl font-semibold mb-4 text-gray-900">
                    {{ $editPlaylistId ? 'Edit Playlist' : 'Create New Playlist' }}
                </h2>
            
                <form wire:submit.prevent="{{ $editPlaylistId ? 'updatePlaylist' : 'createPlaylist' }}">
                    <div class="mb-4">
                        <label for="playlistName" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input 
                            type="text" 
                            id="playlistName"
                            wire:model.defer="playlistName"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-150 ease-in-out"
                            placeholder="Enter playlist name"
                        />
                        @error('playlistName') 
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                        @enderror
                    </div>
            
                    <div class="flex justify-end space-x-3">
                        <button 
                            type="button" 
                            wire:click="hideCreatePlaylistModal" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-green-500 text-white rounded-lg shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-150 ease-in-out">
                            {{ $editPlaylistId ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>            
        </div>
    @endif

    <!-- Search and Add Playlist Button -->
    <div class="mb-4 flex items-center space-x-4">
        <input 
            type="text" 
            placeholder="Search for playlist name..." 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline focus:ring-2 focus:ring-blue-500"
            wire:model.debounce.300ms="searchTerm"  
        />
        <button 
            wire:click="showCreatePlaylistModal" 
            class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
            style="width: 150px;"
            wire:loading.attr="disabled">

            Add Playlist 
        </button>
    </div>

    <!-- Playlists Grid -->
    <br>
    @if ($playlists->isEmpty())
        <div class="flex flex-col items-center justify-center text-center">
            <span class="text-4xl mb-2">😞</span>
            <p class="text-gray-600">Não há nenhuma playlist disponível.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($playlists as $playlist)
                <div class="relative bg-white p-6 rounded-lg shadow-lg border border-gray-200 group">
                    <h3 class="text-xl font-semibold mb-2">{{ $playlist->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ $playlist->description }}</p>

                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="space-x-4">
                            <button wire:click="viewPlaylist({{ $playlist->id }})" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                View
                            </button>
                            <button wire:click="editPlaylist({{ $playlist->id }})" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                Edit
                            </button>
                            <button wire:click="confirmDelete({{ $playlist->id }})" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $playlists->links() }}
        </div>
    @endif
</div>

<script>
    function closeToastrMsg() {
        const toastrMsg = document.getElementById('toastrMsg');
        if (toastrMsg) {
            toastrMsg.classList.add('opacity-0');
            setTimeout(() => {
                toastrMsg.remove();
            }, 300);
        }
    }
</script>
