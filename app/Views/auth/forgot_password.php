<!DOCTYPE html>
<html lang="te">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NToday | Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-gradient-custom {
            background: radial-gradient(circle at top left, #1e3a8a, #0f172a),
                        radial-gradient(circle at bottom right, #991b1b, #0f172a);
            background-blend-mode: screen;
            background-color: #0f172a;
        }
    </style>
</head>
<body class="bg-gradient-custom flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white/95 backdrop-blur-md p-8 rounded-[2.5rem] shadow-2xl transition-all">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">పాస్‌వర్డ్ రీసెట్</h2>
            <p class="text-gray-500 text-xs font-bold mt-2 uppercase tracking-widest">మీ ఇమెయిల్ అడ్రస్ ఇవ్వండి</p>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="bg-green-500 text-white p-3 rounded-xl mb-4 text-xs font-bold"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-red-500 text-white p-3 rounded-xl mb-4 text-xs font-bold"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('login/sendResetLink') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="group mb-6">
                <label class="text-[10px] font-bold text-gray-400 uppercase ml-4 mb-1.5 block tracking-widest">Official Email</label>
                <input type="email" name="email" placeholder="name@ntoday.in" 
                    class="w-full px-6 py-4 bg-gray-100 border-2 border-transparent rounded-2xl focus:outline-none focus:bg-white focus:border-blue-500 transition-all font-semibold" required>
            </div>

            <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-black text-white font-black py-4 rounded-2xl shadow-xl transition-all uppercase tracking-widest text-xs">
                రీసెట్ లింక్ పంపించు
            </button>

            <div class="mt-8 text-center">
                <a href="<?= base_url('login') ?>" class="text-xs font-bold text-gray-400 hover:text-red-500 transition-colors">
                    ← తిరిగి లాగిన్ పేజీకి
                </a>
            </div>
        </form>
    </div>
</body>
</html>