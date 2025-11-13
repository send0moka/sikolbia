// Enhanced Prediksi NBM with Charts and Export
// Chart instances (global untuk destroy on re-render)
let trendChartInstance = null;
let confidenceChartInstance = null;
let comparisonChartInstance = null;

// Store current prediction data for export
window.currentPredictionData = null;

/**
 * Render interactive charts for prediction results
 */
function renderPredictionCharts(data) {
    // Show chart section
    const chartSection = document.getElementById('chartSection');
    if (chartSection) {
        chartSection.style.display = 'block';
    }
    
    // Prepare data
    const historical = data.historical || [];
    const predictions = data.prediction || [];
    const ciArray = data.confidence_intervals || [];
    
    // Reverse historical data (oldest first)
    const historicalReversed = [...historical].reverse();
    
    // Labels: historical months + predicted months
    const labels = [];
    historicalReversed.forEach(item => {
        labels.push(`${item.tahun}-${String(item.bulan).padStart(2, '0')}`);
    });
    
    // Add prediction periods
    const lastHistorical = historical[0];
    predictions.forEach((_, idx) => {
        if (lastHistorical) {
            const futureMonth = (parseInt(lastHistorical.bulan) + idx + 1);
            const futureYear = parseInt(lastHistorical.tahun) + Math.floor((futureMonth - 1) / 12);
            const month = ((futureMonth - 1) % 12) + 1;
            labels.push(`${futureYear}-${String(month).padStart(2, '0')}`);
        }
    });
    
    // Historical values
    const historicalValues = historicalReversed.map(item => item.kalori_hari);
    
    // For smooth transition: add last historical point to prediction array
    // This creates a continuous line from historical to prediction
    const lastHistoricalValue = historicalValues[historicalValues.length - 1];
    
    // Prediction values: start with last historical value for continuity
    const predictionValues = new Array(historicalValues.length - 1).fill(null)
        .concat([lastHistoricalValue]) // Transition point
        .concat(predictions);
    
    // Confidence intervals (also include transition point)
    const lowerBounds = new Array(historicalValues.length).fill(null);
    const upperBounds = new Array(historicalValues.length).fill(null);
    ciArray.forEach((ci, idx) => {
        lowerBounds.push(ci.lower_bound);
        upperBounds.push(ci.upper_bound);
    });
    
    // Destroy previous charts
    if (trendChartInstance) trendChartInstance.destroy();
    if (confidenceChartInstance) confidenceChartInstance.destroy();
    if (comparisonChartInstance) comparisonChartInstance.destroy();
    
    // 1. Trend Chart (Historical + Prediction Continuous)
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        // Custom plugin to draw vertical separator
        const verticalLinePlugin = {
            id: 'verticalLine',
            afterDatasetsDraw: function(chart) {
                const ctx = chart.ctx;
                const xAxis = chart.scales.x;
                const yAxis = chart.scales.y;
                
                // Draw line at transition point (between last historical and first prediction)
                const xPosition = xAxis.getPixelForValue(historicalValues.length - 1);
                
                ctx.save();
                ctx.beginPath();
                ctx.moveTo(xPosition, yAxis.top);
                ctx.lineTo(xPosition, yAxis.bottom);
                ctx.lineWidth = 2;
                ctx.strokeStyle = 'rgba(139, 92, 246, 0.6)';
                ctx.setLineDash([8, 4]);
                ctx.stroke();
                ctx.restore();
                
                // Draw label
                ctx.save();
                ctx.font = 'bold 11px sans-serif';
                ctx.fillStyle = 'rgba(139, 92, 246, 0.9)';
                ctx.textAlign = 'center';
                ctx.fillText('Transisi', xPosition, yAxis.top - 5);
                ctx.restore();
            }
        };
        
        trendChartInstance = new Chart(trendCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Data Historis',
                    data: historicalValues.concat(new Array(predictions.length).fill(null)),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                    tension: 0.3,
                    fill: false
                }, {
                    label: 'Prediksi',
                    data: predictionValues,
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 3,
                    borderDash: [8, 4],
                    pointRadius: 5,
                    pointBackgroundColor: 'rgb(239, 68, 68)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                    pointStyle: 'circle',
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Trend Konsumsi Kalori: Historis → Prediksi',
                        font: {
                            size: 14,
                            weight: 'bold'
                        },
                        padding: 10
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y.toFixed(2) + ' kal/hari';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Kalori per Hari',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Periode (Tahun-Bulan)',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            },
            plugins: [verticalLinePlugin]
        });
    }
    
    // 2. Confidence Interval Chart
    const confidenceCtx = document.getElementById('confidenceChart');
    if (confidenceCtx) {
        confidenceChartInstance = new Chart(confidenceCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Prediksi',
                    data: predictionValues,
                    borderColor: 'rgb(249, 115, 22)',
                    backgroundColor: 'rgba(249, 115, 22, 0.3)',
                    borderWidth: 2,
                    pointRadius: 4,
                    fill: false
                }, {
                    label: 'Batas Atas',
                    data: upperBounds,
                    borderColor: 'rgba(220, 38, 38, 0.5)',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    borderWidth: 1,
                    borderDash: [3, 3],
                    pointRadius: 2,
                    fill: '+1'
                }, {
                    label: 'Batas Bawah',
                    data: lowerBounds,
                    borderColor: 'rgba(34, 197, 94, 0.5)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 1,
                    borderDash: [3, 3],
                    pointRadius: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Confidence Interval (±CI)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Kalori/Hari'
                        }
                    }
                }
            }
        });
    }
    
    // 3. Bar Comparison Chart
    const comparisonCtx = document.getElementById('comparisonChart');
    if (comparisonCtx) {
        comparisonChartInstance = new Chart(comparisonCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Historis',
                    data: historicalValues.concat(new Array(predictions.length).fill(null)),
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }, {
                    label: 'Prediksi',
                    data: predictionValues,
                    backgroundColor: 'rgba(249, 115, 22, 0.6)',
                    borderColor: 'rgb(249, 115, 22)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Perbandingan Historis vs Prediksi'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Kalori/Hari'
                        }
                    }
                }
            }
        });
    }
}

