<template>
  <div
    class="bg-white shadow-sm rounded border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-sm relative w-full text-xs"
  >
    <!-- Header Section -->
    <div class="bg-gray-50 px-2 py-1 border-b border-gray-200">
      <div class="flex justify-between items-center gap-3">
        <h3 class="font-medium text-gray-700">
          {{ title || 'Device' }}
        </h3>

        <!-- Action Controls -->
        <div class="flex items-center gap-2">
          <!-- Filter Input -->
          <div class="relative">
            <input
              v-model="filterKeyword"
              type="text"
              placeholder="Search..."
              class="pl-5 pr-1 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-gray-400 focus:border-gray-400 bg-white w-52 text-xs cursor-text"
              @change="(e: Event) => emit('change:search', (e.target as HTMLInputElement).value.trim())"
            />
            <svg
              class="absolute left-1 top-1.5 h-3 w-3 text-gray-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7
                   7 0 11-14 0 7 7 0 0114 0z"
              />
            </svg>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-2">
            <button
              @click.stop="emit('add-click')"
              class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white rounded px-3 py-1 text-xs"
            >
              <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6
                     0H6"
                />
              </svg>
              New Devices
            </button>

            <button
              @click.stop="resetTable"
              class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white rounded px-3 py-0.5 text-xs"
            >
              <svg class="w-2.5 h-2.5 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 4v5h.582m15.356
                     2A8.001 8.001 0 004.582
                     9m0 0H9m11 11v-5h-.581m0
                     0a8.003 8.003 0
                     01-15.357-2m15.357
                     2H15"
                />
              </svg>
              Reload
            </button>

            <button
              @click.stop
              class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white rounded px-3 py-0.5 text-xs"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="h-2.5 w-2.5 mr-1"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 3c2.755 0 5.455.232
                     8.083.678.533.09.917.556.917
                     1.096v1.044a2.25 2.25 0 0
                     1-.659 1.591l-5.432
                     5.432a2.25 2.25 0 0
                     0-.659 1.591v2.927a2.25
                     2.25 0 0 1-1.244
                     2.013L9.75 21v-6.568a2.25
                     2.25 0 0 0-.659-1.591L3.659
                     7.409A2.25 2.25 0 0 1 3
                     5.818V4.774c0-.54.384-1.006.917-1.096A48.32
                     48.32 0 0 1 12 3Z"
                />
              </svg>
              Filter
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto w-full">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th
              v-for="col in columns"
              :key="col"
              class="text-left font-medium text-gray-600 px-2 py-2 text-xs"
            >
              {{ col }}
            </th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
          <!-- Loading row -->
          <tr v-if="loading">
            <td :colspan="columns.length" class="p-6 text-center">
              <div class="flex flex-col items-center">
                <svg
                  class="animate-spin h-6 w-6 text-blue-500 mb-1"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                  ></circle>
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0
                       0 5.373 0 12h4zm2
                       5.291A7.962 7.962 0 014 12H0c0
                       3.042 1.135 5.824 3 7.938l3-2.647z"
                  ></path>
                </svg>
                <span class="text-gray-600 text-xs">Loading...</span>
              </div>
            </td>
          </tr>

          <!-- Data rows -->
          <tr
            v-else
            v-for="(row, idx) in filteredData"
            :key="idx"
            class="hover:bg-gray-50 transition-colors duration-150"
            @click="emit('detail-click', row as any)"
          >
            <td
              v-for="col in columns"
              :key="col"
              class="text-gray-700 px-2 py-2 text-xs cell-content"
            >
              <span class="font-normal line-clamp">
                {{ row[col] }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Empty State -->
      <div v-if="!loading && filteredData.length === 0" class="text-center py-8">
        <svg
          class="mx-auto h-4 w-4 text-gray-300"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9 12h6m-6 4h6m2
               5H7a2 2 0 01-2-2V5a2
               2 0 012-2h5.586a1 1
               0 01.707.293l5.414
               5.414a1 1 0
               01.293.707V19a2
               2 0 01-2 2z"
          />
        </svg>
        <h3 class="mt-1 font-medium text-gray-600 text-xs">No data available</h3>
        <p class="mt-0.5 text-gray-400 text-xs">
          {{ filterKeyword ? 'No matching results found' : 'No data available' }}
        </p>
      </div>
    </div>

    <!-- Pagination Controls -->
    <div
      v-if="pagination"
      class="px-2 py-1 border-t border-gray-200 bg-gray-50 flex items-center justify-between"
    >
      <div class="text-gray-500 text-xs">
        {{ pagination.page }}/{{ pagination.lastPage }} · {{ pagination.total }} items
      </div>
      <div class="flex items-center gap-1">
        <select          class="border border-gray-300 rounded py-0.5 px-0.5 bg-white text-xs"
          :value="pagination.perPage"
          @change="
            (e: Event) => emit('change:perPage', Number((e.target as HTMLSelectElement).value))
          "
        >
          <option :value="5">5</option>
          <option :value="10">10</option>
          <option :value="20">20</option>
          <option :value="50">50</option>
        </select>
        <button
          class="px-1 py-0.5 border border-gray-300 rounded bg-white hover:bg-gray-50 disabled:opacity-50 text-xs"
          :disabled="pagination.page <= 1"
          @click="emit('change:page', pagination.page - 1)"
        >
          ←
        </button>
        <button
          class="px-1 py-0.5 border border-gray-300 rounded bg-white hover:bg-gray-50 disabled:opacity-50 text-xs"
          :disabled="pagination.page >= pagination.lastPage"
          @click="emit('change:page', pagination.page + 1)"
        >
          →
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onBeforeUnmount } from 'vue'
import type { PropType } from 'vue'

interface Row {
  [key: string]: string | number | boolean | null | undefined
}

interface Pagination {
  page: number
  lastPage: number
  perPage: number
  total: number
}

const props = defineProps({
  title: { type: String, default: '' },
  data: { type: Array as PropType<Row[]>, default: () => [] },
  columns: { type: Array as PropType<string[]>, default: () => [] },
  isMain: { type: Boolean, default: false },
  pagination: { type: Object as PropType<Pagination | null>, default: null },
  loading: { type: Boolean, default: false },
})

const emit = defineEmits<{
  (e: 'update:data', value: Row[]): void
  (e: 'change:page', value: number): void
  (e: 'change:perPage', value: number): void
  (e: 'change:search', value: string): void
  (e: 'add-click'): void
  (e: 'detail-click', value: string): void
}>()

const filterKeyword = ref('')
let debounceId: ReturnType<typeof setTimeout> | undefined

watch(filterKeyword, (value) => {
  if (debounceId) clearTimeout(debounceId)
  debounceId = setTimeout(() => emit('change:search', value.trim()), 300)
})

onBeforeUnmount(() => {
  if (debounceId) clearTimeout(debounceId)
})

const filteredData = computed(() => props.data)

const resetTable = () => {
  filterKeyword.value = ''
  emit('change:search', '')
}
</script>

<style scoped>
button,
select,
tr {
  cursor: pointer;
}

.cell-content {
  max-width: 200px;
}

.line-clamp {
  display: -webkit-box;
  display: box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}
tr {
  cursor: pointer;
  transition: background-color 0.2s ease;
}

tr:active {
  background-color: #e0f2fe; /* bg-sky-100 */
}

tr:focus {
  outline: none;
  background-color: #dbeafe; /* bg-blue-100 */
}

</style>
