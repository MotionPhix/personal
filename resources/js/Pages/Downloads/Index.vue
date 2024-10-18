<script setup lang="ts">

import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3"
import { Logo } from "@/types";
import LogoCard from "@/Components/Front/LogoCard.vue";
import { IconAlertCircle } from "@tabler/icons-vue";
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { onMounted, ref } from "vue";
import { ModalLink } from '@inertiaui/modal-vue'

gsap.registerPlugin(ScrollTrigger);

const props = defineProps<{
  logoFiles: Logo[]
}>()

const headerRef = ref(null);
const logoListRef = ref(null);

onMounted(() => {
  const header = headerRef.value as any;
  const logoList = logoListRef.value as any;

  // Animate the header
  gsap.from(header.querySelector('h1'), {
    opacity: 0,
    y: 20,
    duration: 0.8,
    ease: "power2.out"
  });

  gsap.from(header.querySelector('p'), {
    opacity: 0,
    y: 20,
    duration: 0.8,
    delay: 0.2,
    ease: "power2.out"
  });

  // Animate the main header section
  gsap.from(logoList, {
    opacity: 0,
    y: 50,
    duration: 0.8,
    scrollTrigger: {
      trigger: logoList,
      start: 'top bottom-=20',
      toggleActions: 'play none none reverse'
    }
  });

  // Animate logo cards
  if (props.logoFiles?.length) {
    gsap.utils.toArray('.logo-card').forEach((card: any, index) => {
      gsap.from(card, {
        opacity: 0,
        y: 30,
        duration: 0.6,
        delay: index * 0.1,
        scrollTrigger: {
          trigger: card,
          start: 'top bottom-=20',
          toggleActions: 'play none none reverse'
        }
      });
    });
  } else {
    // Animate the "No logos available" section
    const noLogosSection = logoList?.querySelector('.no-logos');

    if (noLogosSection) {
      gsap.from(noLogosSection.children, {
        opacity: 0,
        y: 20,
        duration: 0.6,
        stagger: 0.2,
        scrollTrigger: {
          trigger: noLogosSection,
          start: 'top bottom-=20',
          toggleActions: 'play none none reverse'
        }
      });
    }
  }
});

defineOptions({
  layout: AppLayout
})
</script>

<template>

  <Head title="Polished up logos" />

  <div
    class="w-full max-w-2xl px-8 mx-auto mt-16">

    <header ref="headerRef">

      <h1
        class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100">
        Vectorised logos.
      </h1>

      <p
        class="mt-6 text-lg text-zinc-600 dark:text-zinc-400">
        Explore my collection of high-quality, vectorised logos from top companies and organisations across Malawi. These logos are optimized for various design and branding needs, ensuring scalability and clarity across all media.
      </p>

      <p
        class="mt-6 text-lg text-zinc-600 dark:text-zinc-400">
        Is your logo pixelated and can't be used in print-production? No worries,
        <ModalLink
          :href="route('fix-logo-form')"
          class="inline-flex items-center text-sm font-medium text-blue-600 gap-x-1 decoration-2 hover:underline focus:outline-none focus:underline dark:text-blue-500"
          as="button">
          <strong>
            get help
          </strong>
        </ModalLink> now!
      </p>

    </header>

    <div class="mt-16 sm:mt-20" ref="logoListRef">

      <ul
        role="list"
        v-if="logoFiles.length"
        class="grid grid-cols-3 gap-2 lg:grid-cols-4">

        <li
          class="relative flex flex-col items-start group"
          v-for="logo in logoFiles" :key="logo.lid">

          <LogoCard :logo-file="logo" />

        </li>

      </ul>

      <!-- Display this section if no logos are available -->
      <div
        v-else
        class="flex flex-col items-center justify-center mt-10 space-y-4 text-center text-zinc-600 dark:text-zinc-400">

        <IconAlertCircle class="text-gray-400 size-16 dark:text-gray-500" />

        <p class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">
          No logos available at the moment
        </p>

        <p class="text-sm text-zinc-500 dark:text-zinc-400">
          Please check back later.
        </p>

      </div>

    </div>

  </div>

</template>
