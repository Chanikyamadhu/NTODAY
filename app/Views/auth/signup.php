<!DOCTYPE html>
<html lang="te">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporter Signup | NToday News</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: radial-gradient(circle at top left, #1e3a8a, #0f172a), radial-gradient(circle at bottom right, #991b1b, #0f172a); background-attachment: fixed; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px); border-radius: 2.5rem; border: 1px solid rgba(255,255,255,0.2); }
        .peer:checked + span { background-color: #3b82f6; color: white; border-color: #3b82f6; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5); }
        input:focus { transform: translateY(-1px); transition: all 0.2s; }
        /* Loading Spinner */
        .spinner { border: 3px solid rgba(255,255,255,0.3); border-radius: 50%; border-top: 3px solid #fff; width: 20px; height: 20px; animation: spin 1s linear infinite; display: none; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        /* Modal Styles */
        .modal { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); align-items: center; justify-content: center; padding: 20px; }
        .modal-content { background: white; padding: 20px; border-radius: 1.5rem; max-width: 500px; width: 100%; }
        .crop-container { max-height: 400px; overflow: hidden; margin-bottom: 20px; }
        img { max-width: 100%; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 md:p-8 relative overflow-x-hidden">

    <div id="cropModal" class="modal">
        <div class="modal-content">
            <h3 class="text-lg font-bold mb-4">Crop Profile Picture</h3>
            <div class="crop-container">
                <img id="imageToCrop">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-6 py-2 bg-gray-200 rounded-full font-bold">Cancel</button>
                <button type="button" id="cropButton" class="px-6 py-2 bg-blue-600 text-white rounded-full font-bold">Crop & Save</button>
            </div>
        </div>
    </div>

    <div class="absolute top-0 right-0 w-64 h-64 bg-red-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>

    <div class="max-w-2xl w-full relative z-10 py-10">
        <div class="text-center mb-10">
            <div class="inline-block p-4 bg-white/10 rounded-3xl backdrop-blur-md mb-6 border border-white/20 shadow-2xl">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" class="w-20 h-20 md:w-24 md:h-24 object-contain drop-shadow-2xl">
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter uppercase leading-none">
                N<span class="text-red-500">Today</span> <span class="text-blue-400">Reporter</span>
            </h1>
            <p class="text-slate-300 mt-4 font-medium italic">డిజిటల్ జర్నలిజం విప్లవంలో భాగస్వామ్యం అవ్వండి</p>
        </div>

        <div class="glass-card shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)] p-6 md:p-12">
            
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 shadow-sm font-bold animate-bounce">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 shadow-sm font-bold">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if(isset($validation)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-8 text-sm font-bold shadow-sm">
                    <p class="uppercase tracking-widest text-[10px] mb-1 opacity-70">Errors Found:</p>
                    <?= $validation->listErrors() ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/signup/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-8" id="signupForm">
                <?= csrf_field() ?>
                
                <input type="hidden" name="cropped_image" id="cropped_image_input">

                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                        <h2 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em]">వృత్తిపరమైన వివరాలు</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label class="text-[10px] font-black text-gray-500 uppercase ml-4 mb-1 block tracking-widest">Profile Picture</label>
                            <div class="flex items-center gap-4">
                                <div id="preview-container" class="w-16 h-16 bg-slate-200 rounded-2xl overflow-hidden border-2 border-dashed border-slate-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4v16m8-8H4" /></svg>
                                </div>
                                <input type="file" id="imageInput" accept="image/*" class="flex-1 text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" required>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase ml-4 mb-1 block tracking-widest">Display Name (స్క్రీన్ పేరు)</label>
                            <input type="text" name="display_name" placeholder="Ex: Reporter Ramu" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold text-slate-700 shadow-inner">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                        <h2 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em]">వ్యక్తిగత వివరాలు</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <input type="text" name="full_name" placeholder="ఆధార్ కార్డు ప్రకారం పూర్తి పేరు" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold text-slate-700" required>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <label class="text-[9px] font-bold text-gray-400 uppercase absolute left-5 -top-2 bg-white px-2">Date of Birth</label>
                                <input type="date" name="dob" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold text-slate-700" required>
                            </div>
                            <select name="gender" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold text-slate-700 appearance-none">
                                <option value="">లింగం (Gender)</option>
                                <option value="male">పురుషుడు (Male)</option>
                                <option value="female">స్త్రీ (Female)</option>
                                <option value="other">ఇతరులు (Other)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                        <h2 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em]">పరిధి మరియు విభాగం</h2>
                    </div>
                    <div class="space-y-4">
                        <input type="text" name="coverage_area" placeholder="మీరు కవర్ చేసే ప్రాంతం (Ex: ఖమ్మం టౌన్)" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold text-slate-700" required>
                        <textarea name="address" rows="2" placeholder="శాశ్వత చిరునామా (Full Address)" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold text-slate-700"></textarea>
                    </div>

                    <div class="bg-slate-50 p-6 rounded-[2rem] border-2 border-slate-100 shadow-inner">
                        <label class="text-[10px] font-black text-slate-400 uppercase mb-4 block tracking-widest text-center">మీరు వార్తలు అందించే విభాగాలు (Categories)</label>
                        <div class="flex flex-wrap justify-center gap-3">
                            <?php 
                            $cats = ['Politics', 'Crime', 'Cinema', 'Sports', 'Agriculture', 'Health', 'Education'];
                            foreach($cats as $c): ?>
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="categories[]" value="<?= $c ?>" class="hidden peer">
                                    <span class="px-5 py-2 bg-white border-2 border-slate-200 rounded-full text-xs font-extrabold text-slate-500 tab-transition hover:border-blue-300 block"><?= $c ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="email" name="email" placeholder="Email Address" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold text-slate-700" required>
                        <input type="password" name="password" placeholder="Create Password" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-blue-500 outline-none font-bold text-slate-700" required>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="w-full bg-slate-900 hover:bg-blue-800 text-white font-black py-6 rounded-[2rem] shadow-2xl transition-all transform active:scale-95 uppercase tracking-[0.3em] text-sm mt-8 group flex justify-center items-center gap-3">
                    <span id="btnText">రిజిస్ట్రేషన్ పూర్తి చేయండి</span>
                    <div id="loader" class="spinner"></div>
                    <svg id="btnIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </button>
            </form>

            <div class="mt-10 text-center">
                <p class="text-slate-400 font-bold text-sm">
                    ఇప్పటికే సభ్యత్వం ఉందా? 
                    <a href="<?= base_url('/login') ?>" class="text-red-600 hover:text-red-700 ml-1 underline underline-offset-8 decoration-2 transition-all">లాగిన్ అవ్వండి</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const imageInput = document.getElementById('imageInput');
        const imageToCrop = document.getElementById('imageToCrop');
        const cropModal = document.getElementById('cropModal');
        const croppedImageInput = document.getElementById('cropped_image_input');
        const previewContainer = document.getElementById('preview-container');

        // ఫైల్ సెలెక్ట్ చేసినప్పుడు మాడల్ ఓపెన్ అవుతుంది
        imageInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imageToCrop.src = event.target.result;
                    cropModal.style.display = 'flex';
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1, // Square crop
                        viewMode: 1,
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });

        // Crop చేసిన ఇమేజ్‌ను సేవ్ చేయడం
        document.getElementById('cropButton').addEventListener('click', function() {
            const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
            const base64Image = canvas.toDataURL('image/jpeg');
            
            // Preview చూపించడం
            previewContainer.innerHTML = `<img src="${base64Image}" class="w-full h-full object-cover">`;
            previewContainer.classList.remove('bg-slate-200');
            
            // Hidden input లో స్టోర్ చేయడం (సర్వర్‌కు పంపడానికి)
            croppedImageInput.value = base64Image;
            
            closeModal();
        });

        function closeModal() {
            cropModal.style.display = 'none';
        }

        // Form Submit Loading State
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const loader = document.getElementById('loader');

            btn.disabled = true;
            btnText.innerText = "ప్రాసెస్ అవుతోంది...";
            btnIcon.style.display = 'none';
            loader.style.display = 'block';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    </script>
</body>
</html>