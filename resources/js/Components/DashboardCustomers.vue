<script setup lang="ts">
import { Customer } from '@/types';
import { Link } from '@inertiajs/vue3';
import { IconPencil } from '@tabler/icons-vue';

defineProps<{
  data: Customer[]
}>()
</script>

<template>
  <!-- Card -->
  <div class="flex flex-col">

    <div class="-m-1.5 overflow-x-auto">

      <div class="p-1.5 min-w-full inline-block align-middle">

        <div
          class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700">

          <!-- Header -->
          <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">

            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
              Latest customers
            </h2>

            <p class="text-sm text-gray-600 dark:text-neutral-400">
              Customers you have added in the last 10 days
            </p>

          </div>
          <!-- End Header -->

          <!-- Table -->
          <div
            class="w-full p-4 bg-white sm:p-8 dark:bg-gray-800 dark:border-gray-700">

            <div class="flow-root">

              <ul
                role="list"
                class="divide-y divide-gray-200 dark:divide-gray-700">

                <li
                  class="py-3 sm:py-4"
                  v-for="customer in data">

                  <div class="flex items-center">

                    <div class="flex-1 min-w-0">

                      <p
                        class="text-sm font-medium text-gray-900 truncate dark:text-white">
                        {{ `${customer.first_name} ${customer.last_name}` }}
                      </p>

                      <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                        {{ customer.company_name}}
                      </p>
                    </div>

                    <div
                      class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                      <Link
                        as="button"
                        preserve-scroll
                        :href="route('auth.customer.edit', customer.cid)"
                        class="flex items-center gap-2">
                        <IconPencil size="24" />
                        <span>Edit</span>
                      </Link>
                    </div>

                  </div>

                </li>

              </ul>

            </div>
          </div>
          <!-- End Table -->

        </div>
      </div>
    </div>
  </div>
  <!-- End Card -->
</template>
