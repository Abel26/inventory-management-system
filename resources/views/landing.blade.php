@extends('layouts.landing')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content animate-fade-in-up">
        <h1 class="hero-title" x-text="t('hero.title')">
            {{ __('landing.hero.title') }}
        </h1>
        <p class="hero-subtitle" x-text="t('hero.subtitle')">
            {{ __('landing.hero.subtitle') }}
        </p>
        <div class="cta-buttons animate-fade-in-up" style="animation-delay: 0.6s;">
            <a href="{{ route('login') }}" class="cta-button">
                <span x-text="t('hero.cta_button')">{{ __('landing.hero.cta_button') }}</span>
            </a>
        </div>
        
        <!-- Quick Asset Check Search Bar -->
        <div class="search-container animate-fade-in-up" style="animation-delay: 0.8s;" x-data="{ searchOpen: false }" @click.outside="closeSearch()">
            <div class="search-wrapper" :class="{ 'focus-within': searchOpen }">
                <input 
                    type="text" 
                    class="search-input" 
                    :placeholder="t('search.placeholder')"
                    x-model="searchQuery"
                    @focus="searchOpen = true"
                    @keyup.enter="performSearch()"
                    @input="searchQuery.length >= 2 ? performSearch() : ''"
                >
                <button class="search-button" @click="performSearch()" :disabled="searchLoading">
                    <i class="ph ph-magnifying-glass" x-show="!searchLoading"></i>
                    <i class="ph ph-spinner" x-show="searchLoading"></i>
                </button>
            </div>
            
            <!-- Search Results Dropdown -->
            <div class="search-results" x-show="showSearchResults" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
                <!-- Search Results Header -->
                <div class="search-results-header" x-show="searchResults.length > 0">
                    <span x-text="t('search.results_title')">Search Results</span>
                    <span class="search-results-count" x-text="searchResults.length"></span>
                </div>
                
                <!-- Search Results List -->
                <template x-for="result in searchResults" :key="result.code">
                    <div class="search-result-item">
                        <div class="search-result-image">
                            <template x-if="result.image">
                                <img :src="result.image" :alt="result.name" onerror="this.style.display='flex'; this.innerHTML='<i class=\\'ph ph-package\\'></i>'">
                            </template>
                            <template x-if="!result.image">
                                <i class="ph ph-package"></i>
                            </template>
                        </div>
                        <div class="search-result-info">
                            <div class="search-result-name" x-text="result.name"></div>
                            <div class="search-result-meta">
                                <span class="search-result-type" x-text="result.type"></span>
                                <span class="search-result-code" x-text="result.code"></span>
                                <span class="search-result-status" :class="'status-' + result.status_color" x-text="t('search.' + result.status)"></span>
                            </div>
                        </div>
                    </div>
                </template>
                
                <!-- No Results -->
                <div class="search-no-results" x-show="searchResults.length === 0 && searchQuery.length >= 2">
                    <span x-text="t('search.no_results')">{{ __('landing.search.no_results') }}</span>
                </div>
                
                <!-- Loading -->
                <div class="search-loading" x-show="searchLoading">
                    <i class="ph ph-spinner"></i>
                    <span>Searching...</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Impact Metrics Section -->
<section class="metrics-section">
    <div class="metrics-grid">
        <div class="metric-card animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="metric-number" data-suffix="+">{{ $stats['materials'] + $stats['tools'] + $stats['models'] }}</div>
            <div class="metric-label" x-text="t('metrics.managed_assets')">
                {{ __('landing.metrics.managed_assets') }}
            </div>
        </div>
        
        <div class="metric-card animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="metric-number" data-suffix="%">0</div>
            <div class="metric-label" x-text="t('metrics.production_delay')">
                {{ __('landing.metrics.production_delay') }}
            </div>
        </div>
        
        <div class="metric-card animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="metric-number" data-suffix="%">100</div>
            <div class="metric-label" x-text="t('metrics.on_time_schedule')">
                {{ __('landing.metrics.on_time_schedule') }}
            </div>
        </div>
    </div>
</section>

<!-- Feature Cards Section -->
<section class="features-section">
    <div class="features-grid">
        <!-- Early Warning System Card -->
        <div class="feature-card animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="feature-icon">
                <i class="ph ph-bell-ringing"></i>
            </div>
            <h3 class="feature-title" x-text="t('features.early_warning.title')">
                {{ __('landing.features.early_warning.title') }}
            </h3>
            <p class="feature-description" x-text="t('features.early_warning.text')">
                {{ __('landing.features.early_warning.text') }}
            </p>
        </div>
        
        <!-- Asset Tracking Card -->
        <div class="feature-card animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="feature-icon">
                <i class="ph ph-package"></i>
            </div>
            <h3 class="feature-title" x-text="t('features.asset_tracking.title')">
                {{ __('landing.features.asset_tracking.title') }}
            </h3>
            <p class="feature-description" x-text="t('features.asset_tracking.text')">
                {{ __('landing.features.asset_tracking.text') }}
            </p>
        </div>
        
        <!-- Data Insights Card -->
        <div class="feature-card animate-fade-in-up" style="animation-delay: 0.6s;">
            <div class="feature-icon">
                <i class="ph ph-chart-line-up"></i>
            </div>
            <h3 class="feature-title" x-text="t('features.data_insights.title')">
                {{ __('landing.features.data_insights.title') }}
            </h3>
            <p class="feature-description" x-text="t('features.data_insights.text')">
                {{ __('landing.features.data_insights.text') }}
            </p>
        </div>
    </div>
