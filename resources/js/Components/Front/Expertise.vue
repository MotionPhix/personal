<script setup>
import {
  IconFingerprint,
  IconDeviceGamepad2,
  IconLayoutCards,
  IconMovie,
} from '@tabler/icons-vue';
</script>

<template>
<div class="mt-10 sm:mt-14">
  <h2 class="mb-5 font-medium text-gray-800 dark:text-neutral-200">
    My Area of Expertise
  </h2>

  <!-- Timeline -->
  <div>
    <!-- Item -->
    <div class="relative flex group gap-x-5">

      <!-- Icon -->
      <div
        class="relative group-last:after:hidden after:absolute after:top-8 after:bottom-2 after:start-3 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-neutral-700">

        <div class="relative z-10 flex items-center justify-center size-6">

          <IconLayoutCards
            class="text-gray-600 shrink-0 size-6 dark:text-neutral-400" />

        </div>

      </div>
      <!-- End Icon -->

      <!-- Right Content -->
      <div class="pb-8 grow group-last:pb-0">

        <!-- <h3 class="mb-1 text-xs text-gray-600 dark:text-neutral-400">
          2023 - Present
        </h3> -->

        <p class="font-semibold text-gray-800 dark:text-neutral-200">
          Print Design
        </p>

        <p class="mt-1 text-gray-600 dark:text-neutral-400">
          Developing brochures, business cards, posters, and packaging design for both physical and digital distribution, e.g.
        </p>

        <ul class="list-disc ms-6 mt-3 space-y-1.5">
          <li class="text-gray-600 ps-1 dark:text-neutral-400">
            Folded pamphlets or booklets that provide detailed information about products, services, or events, often used for marketing purposes.
          </li>
          <li class="text-gray-600 ps-1 dark:text-neutral-400">
            Large-format prints designed to be displayed in public spaces, often used for advertising events, movies, or products.
          </li>
          <li class="text-gray-600 ps-1 dark:text-neutral-400">
            Creative design for product packaging, including boxes, bags, labels, and wrappers that visually appeal to consumers while conveying brand identity.
          </li>
          <li class="text-gray-600 ps-1 dark:text-neutral-400">
            Custom-designed invitations for weddings, parties, corporate events, and other occasions, often featuring elegant typography and imagery.
          </li>
          <li class="text-gray-600 ps-1 dark:text-neutral-400">
            Large outdoor advertising prints designed to catch the attention of drivers and pedestrians, typically located in high-traffic areas.
          </li>
        </ul>
      </div>
      <!-- End Right Content -->
    </div>
    <!-- End Item -->

    <!-- Item -->
    <div class="relative flex group gap-x-5">

      <!-- Icon -->
      <div
        class="relative group-last:after:hidden after:absolute after:top-8 after:bottom-2 after:start-3 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-neutral-700">
        <div
          class="relative z-10 flex items-center justify-center size-6">
          <IconFingerprint
            class="text-gray-600 shrink-0 size-6 dark:text-neutral-400"
            size="32" />
        </div>
      </div>
      <!-- End Icon -->

      <!-- Right Content -->
      <div class="pb-8 grow group-last:pb-0">

        <!-- <h3 class="mb-1 text-xs text-gray-600 dark:text-neutral-400">
          2021 - 2023
        </h3> -->

        <p class="font-semibold text-gray-800 dark:text-neutral-200">
          Branding and Identity
        </p>

        <p class="mt-1 text-gray-600 dark:text-neutral-400">
          Crafting cohesive brand identities, including logos, color schemes, and typography,
          for companies to establish a strong market presence.
        </p>

        <!-- <div class="mt-3"> -->
          <!-- Card -->
          <!-- <a
            class="block border border-gray-200 rounded-lg hover:shadow-sm focus:outline-none dark:border-neutral-700"
            href="#">
            <div class="relative flex items-center overflow-hidden">
              <img
                class="absolute inset-0 object-cover w-32 h-full sm:w-48 rounded-s-lg"
                src="/assets/ux.PNG" alt="Blog Image">

              <div class="p-4 grow ms-32 sm:ms-48">
                <div class="flex flex-col justify-center min-h-24">
                  <h3 class="font-semibold text-gray-800 dark:text-neutral-300">
                    Studio by Mailchimp
                  </h3>
                  <p class="mt-1 text-gray-500 dark:text-neutral-500">
                    Produce professional, reliable streams easily leveraging Mailchimp's innovative broadcast studio.
                  </p>
                </div>
              </div>
            </div>
          </a> -->
          <!-- End Card -->
        <!-- </div> -->
      </div>
      <!-- End Right Content -->
    </div>
    <!-- End Item -->

    <!-- Item -->
    <div class="relative flex group gap-x-5">

      <!-- Icon 36C5F0 2EB67D ECB22E E01E5A -->
      <div
        class="relative group-last:after:hidden after:absolute after:top-8 after:bottom-2 after:start-3 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-neutral-700">

        <div class="relative z-10 flex items-center justify-center size-6">
          <IconDeviceGamepad2
            class="text-gray-600 shrink-0 size-6 dark:text-neutral-400" size="32" />
        </div>
      </div>
      <!-- End Icon -->

      <!-- Right Content -->
      <div class="pb-8 grow group-last:pb-0">
        <!-- <h3 class="mb-1 text-xs text-gray-600 dark:text-neutral-400">
          2011 - 2021
        </h3> -->

        <p class="font-semibold text-gray-800 dark:text-neutral-200">
          UI Design
        </p>

        <p class="mt-1 text-gray-600 dark:text-neutral-400">
          Specializing in creating intuitive and visually appealing interfaces for websites and mobile apps to enhance user experience.
        </p>
      </div>
      <!-- End Right Content -->
    </div>
    <!-- End Item -->

    <!-- Item -->
    <div class="relative flex group gap-x-5">
      <!-- Icon -->
      <div
        class="relative group-last:after:hidden after:absolute after:top-8 after:bottom-2 after:start-3 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-neutral-700">
        <div class="relative z-10 flex items-center justify-center size-6">
          <IconMovie
            class="text-gray-800 shrink-0 size-6 dark:text-neutral-200" size="24" />
        </div>
      </div>
      <!-- End Icon -->

      <!-- Right Content -->
      <div class="pb-8 grow group-last:pb-0">
        <!-- <h3 class="mb-1 text-xs text-gray-600 dark:text-neutral-400">
          2010 - 2011
        </h3> -->

        <p class="font-semibold text-gray-800 dark:text-neutral-200">
          Motion Graphics
        </p>

        <p class="mt-1 text-gray-600 dark:text-neutral-400">
          Incorporating animated elements in web design, promotional materials, or UI elements to create dynamic and engaging user interactions.
        </p>

        <!-- <ul class="list-disc ms-6 mt-3 space-y-1.5">
          <li class="text-gray-600 ps-1 dark:text-neutral-400">
            Worked with a diverse range of clients, delivering tailored design solutions.
          </li>

          <li class="text-gray-600 ps-1 dark:text-neutral-400">
            Developed and maintained strong client relationships through effective communication and bucket management.
          </li>

          <li class="text-gray-600 ps-1 dark:text-neutral-400">
            Utilized tools such as Notion for bucket tracking, Mailchimp for email marketing designs, Slack for team collaboration, and GitHub for version control and bucket sharing.
          </li>
        </ul> -->

      </div>
      <!-- End Right Content -->
    </div>
    <!-- End Item -->
  </div>
  <!-- End Timeline -->
</div>
</template>
