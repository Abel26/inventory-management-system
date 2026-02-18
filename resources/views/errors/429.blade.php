@extends('errors.layout')

@section('content')
<!-- Error 429 Hero Section -->
<section class="error-hero flex items-center justify-center relative">
    <!-- Floating Particles -->
    <div class="particles"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="error-content animate-fade-in-up">
            <!-- Error Icon -->
            <div class="error-icon">
                <i class="ph ph-speedometer"></i>
            </div>
            
            <!-- Error Code -->
            <div class="error-code text-center">429</div>
            
            <!-- Error Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-center text-white mb-4" x-text="t('429.title')">
                {{ __('errors.429.title') }}
            </h1>
            
            <!-- Error Subtitle -->
            <h2 class="text-xl md:text-2xl text-center text-gray-300 mb-6" x-text="t('429.subtitle')">
                {{ __('errors.429.subtitle') }}
            </h2>
            
            <!-- Error Description -->
            <p class="text-center text-gray-400 mb-8 leading-relaxed" x-text="t('429.description')">
                {{ __('errors.429.description') }}
            </p>
            
            <!-- Rate Limit Info -->
            <div class="bg-orange-500/10 border border-orange-500/20 rounded-lg p-4 mb-8">
                <div class="flex items-center justify-center space-x-2">
                    <i class="ph ph-info text-orange-400 text-xl"></i>
                    <p class="text-orange-300 text-sm" x-text="t('429.rate_limit_info')">
                        {{ __('errors.429.rate_limit_info') }}
                    </p>
                </div>
            </div>
            
            <!-- Countdown Timer -->
            <div class="mb-8">
                <div class="text-center">
                    <div class=\"text-sm text-gray-400 mb-2\">
                        Wait <span x-text=\"countdown\">{{ $countdown ?? 30 }}</span> seconds before retrying
                    </div>
                    <div class="countdown-timer" x-text="countdown + 's'">30s</div>
                    <div class="w-full bg-gray-700 rounded-full h-2 mt-4">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-1000" 
                             :style="`width: ${(countdown / 30) * 100}%`"></div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="error-actions">
                <button @click="refreshPage()" 
                        :disabled="countdown > 0" 
                        data-countdown-button
                        class="error-button error-button-primary"
                        :class="{ 'opacity-50 cursor-not-allowed': countdown > 0 }">
                    <i class="ph ph-arrow-clockwise"></i>
                    <span x-text=\"countdown > 0 ? 'Wait ' + countdown + 's' : t('429.actions.refresh')\">
                        {{ __('errors.429.actions.refresh') }}
                    </span>
                </button>
                
                <a href="{{ route('landing') }}" class="error-button error-button-secondary">
                    <i class="ph ph-house"></i>
                    <span x-text="t('429.actions.home')">{{ __('errors.429.actions.home') }}</span>
                </a>
            </div>
            
            <!-- Rate Limit Details -->
            <div class="mt-8 p-6 bg-gray-800/50 rounded-lg border border-gray-700">
                <h3 class="text-lg font-semibold text-white mb-4 text-center">Rate Limit Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-700/30 rounded-lg">
                        <i class="ph ph-clock-countdown text-2xl text-blue-400 mb-2"></i>
                        <div class="text-sm font-medium text-gray-300">Time Window</div>
                        <div class="text-xs text-gray-500">1 minute</div>
                    </div>
                    <div class="text-center p-4 bg-gray-700/30 rounded-lg">
                        <i class="ph ph-battery-charging text-2xl text-green-400 mb-2"></i>
                        <div class="text-sm font-medium text-gray-300">Max Requests</div>
                        <div class="text-xs text-gray-500">60 per minute</div>
                    </div>
                    <div class="text-center p-4 bg-gray-700/30 rounded-lg">
                        <i class="ph ph-arrow-counter-clockwise text-2xl text-purple-400 mb-2"></i>
                        <div class="text-sm font-medium text-gray-300">Reset Time</div>
                        <div class="text-xs text-gray-500" x-text="countdown + 's'">30s</div>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <div class="inline-flex items-center space-x-2 text-sm text-gray-400">
                        <i class="ph ph-lightbulb"></i>
                        <span>Rate limiting helps maintain system performance for all users</span>
                    </div>
                </div>
            </div>
            
            <!-- Tips Section -->
            <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                <h4 class="text-sm font-medium text-blue-300 mb-2 text-center">Tips to avoid rate limiting:</h4>
                <ul class="text-xs text-gray-400 space-y-1 text-center">
                    <li>• Avoid refreshing the page multiple times quickly</li>
                    <li>• Use search filters instead of browsing through large datasets</li>
                    <li>• Wait for page loads before clicking again</li>
                </ul>
            </div>
            
            <!-- Error Info -->
            <div class="mt-8 pt-6 border-t border-gray-700 text-center">
                <p class="text-sm text-gray-500">
                    <span>Error Code: <span x-text="errorCode">{{ $errorCode ?? '429' }}</span></span> • 
                    <span>Time: <span x-text="timestamp">{{ $timestamp ?? now()->format('H:i:s') }}</span></span>
                </p>
                <p class="text-sm text-gray-400 mt-2" x-text="t('common.help_text')">
                    {{ __('errors.common.help_text') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Custom 429 Animation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set initial countdown from server or default to 30
    let countdown = 30;
    
    // Animate the speedometer icon
    const speedometerIcon = document.querySelector('.error-icon i');
    if (speedometerIcon) {
        speedometerIcon.style.animation = 'speedometerPulse 2s infinite';
    }
    
    // Add hover effects to rate limit cards
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
    
    // Update countdown display
    const updateCountdown = () => {
        const countdownElement = document.querySelector('.countdown-timer');
        const progressBar = document.querySelector('.bg-gradient-to-r');
        const refreshButton = document.querySelector('[data-countdown-button]');
        const waitText = document.querySelector('.text-center .text-sm.text-gray-400');
        
        if (countdownElement) {
            countdownElement.textContent = countdown + 's';
        }
        
        if (progressBar) {
            const percentage = (countdown / 30) * 100;
            progressBar.style.width = percentage + '%';
        }
        
        if (refreshButton) {
            refreshButton.disabled = countdown > 0;
            if (countdown > 0) {
                refreshButton.classList.add('opacity-50', 'cursor-not-allowed');
                refreshButton.querySelector('span').textContent = `Wait ${countdown}s`;
            } else {
                refreshButton.classList.remove('opacity-50', 'cursor-not-allowed');
                refreshButton.querySelector('span').textContent = 'Try Again';
            }
        }
        
        // Update reset time display
        const resetTimeElement = document.querySelector('.grid .text-xs');
        if (resetTimeElement && resetTimeElement.textContent.includes('s')) {
            resetTimeElement.textContent = countdown + 's';
        }
        
        // Update countdown color based on time remaining
        if (countdownElement) {
            if (countdown <= 5) {
                countdownElement.style.color = '#ef4444'; // Red
            } else if (countdown <= 10) {
                countdownElement.style.color = '#f59e0b'; // Amber
            } else {
                countdownElement.style.color = '#3b82f6'; // Blue
            }
        }
    };
    
    // Start countdown
    const countdownInterval = setInterval(() => {
        countdown--;
        updateCountdown();
        
        if (countdown <= 0) {
            clearInterval(countdownInterval);
            // Enable refresh button when countdown reaches 0
            const refreshButton = document.querySelector('[data-countdown-button]');
            if (refreshButton) {
                refreshButton.disabled = false;
                refreshButton.classList.remove('opacity-50', 'cursor-not-allowed');
                refreshButton.querySelector('span').textContent = 'Try Again';
            }
        }
    }, 1000);
    
    // Initial update
    updateCountdown();
    
    // Add visual feedback for button state changes
    const refreshButton = document.querySelector('[data-countdown-button]');
    if (refreshButton) {
        refreshButton.addEventListener('mouseenter', function() {
            if (countdown <= 0) {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 10px 25px -5px rgba(59, 130, 246, 0.5)';
            }
        });
        
        refreshButton.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    }
});

// Add custom animations
const style = document.createElement('style');
style.textContent = `
    @keyframes speedometerPulse {
        0%, 100% {
            transform: scale(1);
            filter: brightness(1);
        }
        50% {
            transform: scale(1.1);
            filter: brightness(1.2);
        }
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
    
    .error-button {
        transition: all 0.3s ease;
    }
    
    .error-button:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
    }
`;
document.head.appendChild(style);
</script>
@endsection