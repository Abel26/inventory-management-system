<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="landingPage">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title x-text="t('meta_title')">{{ __('landing.meta_title') }}</title>
    <meta name="description" x-text="t('meta_description')" content="{{ __('landing.meta_description') }}">
    <meta name="keywords" content="inventory management, mold modification, production efficiency, ebara">
    <meta name="author" content="Ivan Fadillah">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" x-text="t('meta_title')" content="{{ __('landing.meta_title') }}">
    <meta property="og:description" x-text="t('meta_description')" content="{{ __('landing.meta_description') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('assets/img/logo.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=georgia:400,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js'])
</head>
<body class="landing-page">
    <!-- Navbar -->
    <nav class="navbar-landing" :class="{ 'scrolled': scrolled }">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="/" class="flex items-center space-x-3">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Ebara IMS" class="h-10 w-auto">
                        <span class="text-xl font-bold text-white">Ebara IMS</span>
                    </a>
                </div>
                
                <!-- Right Side: Language Toggle + Login -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Language Dropdown -->
                    <div class="relative" x-data="{ langOpen: false }" @click.outside="langOpen = false">
                        <button 
                            @click="langOpen = !langOpen"
                            class="flex items-center space-x-2 bg-white/10 hover:bg-white/20 text-white rounded-full px-4 py-2 transition-all duration-300 border border-white/20 backdrop-blur-sm"
                        >
                            <i class="ph ph-translate text-lg"></i>
                            <span class="text-sm font-medium hidden sm:inline-block" x-text="lang === 'id' ? 'Bahasa Indonesia' : 'English'"></span>
                            <span class="text-sm font-medium sm:hidden" x-text="lang === 'id' ? 'ID' : 'EN'"></span>
                            <i class="ph ph-caret-down text-xs text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': langOpen }"></i>
                        </button>

                        <div 
                            x-show="langOpen"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-slate-900/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-xl overflow-hidden z-50 transform origin-top-right ring-1 ring-black/5"
                            style="display: none;"
                        >
                            <div class="py-1">
                                <button 
                                    @click="setLanguage('id'); langOpen = false"
                                    class="w-full text-left px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-white/10 transition-colors flex items-center justify-between group"
                                    :class="{ 'bg-white/5 text-white': lang === 'id' }"
                                >
                                    <div class="flex items-center space-x-3">
                                        <span class="text-lg">🇮🇩</span>
                                        <span>Bahasa Indonesia</span>
                                    </div>
                                    <i class="ph ph-check text-emerald-500" x-show="lang === 'id'"></i>
                                </button>
                                <button 
                                    @click="setLanguage('en'); langOpen = false"
                                    class="w-full text-left px-4 py-3 text-sm text-gray-300 hover:text-white hover:bg-white/10 transition-colors flex items-center justify-between group"
                                    :class="{ 'bg-white/5 text-white': lang === 'en' }"
                                >
                                    <div class="flex items-center space-x-3">
                                        <span class="text-lg">🇬🇧</span>
                                        <span>English</span>
                                    </div>
                                    <i class="ph ph-check text-emerald-500" x-show="lang === 'en'"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Login Button -->
                    <a href="{{ route('login') }}" class="cta-button">
                        <span x-text="t('nav.login')">{{ __('landing.nav.login') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="glass-dark py-8 mt-16">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <p class="text-gray-400">
                    <span x-text="t('footer.copyright')">{{ __('landing.footer.copyright') }}</span>
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    <span x-text="t('footer.built_by')">{{ __('landing.footer.built_by') }}</span> Ivan Fadillah
                </p>
            </div>
        </div>
    </footer>
    
    <!-- Alpine.js Landing Page Data -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('landingPage', () => ({
                lang: 'id', // Default to Indonesian
                scrolled: false,
                translations: {
                    id: @json(trans('landing', [], 'id')),
                    en: @json(trans('landing', [], 'en'))
                },
                
                // FAQ Accordion State
                activeFaq: null,
                
                // Count-up Animation State
                metricsAnimated: false,
                
                // Search State
                searchQuery: '',
                searchResults: [],
                searchLoading: false,
                showSearchResults: false,
                
                init() {
                    // Check for saved language preference
                    const savedLang = localStorage.getItem('preferred-language');
                    if (savedLang && ['id', 'en'].includes(savedLang)) {
                        this.lang = savedLang;
                    }
                    
                    // Update document lang attribute initially
                    document.documentElement.lang = this.lang === 'id' ? 'id' : 'en';

                    // Handle scroll events for navbar
                    window.addEventListener('scroll', () => {
                        this.scrolled = window.scrollY > 50;
                        
                        // Trigger count-up animation when metrics section is visible
                        if (!this.metricsAnimated) {
                            const metricsSection = document.querySelector('.metrics-section');
                            if (metricsSection) {
                                const rect = metricsSection.getBoundingClientRect();
                                if (rect.top < window.innerHeight && rect.bottom > 0) {
                                    this.animateMetrics();
                                    this.metricsAnimated = true;
                                }
                            }
                        }
                    });
                },
                
                async setLanguage(language) {
                    this.lang = language;
                    localStorage.setItem('preferred-language', language);
                    
                    // Update document lang attribute
                    document.documentElement.lang = language === 'id' ? 'id' : 'en';

                    // Sync with server session
                    try {
                        await fetch('/lang/' + language);
                    } catch (e) {
                        console.error('Failed to sync language', e);
                    }
                },
                
                t(key) {
                    // Split the key by dot to navigate nested objects
                    const keys = key.split('.');
                    
                    // Start from the current language object
                    let value = this.translations[this.lang];
                    
                    // Navigate through the keys
                    for (const k of keys) {
                        if (value && value[k] !== undefined) {
                            value = value[k];
                        } else {
                            // If key not found, try fallback to English
                            let fallback = this.translations['en'];
                            for (const fbK of keys) {
                                if (fallback && fallback[fbK] !== undefined) {
                                    fallback = fallback[fbK];
                                } else {
                                    return key; // Return key if not found in fallback either
                                }
                            }
                            return fallback;
                        }
                    }
                    
                    return value;
                },
                
                // FAQ Accordion Toggle
                toggleFaq(index) {
                    this.activeFaq = this.activeFaq === index ? null : index;
                },
                
                // Count-up Animation
                animateMetrics() {
                    const animateValue = (element, start, end, duration) => {
                        const startTimestamp = Date.now();
                        const step = () => {
                            const timestamp = Date.now();
                            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                            const value = Math.floor(progress * (end - start) + start);
                            element.textContent = value + (element.dataset.suffix || '');
                            if (progress < 1) {
                                window.requestAnimationFrame(step);
                            }
                        };
                        window.requestAnimationFrame(step);
                    };
                    
                    // Animate metrics
                    const metricElements = document.querySelectorAll('.metric-number');
                    metricElements.forEach(element => {
                        const finalValue = parseInt(element.textContent);
                        if (!isNaN(finalValue)) {
                            element.textContent = '0' + (element.dataset.suffix || '');
                            setTimeout(() => {
                                animateValue(element, 0, finalValue, 2000);
                            }, 200);
                        }
                    });
                },
                
                // Search Functionality
                async performSearch() {
                    if (this.searchQuery.length < 2) {
                        this.searchResults = [];
                        this.showSearchResults = false;
                        return;
                    }
                    
                    this.searchLoading = true;
                    
                    try {
                        const response = await fetch(`/api/public-search?query=${encodeURIComponent(this.searchQuery)}`);
                        const data = await response.json();
                        
                        this.searchResults = data.results || [];
                        this.showSearchResults = true; // Always show dropdown when search is performed
                    } catch (error) {
                        console.error('Search error:', error);
                        this.searchResults = [];
                        this.showSearchResults = false;
                    } finally {
                        this.searchLoading = false;
                    }
                },
                
                clearSearch() {
                    this.searchQuery = '';
                    this.searchResults = [];
                    this.showSearchResults = false;
                },
                
                // Close search when clicking outside
                closeSearch() {
                    setTimeout(() => {
                        this.showSearchResults = false;
                    }, 200);
                }
            }))
        })
    </script>
</body>
</html>