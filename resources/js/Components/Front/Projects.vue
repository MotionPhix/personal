<script setup lang="ts">
import { Project } from '@/types';
import { Link } from '@inertiajs/vue3';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onMounted, onUnmounted, ref } from 'vue';

gsap.registerPlugin(ScrollTrigger);

defineProps<{
  projects?: Project[],
  smallColumns: boolean
}>()

const main = ref();
let ctx: any;

onMounted(() => {
  ctx = gsap.context(() => {

    // const cards = self?.selector('.card');
    const cards = gsap.utils.toArray('.card');

    cards.forEach((card: any, index) => {
      gsap.fromTo(card,
        { opacity: 0, y: 50 },
        {
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: 'power2.out',
          scrollTrigger: {
            trigger: card,
            start: 'top bottom-=100',
            end: 'bottom center',
            toggleActions: 'play none none reverse',
          },
          delay: index * 0.2 // Stagger effect
        }
      );
    });

  }, main.value);
})

onUnmounted(() => {
  //ctx.revert(); // <- Easy Cleanup!
});
</script>

<template>
  <!-- Projects -->
  <div class="mt-10 sm:mt-14">
    <h2 class="mb-5 uppercase font-medium text-gray-800 dark:text-neutral-200">
      Projects
    </h2>

    <!-- Image Grid -->
    <div
      ref="main"
      class="grid gap-4"
      :class="smallColumns ? 'grid-cols-3' : 'grid-cols-2'">

      <Link
        class="relative block overflow-hidden rounded-lg card group"
        :href="route('projects.show', project.pid)"
        v-for="project in projects"
        :key="project.pid">

        <img
          class="object-cover w-full bg-gray-100 rounded-lg size-40 dark:bg-neutral-800"
          :src="project.media?.length ? project.media[0].original_url : ''"
          :alt="project.media?.length ? project.media[0].name : 'project poster'" />

        <div class="absolute transition opacity-0 bottom-1 end-1 group-hover:opacity-100">

          <div
            class="flex items-center px-2 py-1 text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200">

            <svg
              class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round">
              <circle cx="11" cy="11" r="8" />
              <path d="m21 21-4.3-4.3" />
            </svg>

            <span class="text-xs">View</span>

          </div>

        </div>

      </Link>

    </div>
    <!-- End Image Grid -->
  </div>
  <!-- End Projects -->
</template>
