<script setup lang="ts">
defineProps<{
  data: Object
}>()
</script>

<template>
  <!-- Card -->
  <div class="flex flex-col">

    <div class="-m-1.5 overflow-x-auto">

      <div class="p-1.5 min-w-full inline-block align-middle">

        <div
          class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">

          <!-- Header -->
          <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">

            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
              Latest projects
            </h2>

            <p class="text-sm text-gray-600 dark:text-neutral-400">
              The last few projects that were completed.
            </p>

          </div>
          <!-- End Header -->

          <!-- Table -->
          <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">

            <thead class="bg-gray-50 dark:bg-neutral-800">

              <tr>

                <th scope="col"
                    class="px-6 py-3 text-start whitespace-pre-wrap min-w-36">
                  <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                    Project
                  </span>
                </th>

                <th scope="col"
                    class="px-6 py-3 text-start whitespace-nowrap">
                  <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                    Completion date
                  </span>
                </th>
              </tr>

            </thead>

            <tbody
              class="divide-y divide-gray-200 dark:divide-neutral-700">

              <tr v-for="project in data">
                <td class="size-px whitespace-nowrap px-6 py-3">

                  <span class="text-sm text-gray-800 dark:text-white">
                    {{ project.name }}
                  </span>

                  <span class="block text-xs text-gray-400 dark:text-neutral-400">
                    {{ project.customer.name }}
                  </span>

                </td>

                <td class="size-px whitespace-nowrap px-6 py-3">
                  <span class="text-sm text-gray-500 dark:text-neutral-400">
                    {{ project.completion_date }}
                  </span>
                </td>

              </tr>

            </tbody>
          </table>
          <!-- End Table -->
        </div>
      </div>
    </div>
  </div>
  <!-- End Card -->
</template>
