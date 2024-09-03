<script setup lang="ts">

</script>

<template>
  <a
    class="relative block w-full overflow-hidden rounded-lg group"
    href="#">

    <img
      class="object-cover w-full bg-gray-100 rounded-lg size-40 dark:bg-neutral-800"
      src="https://images.unsplash.com/photo-1585159812596-fac104f2f069?q=80&w=320&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Project">

    <div
      class="absolute transition opacity-0 bottom-1 end-1 group-hover:opacity-100">

      <div
        class="flex items-center px-2 py-1 text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200">

        <svg
          class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8" />
          <path d="m21 21-4.3-4.3" />
        </svg>

        <span class="text-xs">View</span>

      </div>

    </div>

  </a>
</template>
