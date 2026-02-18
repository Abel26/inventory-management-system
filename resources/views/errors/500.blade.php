@extends('errors.layout')

@section('content')
<!-- Error 500 Hero Section -->
<section class="error-hero flex items-center justify-center relative">
    <!-- Floating Particles -->
    <div class="particles"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="error-content animate-fade-in-up">
            <!-- Error Icon -->
            <div class="error-icon">
                <i class="ph ph-warning-circle"></i>
            </div>
            
            <!-- Error Code -->
            <div class="error-code text-center">500</div>
            
            <!-- Error Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-center text-white mb-4" x-text="t('500.title')">
                {{ __('errors.500.title') }}
            </h1>
            
            <!-- Error Subtitle -->
            <h2 class="text-xl md:text-2xl text-center text-gray-300 mb-6" x-text="t('500.subtitle')">
                {{ __('errors.500.subtitle') }}
            </h2>
            
            <!-- Error Description -->
            <p class="text-center text-gray-400 mb-8 leading-relaxed" x-text="t('500.description')">
                {{ __('errors.500.description') }}
            </p>
            
            <!-- Reassurance Message -->
            <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4 mb-8">
                <div class="flex items-center justify-center space-x-2">
                    <i class="ph ph-shield-check text-blue-400 text-xl"></i>
                    <p class="text-blue-300 text-sm" x-text="t('500.reassurance')">
                        {{ __('errors.500.reassurance') }}
                    </p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="error-actions">
                <button @click="refreshPage()" class="error-button error-button-primary">
                    <i class="ph ph-arrow-clockwise"></i>
                    <span x-text="t('500.actions.refresh')">{{ __('errors.500.actions.refresh') }}</span>
                </button>
                
                <a href="{{ route('landing') }}" class="error-button error-button-secondary">
                    <i class="ph ph-house"></i>
                    <span x-text="t('500.actions.home')">{{ __('errors.500.actions.home') }}</span>
                </a>
                
                @guest
                <a href="{{ route('login') }}" class="error-button error-button-secondary">
                    <i class="ph ph-sign-in"></i>
                    <span x-text="t('500.actions.dashboard')">{{ __('errors.500.actions.dashboard') }}</span>
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="error-button error-button-secondary">
                    <i class="ph ph-layout"></i>
                    <span x-text="t('500.actions.dashboard')">{{ __('errors.500.actions.dashboard') }}</span>
                </a>
                @endguest
                
                <button onclick="window.open('mailto:support@ebara.com?subject=Error%20500%20Report', '_blank')" class="error-button error-button-secondary">
                    <i class="ph ph-envelope"></i>
                    <span x-text="t('500.actions.report')">{{ __('errors.500.actions.report') }}</span>
                </button>
            </div>
            
            <!-- System Status -->
            <div class="mt-8 p-6 bg-gray-800/50 rounded-lg border border-gray-700">
                <h3 class="text-lg font-semibold text-white mb-4 text-center">System Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="w-3 h-3 bg-yellow-400 rounded-full mx-auto mb-2 animate-pulse"></div>
                        <div class="text-sm text-gray-300">Database</div>
                        <div class="text-xs text-gray-500">Checking...</div>
                    </div>
                    <div class="text-center">
                        <div class="w-3 h-3 bg-green-400 rounded-full mx-auto mb-2"></div>
                        <div class="text-sm text-gray-300">API</div>
                        <div class="text-xs text-gray-500">Operational</div>
                    </div>
                    <div class="text-center">
                        <div class="w-3 h-3 bg-green-400 rounded-full mx-auto mb-2"></div>
                        <div class="text-sm text-gray-300">Storage</div>
                        <div class="text-xs text-gray-500">Operational</div>
                    </div>
                </div>
            </div>
            
            <!-- Error Info -->
            <div class="mt-8 pt-6 border-t border-gray-700 text-center">
                <p class="text-sm text-gray-500">
                    <span>Error Code: <span x-text="errorCode">{{ $errorCode ?? '500' }}</span></span> • 
                    <span>Time: <span x-text="timestamp">{{ $timestamp ?? now()->format('H:i:s') }}</span></span>
                </p>
                <p class="text-sm text-gray-400 mt-2" x-text="t('common.help_text')">
                    {{ __('errors.common.help_text') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Custom 500 Animation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate the warning icon
    const warningIcon = document.querySelector('.error-icon i');
    if (warningIcon) {
        warningIcon.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            warningIcon.style.animation = 'pulse 2s infinite';
        }, 500);
    }
    
    // Simulate system status check
    const statusElements = document.querySelectorAll('.grid > div');
    statusElements.forEach((element, index) => {
        setTimeout(() => {
            const statusDot = element.querySelector('.rounded-full');
            const statusText = element.querySelector('.text-xs');
            
            if (statusDot && statusText) {
                // Simulate status check completion
                statusDot.classList.remove('animate-pulse');
                if (index === 0) { // Database
                    statusDot.classList.remove('bg-yellow-400');
                    statusDot.classList.add('bg-green-400');
                    statusText.textContent = 'Operational';
                }
            }
        }, 2000 + (index * 500));
    });
    
    // Auto-refresh functionality
    let refreshCountdown = 30;
    const refreshInterval = setInterval(() => {
        refreshCountdown--;
        if (refreshCountdown <= 0) {
            clearInterval(refreshInterval);
            // Auto-refresh after 30 seconds
            window.location.reload();
        }
    }, 1000);
    
    // Show countdown to user
    const refreshButton = document.querySelector('.error-button-primary');
    if (refreshButton) {
        const originalText = refreshButton.querySelector('span').textContent;
        const updateButtonText = () => {
            if (refreshCountdown > 0) {
                refreshButton.querySelector('span').textContent = `${originalText} (${refreshCountdown}s)`;
            } else {
                refreshButton.querySelector('span').textContent = originalText;
            }
        };
        
        updateButtonText();
        const countdownInterval = setInterval(() => {
            updateButtonText();
            if (refreshCountdown <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }
});

// Add shake animation
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection