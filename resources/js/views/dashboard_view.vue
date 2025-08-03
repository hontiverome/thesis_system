<!---
 * System Name: Theming and UI Framework
 * Module Name: Dashboard
 * Purpose Of this file: 
 * To display the main dashboard view
 * 
 * Author: Jerome Andrei O. Hontiveros
 * Copyright (C) 2025
 * by the Department of Science and Technology — Project LODI
 * All rights reserved.
 * 
 * Permission is hereby granted, free of charge, to any persons obtaining a copy
 * of this software and associated documentation files, to deal in the Software
 * without restriction, including the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, provided that the
 * above copyright notice(s) and this permission notice appears in all copies of
 * the Software and that both the above copyright notice(s) and this permission
 * notice appear in supporting documentation.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS.
 * IN NO EVENT SHALL THE COPYRIGHT HOLDER OR HOLDERS INCLUDED IN THIS NOTICE BE
 * LIABLE FOR ANY CLAIM, OR ANY SPECIAL INDIRECT OR CONSEQUENTIAL DAMAGES, OR ANY
 * DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN
 * CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 * 
 * Except as contained in this notice, the name of a copyright holder shall not
 * be used in advertising or otherwise to promote the sale, use or other dealings
 * in this Software without prior written authorization of the copyright holder.
-->

<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue';
import { Chart, registerables } from 'chart.js';
import { useThemeStore } from '@/stores/theme.js';

// Register Chart.js components
Chart.register(...registerables);

const themeStore = useThemeStore();
const lineChart = ref(null);
const pieChart = ref(null);

// Get chart colors from CSS variables
const getThemeColors = () => {
  const style = getComputedStyle(document.documentElement);
  return {
    primary: style.getPropertyValue('--chart-primary').trim() || style.getPropertyValue('--primary-color').trim(),
    secondary: style.getPropertyValue('--chart-secondary').trim() || style.getPropertyValue('--secondary-color') || '#4CAF50',
    accent: style.getPropertyValue('--chart-accent').trim() || style.getPropertyValue('--accent-color') || '#FFC107',
    text: style.getPropertyValue('--text-color').trim()
  };
};

// Helper function to convert hex to rgba
const hexToRgba = (hex, alpha = 1) => {
  if (!hex) return `rgba(0, 0, 0, ${alpha})`;
  
  hex = hex.replace('#', '');
  
  // Parse r, g, b values
  const r = parseInt(hex.substring(0, 2), 16);
  const g = parseInt(hex.substring(2, 4), 16);
  const b = parseInt(hex.substring(4, 6), 16);
  
  return `rgba(${r}, ${g}, ${b}, ${alpha})`;
};

// Update chart data with current theme colors
const getChartData = () => {
  const colors = getThemeColors();
  
  // Convert colors to rgba format for better compatibility
  const primaryRgba = colors.primary.startsWith('#') ? hexToRgba(colors.primary) : colors.primary;
  const secondaryRgba = colors.secondary.startsWith('#') ? hexToRgba(colors.secondary) : colors.secondary;
  const accentRgba = colors.accent.startsWith('#') ? hexToRgba(colors.accent) : colors.accent;
  const textColor = colors.text.startsWith('#') ? hexToRgba(colors.text) : colors.text;

  // Static values for line chart
  return {
    line: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [
      {
        label: 'Min',
        data: [65, 59, 80, 81, 56, 55, 40, 45, 50, 60, 65, 70],
        borderColor: primaryRgba,
        backgroundColor: primaryRgba.replace('1)', '0.1)'),
        borderWidth: 3,
        tension: 0.4,
        fill: false,
        pointBackgroundColor: primaryRgba,
        pointBorderColor: textColor,
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        pointHoverBackgroundColor: primaryRgba,
        pointHoverBorderColor: '#fff',
        pointHoverBorderWidth: 3,
        hoverBorderWidth: 4
      },
      {
        label: 'Max',
        data: [85, 79, 100, 101, 86, 85, 80, 85, 90, 100, 105, 110],
        borderColor: secondaryRgba,
        backgroundColor: secondaryRgba.replace('1)', '0.1)'),
        borderWidth: 3,
        tension: 0.4,
        fill: false,
        pointBackgroundColor: secondaryRgba,
        pointBorderColor: textColor,
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        pointHoverBackgroundColor: secondaryRgba,
        pointHoverBorderColor: '#fff',
        pointHoverBorderWidth: 3,
        hoverBorderWidth: 4
      },
      {
        label: 'Average',
        data: [75, 69, 90, 91, 71, 70, 60, 65, 70, 80, 85, 90],
        borderColor: accentRgba,
        backgroundColor: accentRgba.replace('1)', '0.1)'),
        borderWidth: 3,
        tension: 0.4,
        fill: false,
        pointBackgroundColor: accentRgba,
        pointBorderColor: textColor,
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6,
        pointHoverBackgroundColor: accentRgba,
        pointHoverBorderColor: '#fff',
        pointHoverBorderWidth: 3,
        hoverBorderWidth: 4
      }
    ]
  },
    // Static values for pie chart
    pie: {
      labels: ['Category A', 'Category B', 'Category C'],
      datasets: [
        {
          data: [30, 50, 20],
          backgroundColor: [
            primaryRgba.replace('1)', '0.2)'),
            secondaryRgba.replace('1)', '0.2)'),
            accentRgba.replace('1)', '0.2)')
          ],
          borderColor: [
            primaryRgba,
            secondaryRgba,
            accentRgba
          ],
          borderWidth: 2
        }
      ]
    }
  };
};

