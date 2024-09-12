<script setup lang="ts">

import { Head, Link } from '@inertiajs/vue3';
import Navheader from '@/Components/Backend/Navheader.vue';
import AuthLayout from "@/Layouts/AuthLayout.vue";
import Footnote from "@/Components/Front/Footnote.vue";
import { IconDeviceProjector, IconInbox, IconDots, IconPhotoX, IconPencil, IconTableShortcut } from '@tabler/icons-vue';
import {Project} from "@/types";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import IconContacts from "@/Components/Icon/IconContacts.vue";

const props = defineProps<{
  projects: Project[],
}>()

defineOptions({
  layout: AuthLayout
})
</script>

<template>

  <Head title="Available Projects" />

  <Navheader>

    <nav
      class="flex items-center w-full gap-6 dark:text-white dark:border-gray-700">

      <Link
        as="button"
        v-if="projects.length"
        :href="route('auth.projects.create')"
        class="flex font-semibold items-center text-xl gap-2 py-1 transition duration-300 hover:opacity-70 dark:text-gray-300"
      >
        <IconDeviceProjector class="w-8 h-8 stroke-current" /> <span>Add new</span>

      </Link>

      <h2 v-else class="text-xl font-semibold py-1.5">
        Explore projects
      </h2>

    </nav>

  </Navheader>

  <main class="max-w-2xl px-4 py-10 mx-auto sm:px-6 lg:px-8 lg:py-14">

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

      <!-- Card -->
      <section
        class="group flex flex-col h-full border border-gray-200 hover:border-transparent hover:shadow-lg focus:outline-none focus:border-transparent focus:shadow-lg transition duration-300 rounded-xl p-5 dark:border-neutral-700 dark:hover:border-transparent dark:hover:shadow-black/40 dark:focus:border-transparent dark:focus:shadow-black/40"
        v-for="project in projects"
        :key="project.pid"
      >
        <div class="mt-auto flex items-center gap-x-3 px-2 pb-4">

          <div class="rounded-full bg-gray-300 p-2">
            <IconContacts
              class="size-5" />
          </div>

          <div>
            <h5 class="text-sm text-gray-800 dark:text-neutral-200">
              {{ `${project.customer?.first_name} ${project.customer?.last_name}` }}
            </h5>
          </div>

          <div class="flex-1"></div>

          <Menu
            as="div"
            class="relative text-left rounded-full z-40">

            <MenuButton
              class="inline-flex w-full justify-center"
            >
              <IconDots
                class="size-5 text-gray-700 dark:text-gray-300"
                aria-hidden="true"
              />
            </MenuButton>

            <transition
              enter-active-class="transition duration-100 ease-out"
              enter-from-class="transform scale-95 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-75 ease-in"
              leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0"
            >
              <MenuItems
                class="absolute -right-2 -mt-1 w-24 origin-bottom-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
              >
                <div class="px-1 py-1">
                  <MenuItem v-slot="{ active }">
                    <Link
                      as="button"
                      :class="[
                        active ? 'bg-violet-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                      :href="route('auth.projects.edit', project.pid)"
                    >
                      <IconPencil
                        :active="active"
                        class="mr-2 h-5 w-5 text-violet-400"
                        aria-hidden="true"
                      />
                      Edit
                    </Link>
                  </MenuItem>

                  <MenuItem v-slot="{ active }">
                    <Link
                      as="button"
                      method="delete"
                      :class="[
                        active ? 'bg-violet-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                      :href="route('auth.projects.destroy', { project: project.id })"
                    >
                      <IconPhotoX
                        :active="active"
                        class="mr-2 h-5 w-5 text-violet-400"
                        aria-hidden="true"
                      />
                      Delete
                    </Link>
                  </MenuItem>
                </div>

              </MenuItems>

            </transition>

          </Menu>

        </div>

        <span class="flex-1 block" />

        <Link
          class="aspect-w-16 aspect-h-11"
          :href="route('auth.projects.detail', project.pid)">
          <img
            class="w-full object-cover rounded-xl"
            :src="project.poster as any" alt="Project Image">
        </Link>

        <Link
          class="px-2 pt-4"
          :href="route('auth.projects.detail', project.pid)">
          <p class="mt-5 text-gray-600 leading-tight dark:text-neutral-400">
            {{ project.name }}
          </p>
        </Link>

      </section>
      <!-- End Card -->

    </div>
    <!-- End Grid -->

  </main>

  <Footnote />
</template>
