<?= view('frontend/includes/header', ['title' => $title]) ?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-5 overflow-hidden bg-dark text-white">
                <div class="card-body p-5 text-center">
                    <h2 class="fw-bold mb-4 text-warning">
                        <i class="bi bi-broadcast me-2"></i> లైవ్ తెలుగు రేడియో
                    </h2>

                    <div class="visualizer-container mb-4 d-flex align-items-end justify-content-center gap-1">
                        <div class="v-bar"></div><div class="v-bar"></div><div class="v-bar"></div>
                        <div class="v-bar"></div><div class="v-bar"></div><div class="v-bar"></div>
                        <div class="v-bar"></div><div class="v-bar"></div><div class="v-bar"></div>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="small text-muted mb-2 ps-2">స్టేషన్‌ను ఎంచుకోండి:</label>
                        <select id="station-select" class="form-select form-select-lg bg-secondary text-white border-0 rounded-pill px-4" onchange="changeStation()">
                            <option value="https://air.pc.cdn.bitgravity.com/air/live/pbaudio113/playlist.m3u8">AIR Telugu (ఆకాశవాణి)</option>
                            <option value="https://vachana.out.airtime.pro/vachana_a">Vachana Radio (Devotional)</option>
                            <option value="https://ssl.prostreaming.it:8012/stream">Telugu One Radio</option>
                            <option value="https://stream.zeno.fm/5p7h81h4698uv">Radio Sai Global</option>
                        </select>
                    </div>

                    <div class="player-wrapper p-4 rounded-4" style="background: rgba(255,255,255,0.05);">
                        <audio id="radio-player" preload="none">
                            <source id="audio-source" src="https://air.pc.cdn.bitgravity.com/air/live/pbaudio113/playlist.m3u8" type="application/x-mpegURL">
                        </audio>
                        
                        <div class="d-flex align-items-center justify-content-center gap-4">
                            <button onclick="togglePlay()" id="play-btn" class="btn btn-warning btn-lg rounded-circle shadow-lg" style="width: 70px; height: 70px;">
                                <i class="bi bi-play-fill fs-1"></i>
                            </button>
                            <div class="volume-container d-flex align-items-center gap-2">
                                <i class="bi bi-volume-up"></i>
                                <input type="range" class="form-range" min="0" max="1" step="0.1" id="volume-control" oninput="changeVolume(this.value)">
                            </div>
                        </div>
                        <p class="mt-3 mb-0 small opacity-50" id="status-text">ప్లే బటన్ క్లిక్ చేయండి...</p>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-4 rounded-4 border-0 shadow-sm small">
                <i class="bi bi-info-circle-fill me-2"></i> 
                <strong>గమనిక:</strong> కొన్ని స్టేషన్లు ఇంటర్నెట్ వేగం లేదా బ్రాడ్‌కాస్ట్ సమయాన్ని బట్టి లోడ్ అవ్వడానికి సమయం పట్టవచ్చు.
            </div>
        </div>
    </div>
</main>

<style>
    /* Visualizer Style */
    .v-bar { width: 8px; height: 10px; background: #ffc107; border-radius: 10px; transition: 0.3s; }
    .playing .v-bar { animation: bar-dance 1s infinite alternate; }
    .v-bar:nth-child(2n) { animation-delay: 0.2s; }
    .v-bar:nth-child(3n) { animation-delay: 0.4s; }
    
    @keyframes bar-dance {
        from { height: 10px; }
        to { height: 60px; }
    }

    /* Range Input Color */
    .form-range::-webkit-slider-runnable-track { background: #555; height: 6px; border-radius: 3px; }
    .form-range::-webkit-slider-thumb { background: #ffc107; margin-top: -5px; }
</style>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<script>
    const audio = document.getElementById('radio-player');
    const playBtn = document.getElementById('play-btn');
    const visualizer = document.querySelector('.visualizer-container');
    const statusText = document.getElementById('status-text');
    let hls = new Hls();

    function changeStation() {
        const url = document.getElementById('station-select').value;
        stopAudio();
        
        if (url.includes('.m3u8')) {
            if (Hls.isSupported()) {
                hls.loadSource(url);
                hls.attachMedia(audio);
            } else if (audio.canPlayType('application/vnd.apple.mpegurl')) {
                audio.src = url;
            }
        } else {
            audio.src = url;
        }
        togglePlay();
    }

    function togglePlay() {
        if (audio.paused) {
            audio.play();
            playBtn.innerHTML = '<i class="bi bi-pause-fill fs-1"></i>';
            visualizer.classList.add('playing');
            statusText.innerText = "ప్రసారం అవుతోంది...";
        } else {
            stopAudio();
        }
    }

    function stopAudio() {
        audio.pause();
        playBtn.innerHTML = '<i class="bi bi-play-fill fs-1"></i>';
        visualizer.classList.remove('playing');
        statusText.innerText = "ఆపివేయబడింది.";
    }

    function changeVolume(val) {
        audio.volume = val;
    }

    // Initial Load
    window.onload = () => {
        const initialUrl = document.getElementById('station-select').value;
        if (Hls.isSupported()) {
            hls.loadSource(initialUrl);
            hls.attachMedia(audio);
        }
    };
</script>

<?= view('frontend/includes/footer') ?>