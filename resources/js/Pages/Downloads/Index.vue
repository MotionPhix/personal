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

      <!-- <p
        class="p-4 mt-6 text-lg rounded-md dark:bg-neutral-600 text-zinc-600 dark:text-zinc-300 bg-neutral-200">
        Is your logo pixelated and can't be used in print-production? No worries,
        <ModalLink
          :href="route('fix-my-logo')"
          class="inline-flex items-center text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline dark:text-lime-500"
          as="button">
          <strong>
            get help
          </strong>
        </ModalLink> now!
      </p> -->

      <div
        class="flex flex-col justify-between gap-4 p-4 mt-8 border-b border-gray-200 rounded-md bg-neutral-200 dark:bg-gray-700 dark:border-gray-600">

          <div class="mb-4 md:mb-0">
            <h2 class="flex justify-between mb-1 text-base font-semibold text-gray-900 dark:text-white">
              <span>
                Help me fix my logo
              </span>
            </h2>

            <p class="text-sm font-normal text-gray-500 dark:text-gray-400">
              If you need me to help you get your pixelated logo turned into a scalable vector graphic (<strong>.svg</strong>) or a high-quality <strong>.pdf</strong> file, upload your logo by clicking the <strong>Get started</strong> button below and follow instructions.
            </p>
          </div>

          <div class="flex items-center justify-end flex-shrink-0">
            <!-- <a
              href="#"
              class="inline-flex items-center justify-center px-3 py-3 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-md me-3 focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"><svg class="w-3 h-3 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                <path d="M9 1.334C7.06.594 1.646-.84.293.653a1.158 1.158 0 0 0-.293.77v13.973c0 .193.046.383.134.55.088.167.214.306.366.403a.932.932 0 0 0 .5.147c.176 0 .348-.05.5-.147 1.059-.32 6.265.851 7.5 1.65V1.334ZM19.707.653C18.353-.84 12.94.593 11 1.333V18c1.234-.799 6.436-1.968 7.5-1.65a.931.931 0 0 0 .5.147.931.931 0 0 0 .5-.148c.152-.096.279-.235.366-.403.088-.167.134-.357.134-.55V1.423a1.158 1.158 0 0 0-.293-.77Z"/>
              </svg> Learn more
            </a> -->

            <ModalLink
              as="button"
              max-width="lg"
              position="top"
              :href="route('fix-my-logo')"
              class="inline-flex items-center justify-center px-3 py-3 text-xs font-medium text-white bg-blue-700 rounded-md me-2 hover:bg-lime-800 focus:ring-4 focus:ring-lime-300 dark:bg-lime-600 dark:hover:bg-lime-700 focus:outline-none dark:focus:ring-lime-800">
              Get started
              <svg class="w-3 h-3 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
              </svg>
            </ModalLink>

          </div>
      </div>

    </header>

    <div class="mt-16 sm:mt-20" ref="logoListRef">

      <ul
        role="list"
        v-if="logoFiles.length"
        class="grid grid-cols-2 gap-2 md:grid-cols-3">

        <li
          class="relative flex flex-col items-start"
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