/**
 * Setup export button handlers
 */
function setupExportHandlers(exportExcelRoute, exportPdfRoute) {
    const exportExcelBtn = document.getElementById('exportExcelBtn');
    const exportPdfBtn = document.getElementById('exportPdfBtn');
    const kelompokSelect = document.getElementById('kelompokSelect');
    const komoditiSelect = document.getElementById('komoditiSelect');
    const bulanPrediksi = document.getElementById('bulanPrediksi');
    
    if (!exportExcelBtn || !exportPdfBtn) return;
    
    // Export Excel handler
    exportExcelBtn.addEventListener('click', function() {
        if (!window.currentPredictionData) {
            alert('Tidak ada data untuk di-export');
            return;
        }
        
        const kelompok = kelompokSelect.value;
        const komoditi = komoditiSelect.value;
        const bulan = bulanPrediksi.value;
        
        // Show loading
        const originalText = this.innerHTML;
        this.innerHTML = '<svg class="animate-spin h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Export...';
        this.disabled = true;
        
        // Trigger Excel export
        window.location.href = `${exportExcelRoute}?kelompok=${kelompok}&komoditi=${komoditi}&bulan=${bulan}`;
        
        // Reset button after delay
        setTimeout(() => {
            this.innerHTML = originalText;
            this.disabled = false;
        }, 2000);
    });
    
    // Export PDF handler
    exportPdfBtn.addEventListener('click', function() {
        if (!window.currentPredictionData) {
            alert('Tidak ada data untuk di-export');
            return;
        }
        
        const kelompok = kelompokSelect.value;
        const komoditi = komoditiSelect.value;
        const bulan = bulanPrediksi.value;
        
        // Show loading
        const originalText = this.innerHTML;
        this.innerHTML = '<svg class="animate-spin h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Export...';
        this.disabled = true;
        
        // Trigger PDF export
        window.location.href = `${exportPdfRoute}?kelompok=${kelompok}&komoditi=${komoditi}&bulan=${bulan}`;
        
        // Reset button after delay
        setTimeout(() => {
            this.innerHTML = originalText;
            this.disabled = false;
        }, 2000);
    });
}

// Export functions to global scope
window.renderPredictionCharts = renderPredictionCharts;
window.setupExportHandlers = setupExportHandlers;
