<!-- resources/views/livewire/user-music.blade.php -->

<div class="container">
    <style>
        /* (Seu CSS aqui) */
    </style>

    <div class="main-audio">
        <audio controls></audio>
        <div class="title"></div>
    </div>
    <div class="audio-playlist">
        <div class="audios">
            @foreach($musics as $index => $music)
                <div class="audio" data-id="{{ $music->id }}">
                    <i class="fas fa-play"></i>
                    <p>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}. </p>
                    <h3 class="title">{{ $music->title }}</h3>
                    <p class="time">{{ gmdate('i:s', $music->duration) }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const main_audio = document.querySelector('.main-audio audio');
            const main_audio_title = document.querySelector('.main-audio .title');
            const audio_playlist = document.querySelector('.audio-playlist .audios');

            let audio_data = @json($musics);

            audio_data.forEach((audio, i) => {
                let audio_element = `
                    <div class="audio" data-id="${audio.id}">
                        <img src="{{ asset('images/play.svg') }}" alt="">
                        <p>${i + 1 > 9 ? i + 1 : '0' + (i + 1)}. </p>
                        <h3 class="title">${audio.title}</h3>
                        <p class="time">${new Date(audio.duration * 1000).toISOString().substr(14, 5)}</p>
                    </div>
                `;
                audio_playlist.innerHTML += audio_element;
            });

            let audios = document.querySelectorAll('.audio');
            if (audios.length > 0) {
                audios[0].classList.add('active');
                audios[0].querySelector('img').src = '{{ asset('images/pause.svg') }}';

                let match_audio = audio_data.find(audio => audio.id == audios[0].dataset.id);
                main_audio.src = '{{ asset('storage') }}/' + match_audio.file_url;
                main_audio_title.innerHTML = match_audio.title;
            }

            audios.forEach(selected_audio => {
                selected_audio.onclick = () => {
                    for (let all_audios of audios) {
                        all_audios.classList.remove('active');
                        all_audios.querySelector('img').src = '{{ asset('images/play.svg') }}';
                    }

                    selected_audio.classList.add('active');
                    selected_audio.querySelector('img').src = '{{ asset('images/pause.svg') }}';

                    let match_audio = audio_data.find(audio => audio.id == selected_audio.dataset.id);
                    main_audio.src = '{{ asset('storage') }}/' + match_audio.file_url;
                    main_audio_title.innerHTML = match_audio.title;
                    main_audio.play();
                }
            });
        });
    </script>
</div>
