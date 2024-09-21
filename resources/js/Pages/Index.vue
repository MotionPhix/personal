<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { IconBrandBehance, IconBrandX, IconMail } from '@tabler/icons-vue';
import Projects from '@/Components/Front/Projects.vue';
import Skills from '@/Components/Front/Skills.vue';
import Expertise from '@/Components/Front/Expertise.vue';
import Subscription from '@/Components/Front/Subscription.vue';
import { Project, User } from '@/types';
import { onMounted, ref } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

defineProps<{
  projects?: Project[];
  user?: User;
}>();

// Refs for elements to animate
const profileRef = ref(null);
const aboutRef = ref(null);
const headlineRef = ref(null);

onMounted(() => {
  const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

  tl.from(profileRef.value, { opacity: 0, x: -50, duration: 1 })
    .from(headlineRef.value, { opacity: 0, y: 50, duration: 1.2 }, '-=0.5')
    .from(aboutRef.value, { opacity: 0, y: 20, duration: 1 }, '-=0.8');
});

defineOptions({ layout: AppLayout })
</script>

<template>

  <Head title="Welcome" />

  <div
    class="w-full max-w-2xl px-4 pt-10 mx-auto md:pt-16 sm:px-6 lg:px-8">

    <!-- Profile -->
    <div
      ref="profileRef"
      class="flex items-center gap-x-3">
      <div class="shrink-0">
        <img
          class="rounded-full shrink-0 size-16"
          src="/assets/profile_400x400.jpg" alt="Avatar">
      </div>

      <div class="grow">
        <h1 class="text-lg font-medium text-gray-800 dark:text-neutral-200">
          {{ user?.first_name + ' ' + user?.last_name }}
        </h1>

        <p class="text-sm text-gray-600 dark:text-neutral-400">
          Graphic Designer, Web designer/developer
        </p>
      </div>
    </div>
    <!-- End Profile -->

    <h1
      ref="headlineRef"
      class="mt-8 text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100">
      I'm Kingsley. I live in Lilongwe, Malawi, where I make designs.
    </h1>

    <!-- About -->
    <div
      ref="aboutRef"
      class="mt-8">
      <p class="text-base text-gray-600 dark:text-neutral-400">
        I am a seasoned graphic designer with over 14 years of experience in creating visually appealing and user-centric designs. My expertise spans across UI design, design systems, and custom illustrations, helping clients bring their digital visions to life.
      </p>

      <p class="mt-3 text-base text-gray-600 dark:text-neutral-400">
        Currently, I work with <a
            class="text-gray-500 underline hover:text-gray-800 hover:decoration-2 focus:outline-none focus:decoration-2 dark:text-neutral-500 dark:hover:text-neutral-400"
            href="https://firstmarkmw.com" target="_blank">
            <strong>Firstmark Advertising</strong>
          </a>, where I design artworks, convert them into print-ready artworks, and provide comprehensive support to our customers. I am passionate about crafting elegant and functional designs that enhance user experiences and businesses.
      </p>

      <ul class="flex gap-2 mt-10 sm:gap-x-4">

        <li>

          <a
            class="text-base flex items-center gap-2.5 text-gray-500 underline hover:text-gray-800 hover:decoration-2 focus:outline-none focus:decoration-2 dark:text-neutral-500 dark:hover:text-neutral-400"
            href="mailto:support@ultrashots.net" target="_blank">
            <IconMail size="24" class="dark:text-neutral-500" />
            <span class="hidden sm:inline-flex">{{ user?.email }}</span>
          </a>

        </li>

        <li>

          <a
            class="text-base flex items-center gap-2.5 text-gray-500 underline hover:text-gray-800 hover:decoration-2 focus:outline-none focus:decoration-2 dark:text-neutral-500 dark:hover:text-neutral-400"
            href="https://x.com/ultrashoots" target="_blank">
            <IconBrandX size="24" class="dark:text-neutral-500" />
            <span class="hidden sm:inline-flex">{{ user?.socials?.twitter }}</span>
          </a>

        </li>

        <li>

          <a
            class="text-base flex items-center gap-2.5 text-gray-500 underline hover:text-gray-800 hover:decoration-2 focus:outline-none focus:decoration-2 dark:text-neutral-500 dark:hover:text-neutral-400"
            href="https://be.net/ultrashots" target="_blank">
            <IconBrandBehance size="24" class="dark:text-neutral-500" />
            <span class="hidden sm:inline-flex">{{ user?.socials?.behance }}</span>
          </a>

        </li>

      </ul>

    </div>
    <!-- End About -->

    <!-- Projects -->
    <Projects
      :projects v-if="projects?.length"
      :small-columns="false"
    />
    <!-- End Projects -->

    <!-- Skills -->
    <Skills ref="skillsRef" />
    <!-- End Skills -->

    <!-- Work Experience -->
    <Expertise ref="expertiseRef" />
    <!-- End Work Experience -->

    <!-- Subscribe -->
    <Subscription ref="subscriptionRef" />
    <!-- End Subscribe -->
  </div>

</template>
