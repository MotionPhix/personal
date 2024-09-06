<script setup lang="ts">
import { IconDownload } from "@tabler/icons-vue";
import { Logo } from "@/types";

defineProps<{
  logo: Logo;
}>();
</script>

<template>
  <a
    class="relative block w-full overflow-hidden rounded-lg group"
    :href="logo.file_path"
    :download="`${logo.name}.jpg`">

    <img
      class="object-cover w-full bg-gray-100 rounded-lg size-40 dark:bg-neutral-800"
      :src="logo.poster" :alt="logo.name">

    <div
      class="absolute transition opacity-0 bottom-1 end-1 group-hover:opacity-100">

      <div
        class="flex items-center px-2 py-1 text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200">

        <IconDownload
          class="shrink-0 size-3" />

        <span class="text-xs">Download</span>

      </div>

    </div>

  </a>
</template>
