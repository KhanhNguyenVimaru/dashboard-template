<template>
  <div class="flex flex-col gap-4 p-4">
    <!-- Tầng 1: 1/3 chiều cao màn hình -->
    <div class="flex gap-5 h-[30vh]">
      <div class="flex-1 bg-gray-100 flex items-center justify-center">
        Thiết bị đăng ký
      </div>
      <div class="flex-1 bg-gray-100 flex items-center justify-center">
        Thông số thiết bị
      </div>
    </div>

    <!-- Tầng 2: 1/3 chiều cao màn hình -->
    <div class="h-[40vh] bg-gray-100 flex items-center justify-center">
      Chart dữ liệu thông số
    </div>

    <!-- Tầng 3: full 1 màn hình -->
    <div class="h-screen bg-gray-100 flex items-center justify-center">
      Kéo thả thiết bị mô phỏng
    </div>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped></style>
