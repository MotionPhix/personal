<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Navheader from "@/Components/Backend/Navheader.vue";
import { Customer, Project } from '@/types';
import TrendLineChart from '@/Components/TrendLineChart.vue';
import Divider from '@/Components/Divider.vue';
import Statistic from '@/Components/Statistic.vue';
import { IconApps, IconBalloon, IconHourglass, IconUserDown, IconUsers, IconUserScreen } from '@tabler/icons-vue';
import { ModalLink } from '@inertiaui/modal-vue';
import SimpleTable from '@/Components/SimpleTable.vue';

defineProps<{
  customersCount: number,
  projectsCount: number,
  downloadsCount: number,
  subscribersCount: number,
  latestCustomers: Customer[],
  latestProjects: Project[],
  trends: any[]
}>()

/*const formartedTrend = computed(() => props.trends.map(item => ({
  date: item.date,
  subscribed: item.subscribed_count,
  unsubscribed: item.unsubscribed_count,
})))*/

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

      <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">

        <Statistic
          title="Total Customers"
          :total="customersCount">

          <template #icon>

            <IconUsers class="size-5" />

          </template>

        </Statistic>

        <Statistic
          title="Total projects"
          :total="projectsCount">

          <template #icon>

            <IconApps class="size-5" />

          </template>

        </Statistic>

        <Statistic
          title="Total logos"
          :total="downloadsCount">

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
        <h2 class="text-lg">Latest customers</h2>

        <ul class="p-4 mt-2 bg-white rounded-lg shadow-md" v-if="latestCustomers.length">

          <li v-for="customer in latestCustomers" :key="customer.id">

            {{ customer.first_name }} {{ customer.last_name }} - {{ customer.company_name }}

          </li>

        </ul>

        <!-- Body -->
        <div
          v-else
          class="max-w-sm w-full flex flex-col justify-center mx-auto px-6 py-12">

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
                class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded border border-transparent bg-lime-600 text-white hover:bg-lime-700 focus:outline-none focus:bg-lime-700 disabled:opacity-50 disabled:pointer-events-none">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Add customer
              </Link>
            </div>
          </div>
          <!-- End Body -->
      </div>

      <Divider />

      <div>

        <SimpleTable
          :data="latestProjects"
          v-if="latestProjects.length" />

        <!-- <ul
          v-if="latestProjects.length"
          class="p-4 mt-2 bg-white rounded-lg shadow-md">

          <li v-for="project in latestProjects" :key="project.id">

            {{ project.name }} - {{ project.production }}
          </li>

        </ul> -->

        <!-- Body -->
        <div
          v-else
          class="max-w-sm w-full flex flex-col justify-center mx-auto px-6 py-12">

            <div class="flex justify-center items-center size-[46px] bg-gray-200 rounded-lg dark:bg-neutral-800">

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
                class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded border border-transparent bg-lime-600 text-white hover:bg-lime-700 focus:outline-none focus:bg-lime-700 disabled:opacity-50 disabled:pointer-events-none">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Add project
              </Link>
            </div>
          </div>
          <!-- End Body -->

      </div>

      <Divider />

      <div class="space-y-2">
        <h2 class="text-lg">
          Subscribers trend (Last 30 days)
        </h2>

        <TrendLineChart :data="trends" />
      </div>

    </div>

  </article>

</template>
