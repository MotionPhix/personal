<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Card from "@/Components/Front/Card.vue";

interface Project {
  id: number;
  pid: string;
  poster: string;
}

defineProps<{
  projects?: Project[];
}>();

defineOptions({ layout: AppLayout })
</script>

<template>

  <Head title="Projects" />

  <main
    class="flex-auto mb-10 sm:mb-14">

     <div class="mt-16 sm:px-8 sm:mt-16">

      <div
        class="w-full max-w-3xl mx-auto lg:px-8">

        <div class="relative px-4 sm:px-8 lg:px-12">

          <div class="max-w-2xl mx-auto lg:max-w-5xl">

            <header class="max-w-2xl">

              <h1
                class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100">
                Things I've made trying to put my dent in the universe.
              </h1>

              <p
                class="mt-6 text-base text-zinc-600 dark:text-zinc-400">
                I've worked on tons of little projects over the years but these are the ones that I'm most proud of.
                Many of them are just simple artworks, not an entire case-study.
              </p>

            </header>

            <div class="mt-16 sm:mt-20">

              <ul
                role="list"
                class="grid grid-cols-2 gap-2 lg:grid-cols-3">

                <li
                  class="relative flex flex-col items-start group"
                  v-for="project in projects" :key="project.pid">

                  <Card :project="project" />

                </li>

              </ul>

            </div>

          </div>

        </div>

      </div>

    </div>

  </main>

</template>
