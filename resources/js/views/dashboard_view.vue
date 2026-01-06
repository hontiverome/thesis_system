<template>
  <div v-if="!isAuthenticated" class="auth-required">
    <div class="auth-message">
      <h2>Authentication Required</h2>
      <p>Please log in to view the dashboard.</p>
      <router-link to="/login" class="btn btn-primary">Log In</router-link>
    </div>
  </div>
  
  <div v-else class="page-view">
    <header class="page-header">
      <h1 id="page-title" class="page-title">Dashboard</h1>
      <p class="page-subtitle">
        Welcome back, {{ user?.firstName || 'User' }}.
        <span v-if="isAdmin" style="color:red; font-weight:bold; margin-left:8px;">(ADMIN MODE)</span>
      </p>
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

<script setup>
// 1. IMPORT shallowRef HERE (Critical Fix)
import { ref, shallowRef, onMounted, onBeforeUnmount, watch, nextTick } from 'vue';
import { Chart, registerables } from 'chart.js';
import { useLayoutStore } from '@/stores/layout.js';
import { useThemeStore } from '@/stores/theme.js';
import { useAuth } from '@/composables/useAuth';

// Register Chart.js components
Chart.register(...registerables);
Chart.defaults.animation = false;
Chart.defaults.animations = { duration: 0 };

const themeStore = useThemeStore();
const layoutStore = useLayoutStore();

// 2. USE AUTH (Logic Fix)
const { user, isAuthenticated, isAdmin } = useAuth();

// 3. USE shallowRef FOR CHARTS (Crash Fix)
const lineChart = shallowRef(null);
const pieChart = shallowRef(null);
let lineObserver = null;
let pieObserver = null;

// Get chart colors from CSS variables
const getThemeColors = () => {
  const style = getComputedStyle(document.documentElement);
  return {
    primary: style.getPropertyValue('--chart-primary').trim() || style.getPropertyValue('--primary-color').trim(),
    secondary: style.getPropertyValue('--chart-secondary').trim() || style.getPropertyValue('--secondary-color') || '#4CAF50',
    accent: style.getPropertyValue('--chart-accent').trim() || style.getPropertyValue('--accent-color') || '#FFC107',
    text: style.getPropertyValue('--text-color').trim(),
    border: style.getPropertyValue('--bg-color').trim() || '#ddd'
  };
};

// Helper function to convert hex to rgba
const hexToRgba = (hex, alpha = 1) => {
  if (!hex) return `rgba(0, 0, 0, ${alpha})`;
  hex = hex.replace('#', '');
  const r = parseInt(hex.substring(0, 2), 16);
  const g = parseInt(hex.substring(2, 4), 16);
  const b = parseInt(hex.substring(4, 6), 16);
  return `rgba(${r}, ${g}, ${b}, ${alpha})`;
};

// Convert rgb/rgba strings to rgba with specified alpha
const rgbToRgba = (rgb, alpha = 1) => {
  if (!rgb) return `rgba(0, 0, 0, ${alpha})`;
  try {
    if (rgb.startsWith('rgba')) {
      const nums = rgb.match(/rgba\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*([0-9.]+)\s*\)/i);
      if (nums) {
        const [, r, g, b] = nums;
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
      }
    }
    if (rgb.startsWith('rgb')) {
      const nums = rgb.match(/rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/i);
      if (nums) {
        const [, r, g, b] = nums;
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
      }
    }
  } catch (_) {}
  return rgb;
};

// Uniform helper to get a transparent version of any color string
const toTransparent = (color, alpha = 0.1) => {
  if (!color) return `rgba(0, 0, 0, ${alpha})`;
  if (color.startsWith('#')) return hexToRgba(color, alpha);
  if (color.startsWith('rgb')) return rgbToRgba(color, alpha);
  return color;
};

