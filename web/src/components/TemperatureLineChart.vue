<template>
  <div class="relative w-full bg-white border border-gray-200 rounded-xl shadow-sm p-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-3">
      <div>
        <h3 class="text-sm font-semibold text-gray-800">Temperature chart</h3>
        <p class="text-xs text-gray-500">Every 30 minutes throughout the day</p>
      </div>
    </div>

    <!-- Chart Canvas -->
    <div class="h-64">
      <canvas ref="canvasRef"></canvas>
    </div>

    <!-- Top-right summary boxes -->
    <div class="absolute top-3 right-3 space-y-2">
      <div class="min-w-[160px] px-3 py-2 rounded-lg border border-red-200 bg-red-50 text-red-700 shadow-sm">
        <div class="text-[10px] font-medium uppercase tracking-wide opacity-80">Highest</div>
        <div class="flex items-baseline gap-2">
          <div class="text-lg font-bold">{{ maxTemp.value.toFixed(1) }}°C</div>
          <div class="text-[11px] text-red-600/80">{{ maxTemp.time }}</div>
        </div>
      </div>
      <div class="min-w-[160px] px-3 py-2 rounded-lg border border-blue-200 bg-blue-50 text-blue-700 shadow-sm">
        <div class="text-[10px] font-medium uppercase tracking-wide opacity-80">Lowest</div>
        <div class="flex items-baseline gap-2">
          <div class="text-lg font-bold">{{ minTemp.value.toFixed(1) }}°C</div>
          <div class="text-[11px] text-blue-700/80">{{ minTemp.time }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue'
import type { Ref } from 'vue'
import {
  Chart,
  type ChartData,
  type ChartOptions,
} from 'chart.js/auto'

type TempPoint = { label: string; value: number; date: Date }

const canvasRef: Ref<HTMLCanvasElement | null> = ref(null)
let chart: Chart | null = null

// Generate fake temperature data every 30 minutes for today
function generateFakeData(): TempPoint[] {
  const now = new Date()
  const start = new Date(now)
  start.setHours(0, 0, 0, 0)
  const points: TempPoint[] = []

  // Diurnal base curve with randomness
  for (let i = 0; i < 48; i++) {
    const d = new Date(start.getTime() + i * 30 * 60 * 1000)

    // Diurnal pattern: min ~4:30, max ~14:30
    const hours = d.getHours() + d.getMinutes() / 60
    const phase = ((hours - 4.5) / 24) * 2 * Math.PI
    const diurnal = Math.sin(phase) // -1..1

    const base = 22 + 6 * Math.max(diurnal, -0.6) // keep nights cooler
    const noise = (Math.random() - 0.5) * 1.2
    const value = base + noise

    const label = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
    points.push({ label, value, date: d })
  }
  return points
}

const data = ref<TempPoint[]>(generateFakeData())

const minTemp = ref({ value: 0, time: '' })
const maxTemp = ref({ value: 0, time: '' })

function computeMinMax() {
  if (!data.value.length) return
  let min = data.value[0]
  let max = data.value[0]
  for (const p of data.value) {
    if (p.value < min.value) min = p
    if (p.value > max.value) max = p
  }
  minTemp.value = {
    value: min.value,
    time: min.date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
  }
  maxTemp.value = {
    value: max.value,
    time: max.date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
  }
}

function buildChart() {
  if (!canvasRef.value) return
  const ctx = canvasRef.value.getContext('2d')
  if (!ctx) return

  const chartData: ChartData<'line'> = {
    labels: data.value.map((p) => p.label),
    datasets: [
      {
        label: 'Nhiệt độ (°C)',
        data: data.value.map((p) => Number(p.value.toFixed(2))),
        borderColor: 'rgb(59, 130, 246)', // blue-500
        backgroundColor: 'rgba(59, 130, 246, 0.15)',
        tension: 0.35,
        pointRadius: 0,
        fill: true,
      },
    ],
  }

  const options: ChartOptions<'line'> = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: 'index', intersect: false },
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: (ctx) => ` ${ctx.parsed.y?.toFixed(1)}°C`,
        },
      },
      title: { display: false },
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: {
          maxTicksLimit: 12,
          autoSkip: true,
          color: '#6b7280',
          font: { size: 10 },
        },
      },
      y: {
        beginAtZero: false,
        grid: { color: 'rgba(0,0,0,0.06)' },
        ticks: {
          color: '#6b7280',
          callback: (v) => `${v}°C`,
          font: { size: 10 },
        },
      },
    },
  }

  chart = new Chart(ctx, { type: 'line', data: chartData, options })
}

onMounted(() => {
  computeMinMax()
  buildChart()
})

onBeforeUnmount(() => {
  chart?.destroy()
})
</script>

<style scoped>
</style>

