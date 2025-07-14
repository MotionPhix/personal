<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { IconBrandBehance, IconBrandLinkedin, IconBrandX } from '@tabler/icons-vue';
import Projects from '@/components/front/Projects.vue';
import Skills from '@/components/front/Skills.vue';
import Expertise from '@/components/front/Expertise.vue';
import Subscription from '@/components/front/Subscription.vue';
import { Project, User } from '@/types';
import { onMounted, ref } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import IconEmail from '@/components/icon/IconEmail.vue';

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
    class="w-full max-w-2xl px-8 pt-10 mx-auto md:pt-16">

    <!-- profile -->
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
    <!-- End profile -->

    <h1
      ref="headlineRef"
      class="mt-8 text-3xl font-bold tracking-tight text-zinc-800 sm:text-4xl dark:text-zinc-100">
      Hi, I'm Kingsley, a creative graphic designer and front-end web developer based in Lilongwe, Malawi.
    </h1>

    <!-- About -->
    <div
      ref="aboutRef"
      class="mt-8 text-base text-gray-600 dark:text-neutral-300">
      <p>
        With more than a decade of experience, I have refined my abilities to produce visually appealing, user-centric designs that function seamlessly. I am enthusiastic about transforming creative concepts into digital realities, from the creation of intuitive user interface designs to the development of cohesive design systems and custom illustrations. My objective is to consistently generate designs that are not only visually compelling but also functional, thereby assisting clients in enhancing their brand and providing exceptional user experiences.
      </p>

      <p class="mt-3">
        I have worked with a variety of clients to revitalize their products and services over the course of my career. I am of the opinion that exceptional design is a combination of strategy and creativity, and I approach each assignment with this perspective. Regardless of whether I am developing websites from the ground up or improving existing platforms, my primary objective is to produce tangible outcomes.
      </p>

      <p class="mt-3">
        I am perpetually in the process of learning, adapting to new trends, and expanding the boundaries of what is feasible in both front-end development and design. Join me in the creation of something truly remarkable!
      </p>

      <ul class="flex gap-2 mt-10 sm:gap-x-4">

        <li>

          <a
            class="text-base flex items-center gap-2.5 text-gray-500 underline hover:text-gray-800 hover:decoration-2 focus:outline-none focus:decoration-2 dark:text-neutral-500 dark:hover:text-neutral-400"
            :href="`https://linkedin.com/in/${user?.socials?.linkedin}`" target="_blank">
            <IconBrandLinkedin size="24" class="dark:text-neutral-500" />
          </a>

        </li>

        <li>

          <a
            class="text-base flex items-center gap-2.5 text-gray-500 underline hover:text-gray-800 hover:decoration-2 focus:outline-none focus:decoration-2 dark:text-neutral-500 dark:hover:text-neutral-400"
            :href="`https://x.com/${user?.socials?.twitter}`" target="_blank">
            <IconBrandX size="24" class="dark:text-neutral-500" />
          </a>

        </li>

        <li>

          <a
            class="text-base flex items-center gap-2.5 text-gray-500 underline hover:text-gray-800 hover:decoration-2 focus:outline-none focus:decoration-2 dark:text-neutral-500 dark:hover:text-neutral-400"
            :href="`https://be.net/${user?.socials?.behance}`" target="_blank">
            <IconBrandBehance size="24" class="dark:text-neutral-500" />
          </a>

        </li>

        <li>

          <a
            class="text-base flex items-center gap-2.5 text-gray-500 underline hover:text-gray-800 hover:decoration-2 focus:outline-none focus:decoration-2 dark:text-neutral-500 dark:hover:text-neutral-400"
            :href="`mailto:${user?.email}`" target="_blank">
            <IconEmail size="24" class="dark:text-neutral-500" />
          </a>

        </li>

      </ul>

    </div>
    <!-- End About -->

    <!-- projects -->
    <Projects
      :projects v-if="projects?.length"
      :small-columns="false"
    />
    <!-- End projects -->

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
