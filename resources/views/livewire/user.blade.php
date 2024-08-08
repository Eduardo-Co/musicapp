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
        <!-- Modal de Edição/Criação -->
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" wire:click.self="resetInputFields">
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-lg" @click.stop>
                <!-- Botão de Fechar -->
                <button 
                    wire:click="resetInputFields" 
                    class="absolute top-4 right-4 text-gray-600 hover:text-gray-900"
                    wire:loading.attr="disabled"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">{{ $isCreating ? 'Create User' : 'Edit User' }}</h3>
                </div>
                <form wire:submit.prevent="save">
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
                                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    wire:model="email" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            @if ($isCreating)
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <input 
                                        type="password" 
                                        id="password" 
                                        wire:model="password" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    @error('password') <span class="text-red-500">{{ $message }}</span> @enderror
                                </div>
                            @endif
                            @if($profile != "administrator")
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select 
                                    id="status" 
                                    wire:model="status" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">Select Status</option>
                                    <option value="actived">Actived</option>
                                    <option value="inactived">Inactived</option>
                                    <option value="pre_registred">Pre Registered</option>
                                </select>
                                @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gender</label>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center space-x-2">
                                        <input 
                                            type="radio" 
                                            name="gender" 
                                            value="male" 
                                            wire:model="gender" 
                                            class="form-radio text-blue-600 focus:ring-blue-500"
                                        />
                                        <span class="text-gray-700">Male</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input 
                                            type="radio" 
                                            name="gender" 
                                            value="female" 
                                            wire:model="gender" 
                                            class="form-radio text-blue-600 focus:ring-blue-500"
                                            wire:loading.attr="disabled"
                                        />
                                        <span class="text-gray-700">Female</span>
                                    </label>
                                </div>
                                @error('gender') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>   
                            <div>
                                <label for="profile" class="block text-sm font-medium text-gray-700">Profile</label>
                                <input 
                                    disabled="disabled"
                                    type="text" 
                                    id="profile" 
                                    wire:model="profile" 
                                    value="{{ $isCreating ? 'user' : $profile }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                @error('profile') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border-t border-gray-200 flex justify-end">
                        <button 
                            type="submit" 
                            class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                            wire:loading.attr="disabled"
                        >
                            {{ $isCreating ? 'Create User' : 'Update User' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($viewingUser)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-40" wire:click.self="closeView">
            <div class="relative bg-white rounded-lg shadow-lg w-full max-w-lg" @click.stop>
                <button wire:click="closeView" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900" wire:loading.attr="disabled">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">View User</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input 
                                type="text" 
                                value="{{ $viewingUser->name }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input 
                                type="text" 
                                value="{{ $viewingUser->email }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender</label>
                            <input 
                                type="text" 
                                value="{{ $viewingUser->gender }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Profile</label>
                            <input 
                                type="text" 
                                value="{{ $viewingUser->profile }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <input 
                                type="text" 
                                value="{{ $viewingUser->status }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100" 
                                disabled
                            />
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
                Add User
            </button>
        </div>
    @endunless

    <div class="rounded-lg border border-gray-300 shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300 bg-white text-sm">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                    <tr>             
                        <th class="whitespace-nowrap px-6 py-3 text-left">Name</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Email</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Gender</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Profile</th>
                        <th class="whitespace-nowrap px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                            <td class="whitespace-nowrap px-6 py-4 text-gray-800">{{ $user->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $user->gender }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $user->profile }}</td>
                            <td class="whitespace-nowrap px-6 py-4 flex space-x-2">
                                <button 
                                    wire:click="edit({{ $user->id }})" 
                                    class="bg-blue-600 text-white px-3 py-1 rounded-lg shadow hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    wire:loading.attr="disabled"
                                >
                                    Edit
                                </button>
                                <button 
                                    wire:click="delete({{ $user->id }})" 
                                    class="bg-red-600 text-white px-3 py-1 rounded-lg shadow hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    wire:loading.attr="disabled"
                                >
                                    Delete
                                </button>
                                <button 
                                    wire:click="view({{ $user->id }})" 
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
            @if ($users->hasPages())
                <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
                    @if ($users->onFirstPage())
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
                            @if ($page == $users->currentPage())
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-white bg-blue-600">{{ $page }}</span>
                            @else
                                <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">{{ $page }}</button>
                            @endif
                        @endfor

                        @if ($end < $users->lastPage())
                            @if ($end < $users->lastPage() - 1)
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white">...</span>
                            @endif
                            <button wire:click="gotoPage({{ $users->lastPage() }})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">{{ $users->lastPage() }}</button>
                        @endif
                    </div>

                    @if ($users->hasMorePages())
                        <button wire:click="nextPage" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:text-gray-500 focus:outline-none" wire:loading.attr="disabled">Next</button>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-500 bg-white cursor-not-allowed">Next</span>
                    @endif
                </nav>
            @endif
        </div>
    </div>
    <script>
        function closeToastrMsg() {
            const toastrMsg = document.getElementById('toastrMsg');
            if (toastrMsg) {
                toastrMsg.style.display = 'none';
            }
        }
    </script>  
</div>
