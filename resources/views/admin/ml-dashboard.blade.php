<x-layouts.app title="ML Model Dashboard">
    <livewire:admin.m-l-model-dashboard />

    @push('styles')
    <style>
    /* Custom styles for ML Dashboard */
    .metric-card {
        transition: transform 0.2s ease-in-out;
    }
    
    .metric-card:hover {
        transform: translateY(-2px);
    }
    
    .performance-indicator {
        position: relative;
        overflow: hidden;
    }
    
    .performance-indicator::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .performance-indicator:hover::before {
        left: 100%;
    }
    
    .prediction-table {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .status-indicator {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    /* Loading spinner for API calls */
    .loading-spinner {
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto refresh dashboard data every 5 minutes
    setInterval(function() {
        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('refreshData');
        }
    }, 300000); // 5 minutes

    // Chart.js integration for performance visualization (if needed)
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any additional charts or visualizations here
        console.log('ML Dashboard loaded');
    });
</script>
@endpush
</x-layouts.app>