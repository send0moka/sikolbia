<x-layouts.landing>
    <!-- Hero Section with Carousel -->
    <section class="relative bg-gradient-to-r from-[#6a4c35] to-[#782c7c] text-white overflow-hidden">
        <!-- Carousel Background -->
        <div class="absolute inset-0 z-0" x-data="{ 
            currentSlide: 0,
            slides: [
                'https://images.unsplash.com/photo-1592997571659-0b21ff64313b?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', // Padi/beras
                'https://images.unsplash.com/photo-1662318183329-e2ee2bb53f9e?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', // Tomat segar
                'https://images.unsplash.com/photo-1546860255-95536c19724e?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', // Cabai merah
                'https://images.unsplash.com/photo-1748118869323-73769eab2f24?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', // Cabbage
                'https://images.unsplash.com/photo-1573414405995-2012861b74e0?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', // Jahe kunyit
                'https://images.unsplash.com/photo-1590868309235-ea34bed7bd7f?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'  // Kentang
            ],
            intervalId: null,
            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            },
            prevSlide() {
                this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1;
            },
            goToSlide(index) {
                this.currentSlide = index;
            },
            startAutoSlide() {
                this.intervalId = setInterval(() => {
                    this.nextSlide();
                }, 5000);
            },
            init() {
                this.startAutoSlide();
            }
        }" x-init="init()">
            <!-- Carousel Images -->
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="currentSlide === index" 
                     x-transition:enter="transition-opacity duration-1000"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity duration-1000"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0">
                    <img :src="slide" 
                         alt="Hasil Bumi Indonesia" 
                         class="w-full h-full object-cover opacity-60">
                </div>
            </template>
            
            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#6a4c35]/40 to-[#782c7c]/40"></div>
            
            <!-- Navigation Arrows -->
            <button @click="prevSlide()" 
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-white/20 hover:bg-white/40 rounded-full p-2 transition-all duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button @click="nextSlide()" 
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-white/20 hover:bg-white/40 rounded-full p-2 transition-all duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <!-- Dots Indicator -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-10 flex space-x-2">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="goToSlide(index)" 
                            :class="currentSlide === index ? 'bg-[#efefa4]' : 'bg-white/50 hover:bg-white/70'"
                            class="w-3 h-3 rounded-full transition-all duration-300"></button>
                </template>
            </div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-36">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 drop-shadow-lg">
                    Basis Data Konsumsi Pangan
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-[#efefa4] drop-shadow-md">
                    Sistem Informasi Ketersediaan dan Konsumsi Pangan Indonesia
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#about" 
                       class="bg-[#efefa4] text-[#6a4c35] px-8 py-3 rounded-lg font-semibold hover:bg-white hover:shadow-lg transition duration-300 shadow-md">
                        Pelajari Lebih Lanjut
                    </a>
                    <a href="{{ route('login') }}" 
                       class="border-2 border-[#efefa4] text-[#efefa4] px-8 py-3 rounded-lg font-semibold hover:bg-[#efefa4] hover:text-[#6a4c35] transition duration-300 backdrop-blur-sm">
                        Akses Data
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-[#2f8b3e] mb-2">10+</div>
                    <div class="text-neutral-600 font-medium">Kelompok Pangan</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-[#6a4c35] mb-2">200+</div>
                    <div class="text-neutral-600 font-medium">Jenis Komoditi</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-[#782c7c] mb-2">30+</div>
                    <div class="text-neutral-600 font-medium">Tahun Data</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-[#e34e38] mb-2">34</div>
                    <div class="text-neutral-600 font-medium">Provinsi</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-[#efefa4]/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-[#6a4c35] mb-6">
                    Tentang Sistem
                </h2>
                <p class="text-xl text-neutral-700 max-w-3xl mx-auto font-medium">
                    Basis Data Konsumsi Pangan adalah sistem informasi terintegrasi yang menyediakan 
                    data komprehensif tentang ketersediaan dan konsumsi pangan di Indonesia
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-2xl font-bold text-[#6a4c35] mb-6">Fitur Utama</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 bg-[#2f8b3e] rounded-full flex items-center justify-center mt-1">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-[#6a4c35]">Data Neraca Bahan Makanan (NBM)</h4>
                                <p class="text-neutral-700">Ketersediaan pangan berdasarkan produksi, impor, ekspor, dan penggunaan</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 bg-[#782c7c] rounded-full flex items-center justify-center mt-1">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-[#6a4c35]">Data Susenas Konsumsi</h4>
                                <p class="text-neutral-700">Survei sosial ekonomi nasional untuk data konsumsi rumah tangga</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 bg-[#e34e38] rounded-full flex items-center justify-center mt-1">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-[#6a4c35]">Analisis Per Kapita</h4>
                                <p class="text-neutral-700">Konsumsi pangan per kapita dalam periode seminggu dan setahun</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold text-neutral-900 mb-6">Akses Data</h3>
                        <p class="text-neutral-600 mb-6">
                            Dapatkan akses ke data lengkap ketersediaan dan konsumsi pangan 
                            untuk keperluan penelitian, analisis, dan perencanaan kebijakan
                        </p>
                        <a href="{{ route('login') }}" 
                           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                            Login Manajemen Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-[#6a4c35] mb-6">
                    Layanan Data
                </h2>
                <p class="text-xl text-neutral-700 font-medium">
                    Akses berbagai jenis data dan informasi pangan
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Ketersediaan -->
                <div class="bg-gradient-to-br from-[#efefa4]/20 to-[#2f8b3e]/10 p-6 rounded-lg text-center hover:shadow-lg transition duration-300 border border-[#efefa4]/50">
                    <div class="w-16 h-16 bg-[#2f8b3e] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#6a4c35] mb-2">Ketersediaan</h3>
                    <p class="text-neutral-700 mb-4">Data neraca bahan makanan dan ketersediaan pangan</p>
                    <a href="{{ route('ketersediaan.konsep-metode') }}" 
                       class="text-[#2f8b3e] hover:text-[#6a4c35] font-semibold transition-colors">
                        Selengkapnya →
                    </a>
                </div>

                <!-- Konsumsi -->
                <div class="bg-gradient-to-br from-[#efefa4]/20 to-[#782c7c]/10 p-6 rounded-lg text-center hover:shadow-lg transition duration-300 border border-[#efefa4]/50">
                    <div class="w-16 h-16 bg-[#782c7c] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#6a4c35] mb-2">Konsumsi</h3>
                    <p class="text-neutral-700 mb-4">Data konsumsi pangan rumah tangga dari Susenas</p>
                    <a href="{{ route('konsumsi.konsep-metode') }}" 
                       class="text-[#782c7c] hover:text-[#6a4c35] font-semibold transition-colors">
                        Selengkapnya →
                    </a>
                </div>

                <!-- Per Kapita Seminggu -->
                <div class="bg-gradient-to-br from-[#efefa4]/20 to-[#e34e38]/10 p-6 rounded-lg text-center hover:shadow-lg transition duration-300 border border-[#efefa4]/50">
                    <div class="w-16 h-16 bg-[#e34e38] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#6a4c35] mb-2">Per Kapita Seminggu</h3>
                    <p class="text-neutral-700 mb-4">Konsumsi pangan per kapita dalam seminggu</p>
                    <a href="{{ route('konsumsi.per-kapita-seminggu') }}" 
                       class="text-[#e34e38] hover:text-[#6a4c35] font-semibold transition-colors">
                        Selengkapnya →
                    </a>
                </div>

                <!-- Per Kapita Setahun -->
                <div class="bg-gradient-to-br from-[#efefa4]/20 to-[#6a4c35]/10 p-6 rounded-lg text-center hover:shadow-lg transition duration-300 border border-[#efefa4]/50">
                    <div class="w-16 h-16 bg-[#6a4c35] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#6a4c35] mb-2">Per Kapita Setahun</h3>
                    <p class="text-neutral-700 mb-4">Konsumsi pangan per kapita dalam setahun</p>
                    <a href="{{ route('konsumsi.per-kapita-setahun') }}" 
                       class="text-[#6a4c35] hover:text-[#782c7c] font-semibold transition-colors">
                        Selengkapnya →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-[#2f8b3e] to-[#6a4c35] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Butuh Akses Data Lengkap?
            </h2>
            <p class="text-xl mb-8 text-[#efefa4]">
                Login ke sistem manajemen data untuk mengakses, mengelola, dan menganalisis data pangan
            </p>
            <a href="{{ route('login') }}" 
               class="bg-[#efefa4] text-[#6a4c35] px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white transition duration-300 inline-block shadow-lg">
                Akses Manajemen Data
            </a>
        </div>
    </section>
</x-layouts.landing>
