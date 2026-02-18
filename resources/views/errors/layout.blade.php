<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="errorPage">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title x-text="t('meta_title', {code: errorCode})">{{ __('errors.meta_title', ['code' => $errorCode ?? '404']) }}</title>
    <meta name="description" x-text="t('meta_description', {code: errorCode})" content="{{ __('errors.meta_description', ['code' => $errorCode ?? '404']) }}">
    <meta name="keywords" content="inventory management, error page, ebara">
    <meta name="author" content="Ivan Fadillah">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" x-text="t('meta_title', {code: errorCode})" content="{{ __('errors.meta_title', ['code' => $errorCode ?? '404']) }}">
    <meta property="og:description" x-text="t('meta_description', {code: errorCode})" content="{{ __('errors.meta_description', ['code' => $errorCode ?? '404']) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('assets/img/logo.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=georgia:400,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/css/error.css', 'resources/js/app.js'])
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
                
                <!-- Right Side: Language Toggle -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Language Toggle -->
                    <div class="language-toggle">
                        <button 
                            @click="setLanguage('id')" 
                            :class="{ 'active': lang === 'id' }"
                            x-text="translations.lang.id"
                        >
                            ID
                        </button>
                        <button 
                            @click="setLanguage('en')" 
                            :class="{ 'active': lang === 'en' }"
                            x-text="translations.lang.en"
                        >
                            EN
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    
    <!-- Main Error Content -->
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
    
    <!-- Alpine.js Error Page Data -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('errorPage', () => ({
                lang: 'id', // Default to Indonesian
                scrolled: false,
                errorCode: "{{ $errorCode ?? '404' }}",
                timestamp: "{{ $timestamp ?? now()->format('H:i:s') }}",
                countdown: {{ $countdown ?? 0 }},
                countdownInterval: null,
                translations: {
                    id: {
                        ...{!! json_encode(trans('landing', [], 'id')) !!},
                        ...{!! json_encode(trans('errors', [], 'id')) !!}
                    },
                    en: {
                        ...{!! json_encode(trans('landing', [], 'en')) !!},
                        ...{!! json_encode(trans('errors', [], 'en')) !!}
                    }
                },
                
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
                    });
                    
                    // Start countdown if needed
                    if (this.countdown > 0) {
                        this.startCountdown();
                    }
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
                
                t(key, replacements = {}) {
                    // Split the key by dot to navigate nested objects
                    const keys = key.split('.');
                    
                    // Start from the current language object
                    let value = this.translations[this.lang];
                    
                    // Navigate through the keys
                    for (const k of keys) {
                        if (value && typeof value === 'object' && k in value) {
                            value = value[k];
                        } else {
                            // If key not found, try fallback to English
                            let fallback = this.translations['en'];
                            for (const fbK of keys) {
                                if (fallback && typeof fallback === 'object' && fbK in fallback) {
                                    fallback = fallback[fbK];
                                } else {
                                    return key; // Return key if not found in fallback either
                                }
                            }
                            value = fallback;
                            break;
                        }
                    }
                    
                    // Handle string replacements
                    if (typeof value === 'string') {
                        // Replace placeholders in the string
                        value = value.replace(/\{code\}/g, this.errorCode);
                        value = value.replace(/\{time\}/g, this.timestamp);
                        value = value.replace(/\{seconds\}/g, this.countdown);
                        
                        // Replace custom replacements passed as parameter
                        Object.entries(replacements).forEach(([key, val]) => {
                            value = value.replace(new RegExp(`\\{${key}\\}`, 'g'), val);
                        });
                    }
                    
                    return value || key;
                },
                
                // Countdown functionality (for 429 error)
                startCountdown() {
                    if (this.countdown > 0) {
                        this.countdownInterval = setInterval(() => {
                            if (this.countdown > 0) {
                                this.countdown--;
                            } else {
                                clearInterval(this.countdownInterval);
                            }
                        }, 1000);
                    }
                },
                
                refreshPage() {
                    window.location.reload();
                },
                
                goBack() {
                    window.history.back();
                }
            }))
        })
    </script>
</body>
</html>