@extends('errors.layout')

@section('content')
<!-- Error 404 Hero Section -->
<section class="error-hero flex items-center justify-center relative">
    <!-- Floating Particles -->
    <div class="particles"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="error-content animate-fade-in-up">
            <!-- Error Icon -->
            <div class="error-icon">
                <i class="ph ph-map-pin-slash"></i>
            </div>
            
            <!-- Error Code -->
            <div class="error-code text-center">404</div>
            
            <!-- Error Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-center text-white mb-4" x-text="t('404.title')">
                {{ __('errors.404.title') }}
            </h1>
            
            <!-- Error Subtitle -->
            <h2 class="text-xl md:text-2xl text-center text-gray-300 mb-6" x-text="t('404.subtitle')">
                {{ __('errors.404.subtitle') }}
            </h2>
            
            <!-- Error Description -->
            <p class="text-center text-gray-400 mb-8 leading-relaxed" x-text="t('404.description')">
                {{ __('errors.404.description') }}
            </p>
            
            <!-- Action Buttons -->
            <div class="error-actions">
                <a href="{{ route('landing') }}" class="error-button error-button-primary">
                    <i class="ph ph-house"></i>
                    <span x-text="t('404.actions.home')">{{ __('errors.404.actions.home') }}</span>
                </a>
                
                @guest
                <a href="{{ route('login') }}" class="error-button error-button-secondary">
                    <i class="ph ph-sign-in"></i>
                    <span x-text="t('404.actions.dashboard')">{{ __('errors.404.actions.dashboard') }}</span>
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="error-button error-button-secondary">
                    <i class="ph ph-layout"></i>
                    <span x-text="t('404.actions.dashboard')">{{ __('errors.404.actions.dashboard') }}</span>
                </a>
                @endguest
                
                <a href="{{ route('landing') }}#search" class="error-button error-button-secondary">
                    <i class="ph ph-magnifying-glass"></i>
                    <span x-text="t('404.actions.search')">{{ __('errors.404.actions.search') }}</span>
                </a>
            </div>
            
            <!-- Suggestions Section -->
            <div class="error-suggestions">
                <h3 class="text-lg font-semibold text-white text-center mb-4" x-text="t('404.suggestions.title')">
                    {{ __('errors.404.suggestions.title') }}
                </h3>
                
                <div class="suggestion-links">
                    @guest
                    <a href="{{ route('login') }}" class="suggestion-link">
                        <i class="ph ph-sign-in text-2xl mb-2"></i>
                        <div class="font-medium" x-text="t('404.suggestions.login')">
                            {{ __('errors.404.suggestions.login') }}
                        </div>
                        <div class="text-sm text-gray-400">Akses dashboard inventaris</div>
                    </a>
                    @else
                    <a href="{{ route('dashboard') }}" class="suggestion-link">
                        <i class="ph ph-layout text-2xl mb-2"></i>
                        <div class="font-medium" x-text="t('404.suggestions.dashboard')">
                            {{ __('errors.404.suggestions.dashboard') }}
                        </div>
                        <div class="text-sm text-gray-400">Lihat overview sistem</div>
                    </a>
                    @endguest
                    
                    <a href="{{ route('assets.materials.index') }}" class="suggestion-link">
                        <i class="ph ph-package text-2xl mb-2"></i>
                        <div class="font-medium" x-text="t('404.suggestions.assets')">
                            {{ __('errors.404.suggestions.assets') }}
                        </div>
                        <div class="text-sm text-gray-400">Jelajahi daftar aset</div>
                    </a>
                    
                    <a href="{{ route('reports.index') }}" class="suggestion-link">
                        <i class="ph ph-chart-line text-2xl mb-2"></i>
                        <div class="font-medium" x-text="t('404.suggestions.reports')">
                            {{ __('errors.404.suggestions.reports') }}
                        </div>
                        <div class="text-sm text-gray-400">Lihat laporan inventaris</div>
                    </a>
                </div>
            </div>
            
            <!-- Error Info -->
            <div class="mt-8 pt-6 border-t border-gray-700 text-center">
                <p class="text-sm text-gray-500">
                    <span>Error Code: <span x-text="errorCode">{{ $errorCode ?? '404' }}</span></span> • 
                    <span>Time: <span x-text="timestamp">{{ $timestamp ?? now()->format('H:i:s') }}</span></span>
                </p>
                <p class="text-sm text-gray-400 mt-2" x-text="t('common.help_text')">
                    {{ __('errors.common.help_text') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Custom 404 Animation -->
<script>
// Add custom animation for 404 page
document.addEventListener('DOMContentLoaded', function() {
    // Animate the error code
    const errorCode = document.querySelector('.error-code');
    if (errorCode) {
        errorCode.style.animation = 'pulse 2s infinite';
    }
    
    // Add interactive hover effects
    const suggestionLinks = document.querySelectorAll('.suggestion-link');
    suggestionLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-2px) scale(1)';
        });
    });
    
    // Add typing effect to error description
    const description = document.querySelector('.error-content p');
    if (description) {
        description.style.opacity = '0';
        setTimeout(() => {
            description.style.transition = 'opacity 1s ease-in';
            description.style.opacity = '1';
        }, 500);
    }
});

// Add pulse animation
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.8;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection