<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Ishonch Portali</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        .toggle-checkbox:checked { right: 0; border-color: #10B981; }
        .toggle-checkbox:checked + .toggle-label { background-color: #10B981; }
        
        /* Modal animatsiyasi */
        @keyframes modalPop {
            0% { transform: scale(0.9); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .modal-content { animation: modalPop 0.3s ease-out forwards; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen pb-10">
    
    @if(session('status_result'))
    <div id="statusModal" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="document.getElementById('statusModal').remove()"></div>
        
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm relative z-10 p-6 modal-content">
            @if(session('status_result')['found'])
                <!-- Agar topilsa -->
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-{{ session('status_result')['color'] }}-100 text-{{ session('status_result')['color'] }}-600">
                        <i class="fas fa-info-circle text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Ariza Holati</h3>
                    <p class="text-lg font-mono font-bold text-gray-600 mb-4">{{ session('status_result')['code'] }}</p>
                    
                    <div class="bg-gray-50 rounded-xl p-4 text-left space-y-3 mb-6">
                        <div>
                            <span class="text-[10px] uppercase text-gray-400 font-bold block">Status</span>
                            <span class="text-sm font-bold text-{{ session('status_result')['color'] }}-600 px-2 py-1 bg-{{ session('status_result')['color'] }}-50 rounded-lg inline-block mt-1">
                                {{ session('status_result')['status'] }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase text-gray-400 font-bold block">Yuborilgan vaqt</span>
                            <span class="text-sm font-medium text-gray-800">{{ session('status_result')['time'] }}</span>
                        </div>
                        @if(session('status_result')['note'] != 'Izoh yo\'q')
                        <div class="border-t pt-2 border-gray-200">
                            <span class="text-[10px] uppercase text-gray-400 font-bold block">Admin javobi</span>
                            <p class="text-sm text-gray-700 mt-1 italic">"{{ session('status_result')['note'] }}"</p>
                        </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Agar topilmasa -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-500">
                        <i class="fas fa-search-minus text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Topilmadi</h3>
                    <p class="text-sm text-gray-500 mb-6">{{ session('status_result')['message'] }}</p>
                </div>
            @endif
            
            <button onclick="document.getElementById('statusModal').remove()" class="w-full py-3 bg-gray-900 text-white rounded-xl font-bold">YOPISH</button>
        </div>
    </div>
    @endif

    <!-- MODAL OYNA (Muvaffaqiyatli yuborilganda) -->
    @if(session('success'))
    <div id="successModal" class="fixed inset-0 z-[100] flex items-center justify-center px-4">
        <!-- Orqa fon -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

        <!-- Modal kartochkasi -->
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm relative z-10 p-6 text-center modal-content">
            <!-- Ikonka -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>

            <h3 class="text-2xl font-bold text-gray-800 mb-2">Muvaffaqiyatli!</h3>
            <p class="text-sm text-gray-500 mb-6">Sizning murojaatingiz qabul qilindi.</p>

            <!-- ID Raqam va Copy -->
            <div class="bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl p-4 mb-6 relative group">
                <p class="text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-wider">Maxsus ID raqamingiz</p>
                
                <div class="flex items-center justify-center gap-3">
                    <span id="trackCode" class="text-2xl font-mono font-bold text-slate-800 tracking-wider select-all">{{ session('success') }}</span>
                    
                    <button onclick="copyToClipboard()" class="w-10 h-10 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-300 hover:shadow-md transition active:scale-95" title="Nusxalash">
                        <i class="far fa-copy text-lg"></i>
                    </button>
                </div>

                <!-- Tooltip -->
                <div id="copyTooltip" class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-[10px] font-bold py-1 px-3 rounded opacity-0 transition-opacity pointer-events-none">
                    Nusxalandi!
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-3 mb-6 text-left flex gap-3">
                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
                <p class="text-xs text-yellow-800">Ushbu kodni saqlab qo'ying! U orqali natijani tekshirasiz.</p>
            </div>

            <button onclick="closeModal()" class="w-full py-3.5 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition shadow-lg active:scale-95">
                TUSHUNDIM
            </button>
        </div>
    </div>
    @endif

    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50 px-4 py-3 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                <i class="fas fa-shield-alt text-xl"></i>
            </div>
            <div>
                <h1 class="font-bold text-gray-800 text-lg leading-tight">ISHONCH</h1>
                <p class="text-[10px] text-gray-500 font-bold uppercase">Anonim Portal</p>
            </div>
        </div>
    </header>

    <!-- Xatoliklar -->
    <div class="px-4 mt-4">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm flex items-center gap-2">
                <i class="fas fa-times-circle"></i>
                <span class="text-sm font-bold">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm mt-2 shadow-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Asosiy Kontent -->
    <div class="px-4 py-6 max-w-lg mx-auto">
        
        <!-- Banner -->
        <div class="bg-slate-800 rounded-2xl p-5 text-white mb-6 shadow-lg relative overflow-hidden">
            <div class="absolute right-[-10px] bottom-[-10px] opacity-10 text-8xl">
                <i class="fas fa-balance-scale"></i>
            </div>
            <h2 class="font-bold text-lg mb-2 relative z-10">Korrupsiya haqida xabar bering!</h2>
            <p class="text-xs text-gray-300 relative z-10">Sizning shaxsingiz 100% sir saqlanadi.</p>
        </div>

        <!-- Murojaat holatini tekshirish -->
         <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <label class="font-bold text-gray-700 text-xs mb-2 block">Murojaat holatini tekshirish</label>
            <form action="{{ route('appeal.check') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="track_code" placeholder="#ADL-..." required class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 uppercase">
                <button type="submit" class="bg-blue-600 text-white px-4 rounded-xl shadow-md active:scale-95 transition hover:bg-blue-700">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Asosiy Forma -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-gray-800">Yangi Murojaat</h2>
                
                <!-- Anonim Toggle -->
                <div class="flex items-center">
                    <span class="text-[10px] font-bold text-gray-400 mr-2 uppercase">Anonim</span>
                    <div class="relative inline-block w-10 align-middle select-none">
                        <input type="checkbox" id="toggle" checked class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 border-gray-300 appearance-none cursor-pointer transition-all duration-300 left-0"/>
                        <label for="toggle" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer transition-colors duration-300"></label>
                    </div>
                </div>
            </div>

            <!-- Status Alert -->
            <div id="status-alert" class="bg-emerald-50 border border-emerald-100 rounded-xl p-3 mb-6 flex gap-3 transition-all duration-300">
                <i class="fas fa-shield-alt text-emerald-500 mt-1"></i>
                <p class="text-xs text-emerald-800">Siz <b>anonim</b> rejimdasiz. Ism va telefon kiritish shart emas.</p>
            </div>

            <!-- FORMA -->
            <form action="{{ route('appeal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="is_anonymous" id="is_anonymous_input" value="1">

                <!-- Shaxsiy ma'lumotlar -->
                <div id="personal-data" class="hidden space-y-4 transition-all duration-300">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Ism Familiya</label>
                        <input type="text" name="full_name" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Telefon</label>
                        <input type="tel" name="phone" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500 transition">
                    </div>
                </div>

                <!-- Muammo turi -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Muammo Turi</label>
                    <div class="relative">
                        <select name="type" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500 appearance-none transition">
                            <option value="" disabled selected>Tanlang...</option>
                            @if(isset($categories) && count($categories) > 0)
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                                @endforeach
                            @else
                                <option value="Korrupsiya">Korrupsiya</option>
                                <option value="Pora olish">Pora olish</option>
                                <option value="Boshqa">Boshqa</option>
                            @endif
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- Viloyat va Tuman -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Viloyat</label>
                        <select name="region" id="region_select" onchange="filterDistricts()" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="" disabled selected>Tanlang</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Tuman</label>
                        <select name="district" id="district_select" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="" disabled selected>---</option>
                        </select>
                    </div>
                </div>

                <!-- Tashkilot nomi -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Tashkilot nomi</label>
                    <input type="text" name="organization" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500 transition">
                </div>

                <!-- Batafsil -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Batafsil ma'lumot</label>
                    <textarea name="description" rows="5" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-emerald-500 transition" placeholder="Voqeani batafsil yozing..."></textarea>
                </div>

                <!-- Fayl yuklash -->
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer relative group">
                    <input type="file" name="files[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="relative z-0">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-cloud-upload-alt text-emerald-500 text-xl"></i>
                        </div>
                        <p class="text-xs text-gray-500 mb-1 font-bold">Rasm yoki video yuklang</p>
                        <p class="text-[10px] text-gray-400">Yoki shu yerga tashlang</p>
                    </div>
                </div>

                <!-- Yuborish tugmasi -->
                <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-emerald-200 mt-4 active:scale-95 transition-transform flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> YUBORISH
                </button>
            </form>
        </div>
        
        <div class="mt-8 text-center pb-8">
            <p class="text-[10px] text-gray-400">© 2026 Ishonch Portali. Respublika Maxsus Boshqarmasi.</p>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        // 1. Nusxalash (Copy)
        function copyToClipboard() {
            const trackCode = document.getElementById('trackCode').innerText;
            navigator.clipboard.writeText(trackCode).then(() => {
                const tooltip = document.getElementById('copyTooltip');
                tooltip.classList.remove('opacity-0');
                setTimeout(() => tooltip.classList.add('opacity-0'), 2000);
            });
        }

        // 2. Modal yopish
        function closeModal() {
            const modal = document.getElementById('successModal');
            if(modal) {
                modal.style.opacity = '0';
                setTimeout(() => modal.remove(), 300);
            }
        }

        // 3. Toggle Logikasi
        const toggle = document.getElementById('toggle');
        const personalData = document.getElementById('personal-data');
        const alertBox = document.getElementById('status-alert');
        const alertText = alertBox.querySelector('p');
        const hiddenInput = document.getElementById('is_anonymous_input');

        toggle.addEventListener('change', function() {
            if(!this.checked) {
                personalData.classList.remove('hidden');
                hiddenInput.value = "0"; 
                alertBox.className = "bg-orange-50 border border-orange-100 rounded-xl p-3 mb-6 flex gap-3 transition-all";
                alertText.innerHTML = "Siz <b>ochiq</b> rejimdasiz. Shaxsingiz oshkor bo'lishi mumkin.";
                alertBox.querySelector('i').className = "fas fa-user text-orange-500 mt-1";
            } else {
                personalData.classList.add('hidden');
                hiddenInput.value = "1";
                alertBox.className = "bg-emerald-50 border border-emerald-100 rounded-xl p-3 mb-6 flex gap-3 transition-all";
                alertText.innerHTML = "Siz <b>anonim</b> rejimdasiz. Ism va telefon kiritish shart emas.";
                alertBox.querySelector('i').className = "fas fa-shield-alt text-emerald-500 mt-1";
            }
        });

        // 4. Viloyat va Tumanlar
        const regionsData = {
            "Toshkent sh.": ["Bektemir", "Chilonzor", "Hamza", "Mirobod", "Mirzo Ulug'bek", "Sergeli", "Shayxontohur", "Olmazor", "Uchtepa", "Yakkasaroy", "Yunusobod"],
            "Toshkent vil.": ["Angren sh.", "Bekobod sh.", "Chirchiq sh.", "Olmaliq sh.", "Ohangaron", "Bo'stonliq", "Parkent", "Piskent", "Zangiota", "Qibray"],
            "Andijon": ["Andijon sh.", "Asaka", "Baliqchi", "Bo'z", "Bulung'ur", "Izboskan", "Qo'rg'ontepa", "Marhamat", "Oltinko'l", "Paxtaobod", "Shahrixon"],
            "Buxoro": ["Buxoro sh.", "G'ijduvon", "Jondor", "Kogon", "Olot", "Peshku", "Qorako'l", "Romitan", "Shofirkon", "Vobkent"],
            "Farg'ona": ["Farg'ona sh.", "Marg'ilon sh.", "Qo'qon sh.", "Beshariq", "Bog'dod", "Dang'ara", "O'zbekiston", "Rishton", "So'x", "Toshloq"],
            "Jizzax": ["Jizzax sh.", "Arnasoy", "Baxmal", "Do'stlik", "Forish", "G'allaorol", "Mirzacho'l", "Paxtakor", "Zomin"],
            "Namangan": ["Namangan sh.", "Chortoq", "Chust", "Kosonsoy", "Mingbuloq", "Norin", "Pop", "To'raqo'rg'on", "Uchqo'rg'on", "Yangiqo'rg'on"],
            "Navoiy": ["Navoiy sh.", "Zarafshon sh.", "Karmana", "Konimex", "Navbahor", "Nurota", "Qiziltepa", "Tomdi", "Uchquduq"],
            "Qashqadaryo": ["Qarshi sh.", "Shahrisabz sh.", "Dehqonobod", "G'uzor", "Kasbi", "Kitob", "Koson", "Mirishkor", "Muborak", "Nishon", "Yakkabog'"],
            "Qoraqalpog'iston": ["Nukus sh.", "Amudaryo", "Beruniy", "Chimboy", "Ellikqal'a", "Kegeyli", "Mo'ynoq", "Qo'ng'irot", "To'rtko'l", "Xo'jayli"],
            "Samarqand": ["Samarqand sh.", "Bulung'ur", "Ishtixon", "Jomboy", "Kattaqo'rg'on", "Narpay", "Oqdaryo", "Paxtachi", "Payariq", "Pastdarg'om", "Tayloq", "Urgut"],
            "Sirdaryo": ["Guliston sh.", "Yangiyer sh.", "Boyovut", "Guliston", "Mirzaobod", "Oqoltin", "Sardoba", "Sayxunobod", "Sirdaryo", "Xovos"],
            "Surxondaryo": ["Termiz sh.", "Angor", "Boysun", "Denov", "Jarqo'rg'on", "Muzrabot", "Oltinsoy", "Qumqo'rg'on", "Sherobod", "Sho'rchi", "Uzun"],
            "Xorazm": ["Urganch sh.", "Xiva sh.", "Bog'ot", "Gurlan", "Xonqa", "Hazorasp", "Qo'shko'pir", "Shovot", "Yangiariq", "Yangibozor"]
        };

        const regionSelect = document.getElementById('region_select');
        const districtSelect = document.getElementById('district_select');

        // Viloyatlarni to'ldirish
        for (const region in regionsData) {
            let option = document.createElement('option');
            option.value = region;
            option.textContent = region;
            regionSelect.appendChild(option);
        }

        // Tumanlarni filtrlash
        function filterDistricts() {
            const selectedRegion = regionSelect.value;
            districtSelect.innerHTML = '<option value="" disabled selected>Tanlang...</option>';
            if (selectedRegion && regionsData[selectedRegion]) {
                regionsData[selectedRegion].forEach(district => {
                    let option = document.createElement('option');
                    option.value = district;
                    option.textContent = district;
                    districtSelect.appendChild(option);
                });
            }
        }
    </script>
</body>
</html>