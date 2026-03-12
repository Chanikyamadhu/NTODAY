<!DOCTYPE html>
<html lang="te">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NToday | Secure Access Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .bg-gradient-custom {
            background: radial-gradient(circle at top left, #1e3a8a, #0f172a),
                        radial-gradient(circle at bottom right, #991b1b, #0f172a);
            background-blend-mode: screen;
            background-color: #0f172a;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .active-tab {
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%) !important;
            color: white !important;
            box-shadow: 0 10px 25px -5px rgba(239, 68, 68, 0.5);
            transform: translateY(-2px);
        }
        .tab-transition { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        @keyframes float {
            0% { transform: translate(0, 0); }
            50% { transform: translate(15px, 20px); }
            100% { transform: translate(0, 0); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .input-focus-blue:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .input-focus-red:focus { border-color: #ef4444; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1); }
    </style>
</head>
<body class="bg-gradient-custom flex items-center justify-center min-h-screen p-4 relative">
    
    <div class="absolute top-[-5%] left-[-5%] w-96 h-96 bg-blue-600 rounded-full mix-blend-screen filter blur-[120px] opacity-20 animate-float"></div>
    <div class="absolute bottom-[-5%] right-[-5%] w-96 h-96 bg-red-600 rounded-full mix-blend-screen filter blur-[120px] opacity-20 animate-float" style="animation-delay: 2s;"></div>

    <div class="max-w-md w-full relative z-10 transition-all duration-500">
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-black text-white tracking-tighter uppercase">
                N<span id="brand-accent" class="text-red-500 transition-colors duration-500">TODAY</span> <span class="text-white">NEWS</span>
            </h1>
            <p class="text-blue-200 mt-1 font-semibold tracking-[0.2em] text-[10px] md:text-xs uppercase opacity-80">Digital Management Portal</p>
        </div>

        <div class="glass-effect p-2 rounded-[2.8rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.6)]">
            <div class="flex p-1.5 bg-gray-200/50 rounded-[2.2rem] mb-6">
                <button onclick="setRole('admin', this)" class="role-btn active-tab flex-1 py-3 text-[10px] md:text-xs font-bold rounded-[1.8rem] tab-transition uppercase tracking-wider">🛡️ Admin</button>
                <button onclick="setRole('editor', this)" class="role-btn flex-1 py-3 text-[10px] md:text-xs font-bold rounded-[1.8rem] tab-transition text-gray-600 uppercase tracking-wider">📝 Editor</button>
                <button onclick="setRole('reporter', this)" class="role-btn flex-1 py-3 text-[10px] md:text-xs font-black rounded-[1.8rem] tab-transition text-gray-600 uppercase tracking-wider">🎤 Reporter</button>
            </div>

            <div class="px-4 md:px-8 pb-8 pt-2">
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="bg-red-500 text-white p-3 rounded-xl mb-4 text-xs font-bold animate-pulse">
                        ⚠️ <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/login/auth') ?>" method="POST" id="loginForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="role" id="selected_role" value="admin">

                    <div class="space-y-5">
                        <div class="group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-4 mb-1.5 block tracking-widest group-focus-within:text-blue-500">Official Email</label>
                            <input type="email" name="email" placeholder="name@ntoday.in" class="w-full pl-6 pr-6 py-4 bg-gray-50/50 border-2 border-transparent rounded-2xl focus:outline-none focus:bg-white input-focus-blue transition-all font-semibold" required>
                        </div>
                        
                        <div class="group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase ml-4 mb-1.5 block tracking-widest group-focus-within:text-red-500">Secret Password</label>
                            <input type="password" name="password" id="password_input" placeholder="••••••••" class="w-full pl-6 pr-12 py-4 bg-gray-50/50 border-2 border-transparent rounded-2xl focus:outline-none focus:bg-white input-focus-red transition-all font-semibold" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-6 px-2">
                        <label class="flex items-center text-xs font-bold text-gray-500 cursor-pointer">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600 mr-2"> గుర్తుంచుకో
                        </label>
                        <a href="<?= base_url('/login/forgotPassword') ?>" class="text-xs font-bold text-red-500 hover:text-red-700">Forgot Password?</a>
                    </div>

                    <button type="submit" id="login_btn" class="w-full mt-8 bg-[#1e3a8a] text-white font-black py-4 rounded-2xl shadow-xl transition-all flex items-center justify-center gap-3 uppercase tracking-widest text-xs">
                        <span id="btn_text">Admin Login</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setRole(role, btn) {
            document.getElementById('selected_role').value = role;
            document.querySelectorAll('.role-btn').forEach(el => el.classList.remove('active-tab'));
            btn.classList.add('active-tab');
            const btnText = document.getElementById('btn_text');
            btnText.innerText = role.charAt(0).toUpperCase() + role.slice(1) + ' Login';
        }
    </script>
</body>
</html>