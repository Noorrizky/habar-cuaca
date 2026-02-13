<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    {{-- Viewport sangat penting untuk mobile responsive --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>AniWeather Aesthetic</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- Tambahkan p-4 agar kartu tidak nempel ke pinggir layar HP --}}
<body class="min-h-screen flex items-center justify-center overflow-hidden bg-gray-900 font-sans antialiased p-4">

    {{-- === LAYER 1: DYNAMIC ANIME BACKGROUND === --}}
    @if(isset($weather))
        <div class="absolute inset-0 z-0 transition-all duration-1000 ease-in-out">
            <img src="{{ $weather['meta']['image'] }}" class="w-full h-full object-cover scale-105 blur-[2px]" alt="Anime Weather Background">
            <div class="absolute inset-0 bg-black/40 mix-blend-overlay"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/50 to-black/30"></div>
        </div>
    @else
        <div class="absolute inset-0 z-0 bg-[url('https://images6.alphacoders.com/133/1330288.png')] bg-cover bg-center">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        </div>
    @endif

    {{-- === LAYER 2: DEKORASI (Hidden di HP kecil biar tidak berat) === --}}
    <div class="hidden md:block absolute top-[-10%] left-[-10%] w-64 h-64 bg-purple-500/30 rounded-full mix-blend-screen filter blur-[100px] animate-float opacity-70 z-1"></div>
    <div class="hidden md:block absolute bottom-[-10%] right-[-10%] w-80 h-80 bg-blue-500/30 rounded-full mix-blend-screen filter blur-[100px] animate-float animation-delay-2000 opacity-70 z-1"></div>


    {{-- === LAYER 3: MAIN GLASS CARD === --}}
    {{-- Lebar full di HP, tapi dibatasi max-w-md di desktop --}}
    <main class="relative z-10 w-full max-w-md mx-auto">
        
        {{-- Padding dikurangi jadi p-6 di HP, p-8 di Desktop --}}
        <div class="backdrop-blur-2xl bg-white/5 border border-white/20 rounded-3xl md:rounded-[2.5rem] shadow-[0_8px_32px_0_rgba(0,0,0,0.36)] overflow-hidden p-6 md:p-8 relative group">
            
            <div class="absolute inset-0 rounded-3xl md:rounded-[2.5rem] border-2 border-transparent group-hover:border-white/30 transition-all duration-500 pointer-events-none"></div>

            {{-- Header --}}
            <div class="mb-6 md:mb-8">
                {{-- Font size responsif: text-2xl di HP, 3xl di Desktop --}}
                <h1 class="text-center text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-200 via-purple-200 to-pink-200 drop-shadow-[0_2px_2px_rgba(0,0,0,0.8)] mb-4 md:mb-6 tracking-wider">
                    HABAR<span class="text-pink-400 animate-pulse">CUACA</span>
                </h1>

                <div class="flex gap-2 md:gap-3">
                    <form action="{{ route('weather.index') }}" method="GET" class="flex-1 relative group/input">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-2xl blur-md opacity-0 group-hover/input:opacity-100 transition-opacity duration-500"></div>
                        {{-- Input text size base agar tidak auto-zoom di iOS --}}
                        <input 
                            type="text" 
                            name="city" 
                            placeholder="Cari kota..." 
                            class="relative w-full py-3 pl-4 pr-10 rounded-xl md:rounded-2xl bg-black/30 border border-white/10 text-white text-base placeholder-white/40 focus:outline-none focus:border-white/40 focus:bg-black/50 transition-all shadow-inner"
                            value="{{ request('city') }}"
                            autocomplete="off"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/60 hover:text-white transition-colors p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 5.196 5.196Z" />
                            </svg>
                          </button>
                    </form>
                    <button onclick="getLocation()" class="relative group/btn p-3 md:p-3.5 rounded-xl md:rounded-2xl bg-black/30 border border-white/10 hover:bg-white/10 transition-all overflow-hidden active:scale-95">
                         <div class="absolute inset-0 bg-gradient-to-tr from-green-400/20 to-emerald-500/20 opacity-0 group-hover/btn:opacity-100 blur-md transition-opacity"></div>
                        <span class="relative text-lg md:text-xl">📍</span>
                    </button>
                </div>
            </div>

            @if(isset($error))
                <div class="bg-red-500/20 backdrop-blur-md border border-red-500/50 text-red-100 px-4 py-2 rounded-xl mb-4 text-center text-xs md:text-sm font-medium animate-pulse">
                    {{ $error }}
                </div>
            @endif

            @if(isset($weather))
                <div class="text-center space-y-4 md:space-y-6 animate-[fadeInUp_0.8s_ease-out]">
                    
                    <div class="relative mt-2">
                        {{-- Icon lebih kecil di HP (text-6xl) --}}
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 h-24 md:w-32 md:h-32 bg-white/10 rounded-full blur-[40px] animate-pulse"></div>
                        <div class="text-7xl md:text-8xl drop-shadow-[0_5px_15px_rgba(0,0,0,0.5)] filter hover:scale-110 transition-transform animate-float">
                            {{ $weather['meta']['icon'] }}
                        </div>
                    </div>

                    <div>
                        {{-- Suhu: 5xl di HP, 7xl di Desktop --}}
                        <h2 class="text-6xl md:text-7xl font-black text-white drop-shadow-lg leading-none tracking-tight mt-2">
                            {{ $weather['temp'] }}<span class="text-3xl md:text-4xl align-super font-bold text-white/80">°C</span>
                        </h2>
                        
                        <div class="flex flex-col items-center gap-2 mt-2">
                            <p class="text-lg md:text-xl text-white/90 font-semibold capitalize tracking-wide drop-shadow-md">
                                {{ $weather['desc'] }}
                            </p>
                            <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/10 border border-white/5 text-xs md:text-sm text-white/70 font-medium backdrop-blur-md">
                                📍 {{ $weather['city'] }}
                            </div>
                        </div>
                    </div>

                    <div class="h-px w-full bg-gradient-to-r from-transparent via-white/20 to-transparent my-4 md:my-6"></div>

                    {{-- Rekomendasi --}}
                    <div class="bg-gradient-to-br from-white/10 to-white/5 border border-white/10 rounded-xl md:rounded-2xl p-4 text-left relative overflow-hidden">
                        <h3 class="text-[10px] md:text-xs font-bold uppercase text-pink-300/80 mb-1 tracking-widest flex items-center gap-2">
                            <span class="text-base">✨</span> Insight
                        </h3>
                        <p class="text-sm md:text-base text-white/90 leading-relaxed font-medium drop-shadow-sm">
                            "{{ $weather['meta']['text'] }}"
                        </p>
                    </div>
                </div>
            @else
                <div class="text-center py-8 md:py-12 animate-[fadeInUp_0.8s_ease-out]">
                    <div class="text-5xl md:text-6xl mb-4 opacity-50 grayscale animate-pulse">🏔️</div>
                    <p class="text-white/60 text-base md:text-lg font-medium">Jelajahi Tempat Anda.~</p>
                    <p class="text-white/40 text-xs md:text-sm mt-1">Cari kota atau gunakan lokasi.</p>
                </div>
            @endif

        </div>
    </main>
    
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                const btn = document.querySelector('button[title="Lokasi Saya"]'); // Selector diperbaiki
                if(btn) {
                    btn.innerHTML = '<span class="animate-spin inline-block">⏳</span>';
                    btn.disabled = true;
                }
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else { alert("Geolocation tidak didukung."); }
        }
        function showPosition(p) { window.location.href = `/?lat=${p.coords.latitude}&lon=${p.coords.longitude}`; }
        function showError(e) { 
            alert("Gagal mengambil lokasi."); 
            location.reload(); 
        }
    </script>
</body>
</html>