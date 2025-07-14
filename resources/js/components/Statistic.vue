<script setup lang="ts">
import { IconTrendingDown, IconTrendingUp } from '@tabler/icons-vue';

defineProps<{
  title: string;
  total: number;
  variation?: number;
}>()
</script>

<template>
  <div class="flex flex-col bg-white border rounded-lg shadow-sm dark:bg-neutral-900 dark:border-neutral-800">

    <div class="flex p-4 md:p-5 gap-x-4">

      <div class="shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-neutral-800">

        <slot name="icon" />

      </div>

      <div class="grow">

        <div class="flex items-center gap-x-2">

          <p class="text-xs tracking-wide text-gray-500 uppercase dark:text-neutral-500">
            {{ title }}
          </p>

        </div>

        <div class="flex items-center mt-1 gap-x-2">

          <h3 class="text-xl font-medium text-gray-800 sm:text-2xl dark:text-neutral-200">
            {{ total }}
          </h3>

          <span
            class="inline-flex items-center gap-x-1 py-0.5 px-2 rounded text-green-100"
            :class="{
              'bg-green-800': variation >= 0,
              'bg-red-500': variation < 0
            }"
            v-if="variation">

            <IconTrendingUp
              class="self-center inline-block size-4"
              v-if="variation >= 0" />

            <IconTrendingDown
              class="self-center inline-block size-4"
              v-else />

            <span class="inline-block text-xs font-medium">
              {{ variation }}%
            </span>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
