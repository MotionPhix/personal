<script setup lang="ts">
import { IconPhotoDown, IconTrashFilled } from "@tabler/icons-vue";
import { Logo } from "@/types";
import { Link } from "@inertiajs/vue3";

defineProps<{
  logoFile: Logo;
}>();
</script>

<template>
  <div class="relative overflow-hidden bg-white rounded-lg group">

    <a
      class="relative block w-full"
      :href="route('auth.downloads.show', logoFile.lid)">

      <img
        class="object-contain w-full px-2 rounded-lg sm:object-cover size-40"
        :src="logoFile.poster_url" :alt="logoFile.brand">

      <div
        class="absolute transition opacity-0 bottom-1 end-1 group-hover:opacity-100">

        <div
          class="flex items-center px-2 py-1 text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200">

          <IconPhotoDown
            class="shrink-0 size-3" />

          <span class="text-xs">Download</span>

        </div>

      </div>

    </a>

    <Link
      class="absolute items-center justify-center hidden text-gray-800 bg-white border border-gray-200 rounded-lg group-hover:flex left-1 bottom-1 size-6 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200"
      :href="route('auth.downloads.destroy', logoFile.lid)"
      method="delete"
      as="button"
      v-if="$page.url.startsWith('/auth')">

      <IconTrashFilled
        class="size-4" />

    </Link>

  </div>
</template>