</section>

<!-- System Workflow Section -->
<section class="workflow-section">
    <div class="workflow-container">
        <h2 class="workflow-title animate-fade-in-up" x-text="t('workflow.title')">
            {{ __('landing.workflow.title') }}
        </h2>
        
        <div class="workflow-steps">
            <!-- Step 1 -->
            <div class="workflow-step animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="step-number">1</div>
                <h3 class="step-title" x-text="t('workflow.step1_title')">
                    {{ __('landing.workflow.step1_title') }}
                </h3>
                <p class="step-description" x-text="t('workflow.step1_desc')">
                    {{ __('landing.workflow.step1_desc') }}
                </p>
            </div>
            
            <!-- Step 2 -->
            <div class="workflow-step animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="step-number">2</div>
                <h3 class="step-title" x-text="t('workflow.step2_title')">
                    {{ __('landing.workflow.step2_title') }}
                </h3>
                <p class="step-description" x-text="t('workflow.step2_desc')">
                    {{ __('landing.workflow.step2_desc') }}
                </p>
            </div>
            
            <!-- Step 3 -->
            <div class="workflow-step animate-fade-in-up" style="animation-delay: 0.6s;">
                <div class="step-number">3</div>
                <h3 class="step-title" x-text="t('workflow.step3_title')">
                    {{ __('landing.workflow.step3_title') }}
                </h3>
                <p class="step-description" x-text="t('workflow.step3_desc')">
                    {{ __('landing.workflow.step3_desc') }}
                </p>
            </div>
        </div>
    </div>
</section>



<!-- Quote Section -->
<section class="quote-section">
    <div class="quote-content animate-fade-in-up">
        <blockquote class="quote-text" x-text="t('quote')">
            {{ __('landing.quote') }}
        </blockquote>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="faq-container">
        <h2 class="faq-title animate-fade-in-up" x-text="t('faq.title')">
            {{ __('landing.faq.title') }}
        </h2>
        
        <!-- FAQ Item 1 -->
        <div class="faq-item animate-fade-in-up" style="animation-delay: 0.2s;" :class="{ 'active': activeFaq === 0 }">
            <button class="faq-question" @click="toggleFaq(0)">
                <span x-text="t('faq.q1_question')">
                    {{ __('landing.faq.q1_question') }}
                </span>
                <i class="ph ph-caret-down faq-icon"></i>
            </button>
            <div class="faq-answer" x-show="activeFaq === 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="max-h-0" x-transition:enter-end="max-h-96" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="max-h-96" x-transition:leave-end="max-h-0">
                <div class="faq-answer-content" x-text="t('faq.q1_answer')">
                    {{ __('landing.faq.q1_answer') }}
                </div>
            </div>
        </div>
        
        <!-- FAQ Item 2 -->
        <div class="faq-item animate-fade-in-up" style="animation-delay: 0.3s;" :class="{ 'active': activeFaq === 1 }">
            <button class="faq-question" @click="toggleFaq(1)">
                <span x-text="t('faq.q2_question')">
                    {{ __('landing.faq.q2_question') }}
                </span>
                <i class="ph ph-caret-down faq-icon"></i>
            </button>
            <div class="faq-answer" x-show="activeFaq === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="max-h-0" x-transition:enter-end="max-h-96" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="max-h-96" x-transition:leave-end="max-h-0">
                <div class="faq-answer-content" x-text="t('faq.q2_answer')">
                    {{ __('landing.faq.q2_answer') }}
                </div>
            </div>
        </div>
        
        <!-- FAQ Item 3 -->
        <div class="faq-item animate-fade-in-up" style="animation-delay: 0.4s;" :class="{ 'active': activeFaq === 2 }">
            <button class="faq-question" @click="toggleFaq(2)">
                <span x-text="t('faq.q3_question')">
                    {{ __('landing.faq.q3_question') }}
                </span>
                <i class="ph ph-caret-down faq-icon"></i>
            </button>
            <div class="faq-answer" x-show="activeFaq === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="max-h-0" x-transition:enter-end="max-h-96" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="max-h-96" x-transition:leave-end="max-h-0">
                <div class="faq-answer-content" x-text="t('faq.q3_answer')">
                    {{ __('landing.faq.q3_answer') }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Creator Profile Section -->
<section class="creator-section">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12" x-text="t('creator.title')">
            {{ __('landing.creator.title') }}
        </h2>
        
        <div class="creator-card animate-fade-in-up">
            <div class="creator-image-container">
                <img src="{{ asset('assets/img/ivan.jpeg') }}" alt="Ivan Fadillah" class="creator-image">
            </div>
            
            <div class="creator-info">
                <h3 x-text="t('creator.name')">{{ __('landing.creator.name') }}</h3>
                <p class="title" x-text="t('creator.role')">{{ __('landing.creator.role') }}</p>
                <p class="narrative" x-text="t('creator.narrative')">
                    {{ __('landing.creator.narrative') }}
                </p>
            </div>
        </div>
    </div>
</section>
@endsection