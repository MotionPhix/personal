<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { IconBrandBehance, IconBrandX, IconMail } from '@tabler/icons-vue';
import Projects from '@/Components/Front/Projects.vue';
import Skills from '@/Components/Front/Skills.vue';
import Expertise from '@/Components/Front/Expertise.vue';
import Subscription from '@/Components/Front/Subscription.vue';
import { Project, User } from '@/types';
import { onMounted, ref } from 'vue';
import { gsap } from 'gsap';

defineProps<{
  projects?: Project[];

  user?: User;
}>();

// Refs for elements to animate
const profileRef = ref(null);
const headlineRef = ref(null);
const projectsRef = ref(null);

// Animate on mounted
onMounted(() => {
  // Animate profile section
  gsap.from(profileRef.value, { opacity: 0, x: -50, duration: 1 });

  // Animate headline
  gsap.from(headlineRef.value, { opacity: 0, y: 50, duration: 1.2, delay: 0.3 });


  // Animate projects section
  gsap.from(projectsRef.value, { opacity: 0, scale: 0.9, duration: 1.4, delay: 0.6 });
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
      ref="projectsRef"
      :projects v-if="projects?.length"
      :small-columns="false"
    />
    <!-- End Projects -->

    <!-- Testimonials -->
    <!-- <Testimonials /> -->
    <!-- End Testimonials -->

    <!-- Skills -->
    <Skills />
    <!-- End Skills -->

    <!-- Work Experience -->
    <Expertise />
    <!-- End Work Experience -->

    <!-- Education -->
    <!-- <Education /> -->
    <!-- End Education -->

    <!-- Articles -->
    <!-- <div class="my-10 sm:my-14">
      <h2 class="mb-5 font-medium text-gray-800 dark:text-neutral-200">
        Articles
      </h2>

      <ul class="space-y-10">
        <li>
          <p class="mb-2 text-sm text-gray-500 dark:text-neutral-500">
            2024
          </p>

          <h5 class="text-sm font-medium text-gray-800 dark:text-neutral-200">
            The complete guide to OKRs
          </h5>

          <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">
            How to make objectives and key results work for your company.
          </p>

          <p class="mt-1">
            <a class="text-sm text-gray-500 underline hover:text-gray-800 hover:decoration-2 focus:outline-none focus:decoration-2 dark:text-neutral-500 dark:hover:text-neutral-400" href="#">
              Continue reading
            </a>
          </p>
        </li>

        <li>
          <p class="mb-2 text-sm text-gray-500 dark:text-neutral-500">
            2024
          </p>

          <h5 class="text-sm font-medium text-gray-800 dark:text-neutral-200">
            Enhancement in Customer Engagement
          </h5>

          <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">
            With the aim of optimizing customer interactions and boosting brand loyalty, the team at Preline leveraged Mailchimp's powerful tools and expertise to deliver exceptional results.
          </p>

          <p class="mt-1">
            <a class="text-sm text-gray-500 underline hover:text-gray-800 hover:decoration-2 focus:outline-none focus:decoration-2 dark:text-neutral-500 dark:hover:text-neutral-400" href="#">
              Continue reading
            </a>
          </p>
        </li>

        <li>
          <p class="mb-2 text-sm text-gray-500 dark:text-neutral-500">
            2023
          </p>

          <h5 class="text-sm font-medium text-gray-800 dark:text-neutral-200">
            How Google Assistant now helps you record stories for kids
          </h5>

          <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">
            Google is constantly updating its consumer AI, Google Assistant, with new features.
          </p>

          <p class="mt-1">
            <a class="text-sm text-gray-500 underline hover:text-gray-800 hover:decoration-2 focus:outline-none focus:decoration-2 dark:text-neutral-500 dark:hover:text-neutral-400" href="#">
              Continue reading
            </a>
          </p>
        </li>
      </ul>

    </div> -->
    <!-- End Articles -->

    <!-- Subscribe -->
    <Subscription />
    <!-- End Subscribe -->
  </div>

</template>
