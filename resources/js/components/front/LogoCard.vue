<script setup lang="ts">
import {IconPhotoDown, IconTrashFilled} from "@tabler/icons-vue";
import {Link} from "@inertiajs/vue3";
import {Logo} from "@/types";

const props = defineProps<{
  logoFile: Logo;
}>();

console.log('Logo file:', props.logoFile);
</script>

<template>
  <div class="relative w-full overflow-hidden bg-white rounded-lg group">

    <div
      class="relative block w-full p-2">

      <img
        loading="lazy"
        decoding="async"
        @contextmenu.prevent
        class="object-contain w-full rounded-lg"
        :src="logoFile.poster_url" :alt="logoFile.brand">

      <div
        class="absolute transition opacity-0 bottom-2 end-2 group-hover:opacity-100">

        <a
          :href="route('downloads.show', logoFile.uuid)"
          class="flex items-center px-2 py-1 text-gray-800 bg-white rounded gap-x-1 dark:bg-neutral-900 dark:text-neutral-200">

          <IconPhotoDown
            class="shrink-0 size-3"/>

          <span class="text-xs">Download</span>

        </a>

      </div>

    </div>

    <Link
      class="absolute items-center justify-center hidden text-gray-800 bg-white rounded group-hover:flex left-2 bottom-2 size-6 dark:bg-neutral-900 dark:text-neutral-200"
      :href="route('admin.downloads.destroy', logoFile.uuid)"
      method="delete"
      as="button"
      v-if="$page.url.startsWith('/admin')">

      <IconTrashFilled
        class="size-4"/>

    </Link>

  </div>
</template>