// Chart configuration options
const chartOptions = (theme) => {
  const isDark = theme === 'dark' || theme === 'night';
  const textColor = isDark ? '#fff' : '#666';
  const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
  
  return {
    line: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { 
        legend: { 
          position: 'top', 
          labels: { 
            color: textColor,
            usePointStyle: true,
            padding: 20,
            font: {
              size: 12,
              weight: '500'
            }
          } 
        },
        tooltip: {
          backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.9)',
          titleColor: textColor,
          bodyColor: textColor,
          borderColor: isDark ? '#555' : '#ddd',
          borderWidth: 1,
          cornerRadius: 6,
          displayColors: true,
          intersect: false,
          mode: 'index',
          callbacks: {
            label: function(context) {
              return `${context.dataset.label}: ${context.parsed.y}`;
            }
          }
        }
      },
      scales: {
        x: { 
          grid: { 
            color: gridColor,
            borderColor: gridColor
          }, 
          ticks: { 
            color: textColor,
            font: {
              size: 11
            }
          } 
        },
        y: { 
          grid: { 
            color: gridColor,
            borderColor: gridColor
          }, 
          ticks: { 
            color: textColor,
            font: {
              size: 11
            }
          } 
        }
      },
      // Enhanced animations for line chart
      animation: {
        duration: 1500,
        easing: 'easeOutQuart'
      },
      // Smooth hover animations
      hover: {
        animationDuration: 200,
        intersect: false,
        mode: 'nearest'
      },
      // Enhanced interaction
      interaction: {
        intersect: false,
        mode: 'index'
      },
      // Element styling for better visual effects
      elements: {
        line: {
          borderJoinStyle: 'round',
          borderCapStyle: 'round'
        },
        point: {
          hoverBorderWidth: 3,
          hoverRadius: 6
        }
      }
    },
    pie: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { 
        legend: { 
          position: 'right', 
          labels: { 
            color: textColor,
            usePointStyle: true,  // Use point style for better visual consistency
            padding: 20
          } 
        },
        tooltip: {
          backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.9)',
          titleColor: textColor,
          bodyColor: textColor,
          borderColor: isDark ? '#555' : '#ddd',
          borderWidth: 1,
          cornerRadius: 6,
          displayColors: true,
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.parsed;
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = ((value / total) * 100).toFixed(1);
              return `${label}: ${value} (${percentage}%)`;
            }
          }
        }
      },
      // Enhanced interaction options
      interaction: {
        intersect: false,
        mode: 'nearest'
      },
      // Smooth animations
      animation: {
        animateRotate: true,
        animateScale: true,
        duration: 800,
        easing: 'easeOutQuart'
      },
      // Hover animations
      hover: {
        animationDuration: 200
      },
      // Element options for consistent behavior - fix layering issues
      elements: {
        arc: {
          borderAlign: 'center',
          borderJoinStyle: 'round',
          borderWidth: 2,        
          offset: 0,            
          spacing: 0              
        }
      },
      // Override any default offsets that might affect Category C
      layout: {
        padding: 0
      }
    }
  };
};

// Update chart data and options
const updateChart = (chart, data, options) => {
  if (!chart) return false;
  
  try {
    chart.data = data;
    chart.options = { ...options, animation: { duration: 1 } };
    chart.update('none');
    return true;
  } catch (error) {
    console.error('Error updating chart:', error);
    return false;
  }
};

// Update all charts with current theme
const updateCharts = () => {
  if (!lineChart.value || !pieChart.value) {
    createCharts();
    return;
  }
  
  // Get fresh data with new theme colors
  const theme = themeStore.currentTheme;
  const data = getChartData();
  const options = chartOptions(theme);
  
  const lineUpdated = updateChart(lineChart.value, data.line, options.line);
  const pieUpdated = updateChart(pieChart.value, data.pie, options.pie);
  
  if (!lineUpdated || !pieUpdated) {
    createCharts();
  }
};

/**
 * Creates or recreates the charts based on the current theme
 */
const createCharts = () => {
  try {
    const lineCtx = document.getElementById('lineChart');
    const pieCtx = document.getElementById('pieChart');
    const theme = themeStore.currentTheme;
    const data = getChartData();
    const options = chartOptions(theme);
    
    // Clean up existing charts before creating new ones
    [lineChart.value, pieChart.value].forEach(chart => chart?.destroy());
    
    // Create new charts
    lineChart.value = new Chart(lineCtx, {
      type: 'line',
      data: data.line,
      options: options.line
    });
    
    pieChart.value = new Chart(pieCtx, {
      type: 'pie',
      data: data.pie,
      options: options.pie
    });
    
  } catch (error) {
    console.error('Error creating charts:', error);
  }
};

/**
 * Main Chart Updater
 * Watches for theme changes and updates the charts accordingly
 */
watch(() => themeStore.currentTheme, () => {
  setTimeout(updateCharts, 50);
}, { immediate: true });

/**
 * Initializes the charts and sets up cleanup on component unmount
 */
onMounted(() => {
  nextTick(() => setTimeout(createCharts, 100));
});

/**
 * Cleans up the charts on component unmount
 */
onBeforeUnmount(() => {
  [lineChart.value, pieChart.value].forEach(chart => {
    if (chart) {
      chart.destroy();
    }
  });
  lineChart.value = null;
  pieChart.value = null;
});
</script>

<!-- Main Page Template -->
<template>
  <div class="dashboard">
    <header class="dashboard-header">
      <h1 id="dashboard-title">Dashboard</h1>
      <p class="subtitle">Welcome to your personalized dashboard</p>
    </header>
    <div class="dashboard-content">
      <div class="chart-container">
        <div class="chart-card">
          <h3>Monthly Performance</h3>
          <div class="chart-wrapper">
            <canvas id="lineChart"></canvas>
          </div>
        </div>
        <div class="chart-card">
          <h3>Category Distribution</h3>
          <div class="chart-wrapper">
            <canvas id="pieChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
