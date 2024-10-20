<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Navheader from "@/Components/Backend/Navheader.vue";
import { Customer, Project } from '@/types';
import TrendLineChart from '@/Components/TrendLineChart.vue';
import Divider from '@/Components/Divider.vue';
import Statistic from '@/Components/Statistic.vue';
import { IconApps, IconBalloon, IconHourglass, IconIdBadge, IconUserDown, IconUsers, IconUserScreen } from '@tabler/icons-vue';
import { ModalLink } from '@inertiaui/modal-vue';
import DashboardProjects from '@/Components/DashboardProjects.vue';
import DashboardCustomers from '@/Components/DashboardCustomers.vue';

defineProps<{
  customersCount: number;
  projectsCount: number;
  downloadsCount: number;
  subscribersCount: number;
  latestCustomers: Customer[];
  latestProjects: Project[];
  customersPercentageChange?: number;
  projectsPercentageChange?: number;
  logosPercentageChange?: number;
  trends: any[];
}>()

defineOptions({
  layout: AuthLayout
})
</script>

<template>
  <Head title="Dashboard" />

  <Navheader>

    <h2 class="text-xl font-semibold dark:text-gray-300">
      Explore Highlights
    </h2>

  </Navheader>

  <article class="w-full max-w-2xl py-10 mx-auto md:pt-16 sm:px-6">

    <div
      class="w-full py-6 px-2.5 sm:px-6 mx-auto overflow-hidden text-gray-900 bg-gray-100 shadow-sm dark:bg-gray-800 sm:rounded-lg dark:text-gray-100">

      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        <Statistic
          title="Total Customers"
          :total="customersCount"
          :variation="customersPercentageChange">

          <template #icon>

            <IconUsers class="size-5" />

          </template>

        </Statistic>

        <Statistic
          title="Total projects"
          :total="projectsCount"
          :variation="projectsPercentageChange">

          <template #icon>

            <IconApps class="size-5" />

          </template>

        </Statistic>

        <Statistic
          title="Total logos"
          :total="downloadsCount"
          :variation="logosPercentageChange">

          <template #icon>

            <IconBalloon class="size-5" />

          </template>

        </Statistic>

        <Statistic
          title="Total subscribers"
          :total="subscribersCount">

          <template #icon>

            <IconUserScreen class="size-5" />

          </template>

        </Statistic>

      </div>

      <Divider />

      <div>

        <DashboardCustomers
          :data="latestCustomers"
          v-if="latestCustomers.length" />

        <!-- Body -->
        <div
          v-else
          class="flex flex-col justify-center w-full max-w-sm px-6 py-12 mx-auto">

            <div class="flex justify-center items-center size-[46px] bg-gray-200 rounded-lg dark:bg-neutral-800">

              <IconUserDown class="shrink-0 size-6" />

            </div>

            <h2 class="mt-5 font-semibold text-gray-800 dark:text-white">
              No customers found
            </h2>

            <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
              Add a customer and create a project on it.
            </p>

            <div class="mt-5">
              <Link
                as="button"
                :href="route('auth.customer.create', { modal: 'show' })"
                class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white border border-transparent rounded gap-x-2 bg-lime-600 hover:bg-lime-700 focus:outline-none focus:bg-lime-700 disabled:opacity-50 disabled:pointer-events-none">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Add customer
              </Link>
            </div>
          </div>
          <!-- End Body -->
      </div>

      <Divider />

      <div>

        <DashboardProjects
          :data="latestProjects"
          v-if="latestProjects.length" />

        <div
          v-else
          class="flex flex-col justify-center w-full max-w-sm px-6 py-12 mx-auto">

          <div
            class="flex justify-center items-center size-[46px] bg-gray-200 rounded-lg dark:bg-neutral-800">

            <IconHourglass class="shrink-0 size-6" />

          </div>

          <h2 class="mt-5 font-semibold text-gray-800 dark:text-white">
            No projects found
          </h2>

          <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
            Add a project to the list of your achievements.
          </p>

          <div class="mt-5">
            <Link
              as="button"
              :href="route('auth.projects.create')"
              class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white border border-transparent rounded gap-x-2 bg-lime-600 hover:bg-lime-700 focus:outline-none focus:bg-lime-700 disabled:opacity-50 disabled:pointer-events-none">
              <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
              Add project
            </Link>

          </div>

        </div>

      </div>

      <Divider />

      <div class="space-y-2">
        <h2 class="text-lg">
          Subscribers trend (Last 30 days)
        </h2>

        <TrendLineChart
          :data="trends"
          v-if="trends.length" />

          <div
            v-else
            class="flex flex-col justify-center w-full max-w-sm px-6 py-12 mx-auto">

            <div
              class="flex justify-center items-center size-[46px] bg-gray-200 rounded-lg dark:bg-neutral-800">

              <IconIdBadge class="shrink-0 size-6" />

            </div>

            <h2 class="mt-5 font-semibold text-gray-800 dark:text-white">
              No trends yet
            </h2>

            <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
              If you have subscribers, the trend will show
            </p>

          </div>

      </div>

    </div>

  </article>

</template>
