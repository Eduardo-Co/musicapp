<div class="bg-gray-100 text-gray-800 min-h-screen p-10">
    
    <div class="mb-4 flex items-center space-x-4">
        <input 
            type="text" 
            placeholder="Search for music name..." 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline focus:ring-2 focus:ring-blue-500"
            wire:model.debounce.300ms="searchTerm"  
        />
    </button>
    </div>

    <!-- Header -->
    <div class="flex text-gray-600 bg-gray-200 border-b border-gray-300 mb-4">
        <div class="p-2 w-8 flex-shrink-0"></div>
        <div class="p-2 w-8 flex-shrink-0"></div>
        <div class="p-2 w-full font-medium">Title</div>
        <div class="p-2 w-full font-medium">Artist</div>
        <div class="p-2 w-full font-medium">Album</div>
        <div class="p-2 w-12 flex-shrink-0 text-right font-medium">
            <i class="fas fa-clock"></i>
        </div>
    </div>
    <!-- lista -->
    @foreach($musics as $music)
        <div class="flex items-center border-b border-gray-300 hover:bg-gray-200 {{ $currentMusicId === $music->id ? 'bg-yellow-100' : '' }}" wire:click="play({{ $music->id }})">
            <div class="p-3 w-8 flex-shrink-0 text-gray-600">
                <button wire:click.stop="play({{ $music->id }})" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-play"></i>
                </button>
            </div>
            <div class="p-3 w-8 flex-shrink-0 text-gray-600">
                <button wire:click.stop="toggleFavorite({{ $music->id }})" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
            <div class="p-3 w-full">{{ $music->title }}</div>
            <div class="p-3 w-full">
                @foreach ($music->album->artistas as $artista)
              {{$artista->nome}}{{ $loop->last ? '' : ' | ' }}
                @endforeach
            </div>
            <div class="p-3 w-full">{{ $music->album->name ?? 'Unknown' }}</div>
            <div class="p-3  flex-shrink-0 text-right">{{ $music->duration }}</div>
        </div>
    @endforeach

    <div class="fixed bottom-0 bg-white shadow-md rounded-t-lg overflow-hidden p-4" style="width: calc(100% - 120px); margin-right: 100px; z-index: 50;">
         <div class="flex flex-col sm:flex-row items-center relative">
            
            @if($isPlaying && $currentMusic)
                <div class="flex-shrink-0">
                    <img id="music-image" src="{{asset('storage/' . $currentMusic->album->foto_url)}}" alt="Album Art" class="w-16 h-16 rounded-full object-cover rotate-animation">
                </div>
                <div class="mr-5">
                    <h3 id="music-title" class="text-lg font-semibold text-gray-800 mb-1 ml-1">Current Music: {{$currentMusic->title}}</h3>
                    <p id="music-artist" class="text-gray-600">Album: {{$currentMusic->album->name}}</p>
                </div>
            @endif
            <div class="flex items-center space-x-4">
                <button class="focus:outline-none" wire:click="previous">
                    <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="19 20 9 12 19 4 19 20"></polygon>
                        <line x1="5" y1="19" x2="5" y2="5"></line>
                    </svg>
                </button>
    
                <button class="rounded-full w-12 h-12 flex items-center justify-center ring-1 ring-red-400 focus:outline-none" wire:click="togglePlayPause">
                    <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        @if($isPlaying)
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        @else
                            <polygon points="5 4 15 12 5 20 5 4"></polygon>
                            <line x1="19" y1="5" x2="19" y2="19"></line>
                        @endif
                    </svg>
                </button>
                
                <button class="focus:outline-none" wire:click="next">
                    <svg class="w-6 h-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="5 4 15 12 5 20 5 4"></polygon>
                        <line x1="19" y1="5" x2="19" y2="19"></line>
                    </svg>
                </button>
            </div>
            
            <div class="relative flex-grow mx-4 mt-2 sm:mt-0">
                <div class="bg-gray-300 h-2 w-full rounded-lg cursor-pointer" id="progress-bar">
                    <div class="bg-red-500 h-2 rounded-lg absolute top-0" id="progress-fill" style="width: 0%;"></div>
                </div>
            </div>
            
            <div class="flex justify-end w-full sm:w-auto mt-2 sm:mt-0">
                <span class="text-xs text-gray-700 uppercase font-medium pl-2" id="time-display">
                    00:00/00:00
                </span>
            </div>
            
            <div class="flex items-center ml-4 mt-2 sm:mt-0">
                <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="1" class="w-24" />
                <button class="ml-2 p-1" id="volume-button">
                    <i class="fas fa-volume-up text-red-500"></i>
                </button>
            </div>
        </div>
    </div>
    
    <script>
          document.addEventListener('livewire:load', function () {
            let audio = new Audio();
            let progressBar = document.getElementById('progress-bar');
            let progressFill = document.getElementById('progress-fill');
            let timeDisplay = document.getElementById('time-display');
            let volumeSlider = document.getElementById('volume-slider');
            let currentMusicUrl = null;

            function updateProgress() {
                if (audio.duration) {
                    let progress = (audio.currentTime / audio.duration) * 100;
                    progressFill.style.width = `${progress}%`;
                    let currentMinutes = Math.floor(audio.currentTime / 60);
                    let currentSeconds = Math.floor(audio.currentTime % 60);
                    let durationMinutes = Math.floor(audio.duration / 60);
                    let durationSeconds = Math.floor(audio.duration % 60);
                    timeDisplay.textContent = `${String(currentMinutes).padStart(2, '0')}:${String(currentSeconds).padStart(2, '0')}/${String(durationMinutes).padStart(2, '0')}:${String(durationSeconds).padStart(2, '0')}`;
                }
            }

            function updateVolume() {
                audio.volume = volumeSlider.value;
                let volumeIcon = document.getElementById('volume-button').querySelector('i');
                if (audio.volume === 0) {
                    volumeIcon.classList.remove('fa-volume-up', 'fa-volume-down');
                    volumeIcon.classList.add('fa-volume-mute');
                } else if (audio.volume <= 0.5) {
                    volumeIcon.classList.remove('fa-volume-up', 'fa-volume-mute');
                    volumeIcon.classList.add('fa-volume-down');
                } else {
                    volumeIcon.classList.remove('fa-volume-down', 'fa-volume-mute');
                    volumeIcon.classList.add('fa-volume-up');
                }
            }

            Livewire.on('playMusic', (url) => {
                if (currentMusicUrl === url) {
                    audio.play();
                } else {
                    localStorage.setItem('currentTime', audio.currentTime); 
                    audio.src = url;
                    audio.currentTime = localStorage.getItem('currentTime') || 0;
                    audio.play();
                }
                currentMusicUrl = url;
                setInterval(updateProgress, 1000); 
            });

            Livewire.on('pauseMusic', () => {
                audio.pause();
                localStorage.setItem('currentTime', audio.currentTime); 
            });

            Livewire.on('updatePlayPause', (isPlaying) => {
                if (isPlaying) {
                    audio.play();
                } else {
                    audio.pause();
                    localStorage.setItem('currentTime', audio.currentTime); 
                }
            });

            progressBar.addEventListener('click', (event) => {
                const rect = progressBar.getBoundingClientRect();
                const offsetX = event.clientX - rect.left;
                const totalWidth = rect.width;
                const percentage = offsetX / totalWidth;
                audio.currentTime = percentage * audio.duration;
                updateProgress();
            });

            volumeSlider.addEventListener('input', updateVolume);

            Livewire.on('componentDestroyed', () => {
                stopUpdating();
            });
        });
    </script>   
    <style>
        .rotate-animation {
            animation: rotate 2s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</div>
