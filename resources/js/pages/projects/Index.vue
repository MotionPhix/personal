<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/components/front/Card.vue";
import { ref, onMounted } from 'vue';
import { Project } from '@/types';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Props
const props = defineProps<{
  projects: {
    data: Project[]
  };
}>();

const projectListRef = ref(null);

onMounted(() => {
  const projectList = projectListRef.value as any;

  // Animate the main container
  gsap.from(projectList, {
    opacity: 0,
    y: 50,
    duration: 0.8,
    scrollTrigger: {
      trigger: projectList,
      start: 'top bottom-=100',
      toggleActions: 'play none none reverse'
    }
  });

  // Animate project cards
  if (props.projects?.data.length) {
    gsap.utils.toArray('.project-card').forEach((card: any, index) => {
      gsap.from(card, {
        opacity: 0,
        y: 30,
        duration: 0.6,
        delay: index * 0.1,
        scrollTrigger: {
          trigger: card,
          start: 'top bottom-=50',
          toggleActions: 'play none none reverse'
        }
      });
    });
  } /*else {
    // Animate the "No projects Available" section
    const noProjectsSection = projectList.querySelector('.no-projects');
    if (noProjectsSection) {
      gsap.from(noProjectsSection.children, {
        opacity: 0,
        y: 20,
        duration: 0.6,
        stagger: 0.2,
        scrollTrigger: {
          trigger: noProjectsSection,
          start: 'top bottom-=50',
          toggleActions: 'play none none reverse'
        }
      });
    }
  }*/
});

defineOptions({ layout: AppLayout });
</script>

<template>
  <Head title="Projects" />

  <div
    class="w-full max-w-2xl px-8 mx-auto mt-16 lg:px-12"
    ref="projectListRef">

    <!-- Header Section -->
    <header ref="headerRef" class="max-w-2xl">
      <h1 class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100">
        Things I've made trying to put my dent in the universe.
      </h1>
      <p class="mt-6 text-lg text-zinc-600 dark:text-zinc-400">
        I've worked on tons of little projects over the years but these are the ones that I'm most proud of. Enjoy!
      </p>
    </header>

    <!-- projects Section -->
    <div class="mt-16 sm:mt-20">

      <ul
        role="list"
        class="grid grid-cols-2 gap-2 lg:grid-cols-3">
        <li
          v-for="project in projects.data"
          :key="project.uuid"
          class="relative flex flex-col items-start project-card"
          ref="el => projectsRef.value[index] = el">
          <Card :project="project" />
        </li>
      </ul>
    </div>

  </div>
</template>
