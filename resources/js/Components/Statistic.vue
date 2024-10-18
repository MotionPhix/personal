<script setup lang="ts">
defineProps<{
  title: string,
  total: number,
}>()
</script>

<template>
  <div class="flex flex-col bg-white border shadow-sm rounded-lg dark:bg-neutral-900 dark:border-neutral-800">

    <div class="p-4 md:p-5 flex gap-x-4">

      <div class="shrink-0 flex justify-center items-center size-[46px] bg-gray-100 rounded-lg dark:bg-neutral-800">

        <slot name="icon" />

      </div>

      <div class="grow">

        <div class="flex items-center gap-x-2">

          <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-neutral-500">
            {{ title }}
          </p>

        </div>

        <div class="mt-1 flex items-center gap-x-2">

          <h3 class="text-xl sm:text-2xl font-medium text-gray-800 dark:text-neutral-200">
            {{ total }}
          </h3>

          <span
            class="inline-flex items-center gap-x-1 py-0.5 px-2 rounded bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100">

            <svg
              class="inline-block size-4 self-center"
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round">
              <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
              <polyline points="16 7 22 7 22 13" />

            </svg>

            <span class="inline-block text-xs font-medium">
              12.5%
            </span>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
