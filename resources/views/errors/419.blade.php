@extends('errors.layout')

@section('content')
<!-- Error 419 Hero Section -->
<section class="error-hero flex items-center justify-center relative">
    <!-- Floating Particles -->
    <div class="particles"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="error-content animate-fade-in-up">
            <!-- Error Icon -->
            <div class="error-icon">
                <i class="ph ph-clock-counter-clockwise"></i>
            </div>
            
            <!-- Error Code -->
            <div class="error-code text-center">419</div>
            
            <!-- Error Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-center text-white mb-4" x-text="t('419.title')">
                {{ __('errors.419.title') }}
            </h1>
            
            <!-- Error Subtitle -->
            <h2 class="text-xl md:text-2xl text-center text-gray-300 mb-6" x-text="t('419.subtitle')">
                {{ __('errors.419.subtitle') }}
            </h2>
            
            <!-- Error Description -->
            <p class="text-center text-gray-400 mb-8 leading-relaxed" x-text="t('419.description')">
                {{ __('errors.419.description') }}
            </p>
            
            <!-- Security Note -->
            <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4 mb-8">
                <div class="flex items-center justify-center space-x-2">
                    <i class="ph ph-shield-check text-green-400 text-xl"></i>
                    <p class="text-green-300 text-sm" x-text="t('419.security_note')">
                        {{ __('errors.419.security_note') }}
                    </p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="error-actions">
                <button @click="refreshPage()" class="error-button error-button-primary">
                    <i class="ph ph-arrow-clockwise"></i>
                    <span x-text="t('419.actions.refresh')">{{ __('errors.419.actions.refresh') }}</span>
                </button>
                
                @guest
                <a href="{{ route('login') }}" class="error-button error-button-secondary">
                    <i class="ph ph-sign-in"></i>
                    <span x-text="t('419.actions.login')">{{ __('errors.419.actions.login') }}</span>
                </a>
                @else
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="error-button error-button-secondary">
                        <i class="ph ph-sign-out"></i>
                        <span x-text="t('419.actions.login')">{{ __('errors.419.actions.login') }}</span>
                    </button>
                </form>
                @endguest
                
                <a href="{{ route('landing') }}" class="error-button error-button-secondary">
                    <i class="ph ph-house"></i>
                    <span x-text="t('419.actions.home')">{{ __('errors.419.actions.home') }}</span>
                </a>
            </div>
            
            <!-- Session Information -->
            <div class="mt-8 p-6 bg-gray-800/50 rounded-lg border border-gray-700">
                <h3 class="text-lg font-semibold text-white mb-4 text-center">Session Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-gray-700/30 rounded-lg">
                        <i class="ph ph-clock text-2xl text-blue-400 mb-2"></i>
                        <div class="text-sm font-medium text-gray-300">Session Timeout</div>
                        <div class="text-xs text-gray-500">2 hours</div>
                    </div>
                    <div class="text-center p-4 bg-gray-700/30 rounded-lg">
                        <i class="ph ph-shield-check text-2xl text-green-400 mb-2"></i>
                        <div class="text-sm font-medium text-gray-300">CSRF Protection</div>
                        <div class="text-xs text-gray-500">Active</div>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <div class="inline-flex items-center space-x-2 text-sm text-gray-400">
                        <i class="ph ph-info"></i>
                        <span>CSRF tokens protect against cross-site request forgery attacks</span>
                    </div>
                </div>
            </div>
            
            <!-- Auto-refresh Countdown -->
            <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                <div class="text-center">
                    <div class="text-sm text-blue-300 mb-2">Auto-refresh in</div>
                    <div class="countdown-timer" x-text="countdown + 's'">10s</div>
                    <div class="text-xs text-gray-400 mt-2">Or click refresh button above</div>
                </div>
            </div>
            
            <!-- Error Info -->
            <div class="mt-8 pt-6 border-t border-gray-700 text-center">
                <p class="text-sm text-gray-500">
                    <span>Error Code: <span x-text="errorCode">{{ $errorCode ?? '419' }}</span></span> • 
                    <span>Time: <span x-text="timestamp">{{ $timestamp ?? now()->format('H:i:s') }}</span></span>
                </p>
                <p class="text-sm text-gray-400 mt-2" x-text="t('common.help_text')">
                    {{ __('errors.common.help_text') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Custom 419 Animation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set countdown for auto-refresh
    let countdown = 10;
    const countdownElement = document.querySelector('.countdown-timer');
    
    const countdownInterval = setInterval(() => {
        countdown--;
        if (countdownElement) {
            countdownElement.textContent = countdown + 's';
        }
        
        if (countdown <= 0) {
            clearInterval(countdownInterval);
            window.location.reload();
        }
    }, 1000);
    
    // Animate the clock icon
    const clockIcon = document.querySelector('.error-icon i');
    if (clockIcon) {
        clockIcon.style.animation = 'rotate 2s linear infinite';
    }
    
    // Add hover effects to session info cards
    const infoCards = document.querySelectorAll('.grid > div');
    infoCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.boxShadow = '0 10px 25px -5px rgba(59, 130, 246, 0.3)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
    
    // Clear countdown if user interacts with the page
    const clearCountdown = () => {
        clearInterval(countdownInterval);
        if (countdownElement) {
            countdownElement.parentElement.innerHTML = '<div class="text-sm text-gray-400">Auto-refresh cancelled</div>';
        }
    };
    
    // Clear countdown on any user interaction
    document.addEventListener('click', clearCountdown);
    document.addEventListener('keydown', clearCountdown);
    document.addEventListener('scroll', clearCountdown);
    
    // Add visual feedback for countdown
    const updateCountdownColor = () => {
        if (countdownElement) {
            if (countdown <= 3) {
                countdownElement.style.color = '#ef4444'; // Red
            } else if (countdown <= 5) {
                countdownElement.style.color = '#f59e0b'; // Amber
            } else {
                countdownElement.style.color = '#3b82f6'; // Blue
            }
        }
    };
    
    const colorInterval = setInterval(() => {
        updateCountdownColor();
        if (countdown <= 0) {
            clearInterval(colorInterval);
        }
    }, 1000);
});

// Add custom animations
const style = document.createElement('style');
style.textContent = `
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .grid > div {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .grid > div:hover {
        border-color: rgba(59, 130, 246, 0.5);
    }
    
    .countdown-timer {
        transition: color 0.3s ease;
    }
`;
document.head.appendChild(style);
</script>
@endsection