// Update chart data with current theme colors
const getChartData = () => {
  const colors = getThemeColors();
  const primaryRgba = colors.primary.startsWith('#') ? hexToRgba(colors.primary, 1) : rgbToRgba(colors.primary, 1);
  const secondaryRgba = colors.secondary.startsWith('#') ? hexToRgba(colors.secondary, 1) : rgbToRgba(colors.secondary, 1);
  const accentRgba = colors.accent.startsWith('#') ? hexToRgba(colors.accent, 1) : rgbToRgba(colors.accent, 1);
  const textColor = colors.text.startsWith('#') ? hexToRgba(colors.text, 1) : rgbToRgba(colors.text, 1);
  const borderNeutral = colors.border.startsWith('#') ? hexToRgba(colors.border, 1) : rgbToRgba(colors.border, 1);

  return {
    line: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [
        {
          label: 'Min',
          data: [65, 59, 80, 81, 56, 55, 40, 45, 50, 60, 65, 70],
          borderColor: primaryRgba,
          backgroundColor: toTransparent(primaryRgba, 0.1),
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
          backgroundColor: toTransparent(secondaryRgba, 0.1),
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
          backgroundColor: toTransparent(accentRgba, 0.1),
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
    pie: {
      labels: ['Category A', 'Category B', 'Category C'],
      datasets: [
        {
          data: [30, 50, 20],
          backgroundColor: [primaryRgba, secondaryRgba, accentRgba],
          borderColor: borderNeutral,
          borderWidth: 1
        }
      ]
    }
  };
};

const chartOptions = (theme) => {
  const isDark = theme === 'dark' || theme === 'night';
  const textColor = isDark ? '#fff' : '#666';
  const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
  const isSmall = window.innerWidth <= 640;
  
  return {
    line: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { 
        legend: { 
          position: isSmall ? 'bottom' : 'top', 
          labels: { color: textColor, usePointStyle: true, padding: isSmall ? 12 : 20, font: { size: isSmall ? 10 : 12, weight: '500' } } 
        },
        tooltip: {
          backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.9)',
          titleColor: textColor, bodyColor: textColor, borderColor: isDark ? '#555' : '#ddd', borderWidth: 1, cornerRadius: 6, displayColors: true, intersect: false, mode: 'index',
          callbacks: { label: function(context) { return `${context.dataset.label}: ${context.parsed.y}`; } }
        }
      },
      scales: {
        x: { grid: { color: gridColor, borderColor: gridColor }, ticks: { color: textColor, font: { size: isSmall ? 10 : 11 } } },
        y: { grid: { color: gridColor, borderColor: gridColor }, ticks: { color: textColor, font: { size: isSmall ? 10 : 11 } } }
      },
      interaction: { intersect: false, mode: 'index' },
      elements: { line: { borderJoinStyle: 'round', borderCapStyle: 'round' }, point: { hoverBorderWidth: 3, hoverRadius: 6 } }
    },
    pie: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { 
        legend: { position: isSmall ? 'bottom' : 'right', labels: { color: textColor, usePointStyle: true, padding: isSmall ? 10 : 20, font: { size: isSmall ? 10 : 12 } } },
        tooltip: {
          backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.9)',
          titleColor: textColor, bodyColor: textColor, borderColor: isDark ? '#555' : '#ddd', borderWidth: 1, cornerRadius: 6, displayColors: true,
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
      interaction: { intersect: false, mode: 'nearest' },
      elements: { arc: { borderAlign: 'inner', borderJoinStyle: 'round', borderWidth: 2, offset: 0, spacing: 0 } },
      layout: { padding: isSmall ? 0 : 0 }
    }
  };
};

const updateChart = (chart, data, options) => {
  if (!chart) return false;
  try {
    chart.stop && chart.stop();
    chart.data = data;
    chart.options = options;
    chart.update('none');
    return true;
  } catch (error) {
    console.error('Error updating chart:', error);
    return false;
  }
};

const updateCharts = () => {
  if (!lineChart.value || !pieChart.value) {
    createCharts();
    return;
  }
  const theme = themeStore.currentTheme;
  const data = getChartData();
  const options = chartOptions(theme);
  
  const lineUpdated = updateChart(lineChart.value, data.line, options.line);
  const pieUpdated = updateChart(pieChart.value, data.pie, options.pie);
  
  if (!lineUpdated || !pieUpdated) {
    createCharts();
  }
};

let creating = false;
const createCharts = () => {
  try {
    if (creating) return; 
    creating = true;
    const lineCtx = document.getElementById('lineChart');
    const pieCtx = document.getElementById('pieChart');
    if (!lineCtx || !pieCtx) { creating = false; return; }
    
    const line2d = lineCtx.getContext('2d');
    const pie2d = pieCtx.getContext('2d');
    
    if (!line2d || !pie2d) {
      creating = false;
      setTimeout(ensureReadyAndCreate, 100);
      return;
    }
    
    const theme = themeStore.currentTheme;
    const data = getChartData();
    const options = chartOptions(theme);
    
    [lineChart.value, pieChart.value].forEach(chart => {
      try { chart?.stop && chart.stop(); } catch (_) {}
      try { chart?.destroy && chart.destroy(); } catch (_) {}
    });
    
    // Create new charts (Saved to shallowRef)
    lineChart.value = new Chart(line2d, { type: 'line', data: data.line, options: options.line });
    pieChart.value = new Chart(pie2d, { type: 'pie', data: data.pie, options: options.pie });
    
  } catch (error) {
    console.error('Error creating charts:', error);
  } finally {
    creating = false;
  }
};

let createTries = 0;
let ensureScheduled = false;
const ensureReadyAndCreate = () => {
  if (ensureScheduled) return;
  ensureScheduled = true;
  const run = () => {
    ensureScheduled = false;
    if (lineChart.value && pieChart.value) { updateCharts(); return; }
    const lineCanvas = document.getElementById('lineChart');
    const pieCanvas = document.getElementById('pieChart');
    if (!lineCanvas || !pieCanvas) {
        if (createTries < 20) { createTries++; setTimeout(ensureReadyAndCreate, 100); }
        return;
    }
    createTries = 0;
    createCharts();
  };
  requestAnimationFrame(run);
};

watch(() => themeStore.currentTheme, () => {
  setTimeout(() => {
    if (!lineChart.value || !pieChart.value) { ensureReadyAndCreate(); } else { updateCharts(); }
  }, 50);
}, { immediate: false });

const handleResize = () => { setTimeout(updateCharts, 100); };

onMounted(() => {
  nextTick(() => setTimeout(ensureReadyAndCreate, 50));
  window.addEventListener('resize', handleResize, { passive: true });
});

watch(() => layoutStore.isMobileSidebarOpen, () => {
  setTimeout(ensureReadyAndCreate, 150);
});

onBeforeUnmount(() => {
  [lineChart.value, pieChart.value].forEach(chart => { if (chart) chart.destroy(); });
  lineChart.value = null;
  pieChart.value = null;
  window.removeEventListener('resize', handleResize);
});
</script>