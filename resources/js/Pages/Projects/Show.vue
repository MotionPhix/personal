<script setup lang="ts">

import AppLayout from "@/Layouts/AppLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import { IconArrowLeft } from "@tabler/icons-vue";

interface Address {
  city: string;
  state: string;
  country: string;
}

interface Customer {
  id: number;
  cid: string;
  first_name: string;
  last_name: string;
  company_name: string;
  address: string[];
  customer: Customer;
}

interface Project {
  id: number;
  pid: string;
  name: string;
  poster: string;
  description: string;
  images: string[];
  customer: Customer;
}

defineProps<{
  project: Project;
}>();

defineOptions({
  layout: AppLayout
})
</script>

<template>

  <Head :title="project.name" preserve-scroll preserve-state />

  <main class="flex-auto mb-10 sm:mb-14">
    <div class="mt-8 sm:px-8 sm:mt-16">
      <div class="w-full max-w-3xl mx-auto lg:px-8">
        <div class="relative px-4 sm:px-8 lg:px-12">
          <div class="max-w-2xl mx-auto lg:max-w-5xl">
            <!-- Back to Projects Button -->
            <Link
              :href="route('projects.index')"
              class="group flex items-center gap-2 text-base text-blue-600 dark:text-blue-400 transition-all duration-300 ease-in-out transform hover:-translate-x-1 hover:text-blue-800 dark:hover:text-blue-600"
            >
              <IconArrowLeft size="24" class="hidden group-hover:inline-block" />

              <span class="group-hover:font-bold">Projects</span>
            </Link>

            <div
              class="md:h-[calc(100vh-400px)] group my-10 max-w-full h-[30rem] relative flex flex-col w-full min-h-60 bg-center bg-cover rounded-xl hover:shadow-lg focus:outline-none focus:shadow-lg transition"
              :style="{ backgroundImage: `url(${project.poster})` }">

              <div class="w-2/3 pb-5 mt-auto md:max-w-lg ps-5 md:ps-10 md:pb-10">

                <span class="block text-white">
                  Nike React
                </span>

                <span
                  class="block text-xl text-white md:text-3xl">
                  Rewriting sport's playbook for billions of athletes
                </span>

              </div>

            </div>

            <header class="max-w-2xl mb-12">
              <h1
                class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100"
              >
                {{ project.name }}
              </h1>

              <p class="mt-6 text-base text-zinc-600 dark:text-zinc-400">
                {{ project.description }}
              </p>

              <div class="mt-4">

                <dl class="flex flex-col gap-1">

                  <dt class="min-w-40">
                    <span class="block text-sm text-gray-500 dark:text-neutral-500">
                      Client
                    </span>
                  </dt>

                  <dd>

                    <ul class="flex flex-col">

                      <li class="me-1 after:content-[','] inline-flex items-center text-sm text-gray-800 dark:text-neutral-200">
                        {{ project.customer.name }}
                      </li>

                      <li class="me-1 after:content-[','] inline-flex items-center text-sm text-gray-800 dark:text-neutral-200">
                        {{ project.customer.company_name }}
                      </li>

                      <li class="me-1 after:content-[','] inline-flex items-center text-sm text-gray-800 dark:text-neutral-200">
                        Attention to detail
                      </li>

                      <li class="me-1 after:content-[','] inline-flex items-center text-sm text-gray-800 dark:text-neutral-200">
                        Time management
                      </li>

                    </ul>

                  </dd>

                </dl>

                <span class="block text-sm text-zinc-500 dark:text-zinc-400">
                  Completed on: {{ 'N/A' }}
                </span>
              </div>
            </header>

            <!-- Project Images -->
            <div class="grid grid-cols-2 gap-4 mb-8">
              <img
                v-for="(image, index) in project.images"
                :key="index"
                :src="image"
                alt="Project Image"
                class="object-cover w-full rounded-lg shadow-lg"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>


</template>
