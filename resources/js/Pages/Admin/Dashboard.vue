<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Navheader from "@/Components/Backend/Navheader.vue";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {
  IconPlus,
  IconUserPlus,
  IconBuildingEstate,
  IconBalloon,
} from "@tabler/icons-vue";
import Footnote from "@/Components/Front/Footnote.vue";

defineOptions({
  layout: AuthLayout
})
</script>

<template>
  <Head title="Dashboard" />

  <Navheader>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      Dashboard
    </h2>

    <span class="flex-1"></span>

    <Menu as="div" class="relative inline-block text-left z-40">

      <div>
        <MenuButton
          class="inline-flex w-full justify-center rounded-lg dark:bg-black/90 bg-black/20 p-1 text-sm font-medium text-white hover:bg-black/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
        >
          <IconPlus
            class="text-gray-600 transition duration-200"
            aria-hidden="true"
            stroke="3"
            size="24"
          />
        </MenuButton>
      </div>

      <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
      >
        <MenuItems
          class="absolute right-0 mt-2 w-40 divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
        >
          <div class="px-1 py-1">
            <MenuItem v-slot="{ active }">
              <Link
                as="button"
                :class="[
                  active ? 'bg-violet-500 text-white' : 'text-gray-900',
                  'group flex gap-2 w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
                :href="route('auth.customer.create')"
              >
                <IconUserPlus
                  :active="active"
                  class="text-violet-400 size-7"
                  aria-hidden="true"
                />

                <span>Add customer</span>
              </Link>
            </MenuItem>

            <MenuItem v-slot="{ active }">
              <Link
                as="button"
                :class="[
                  active ? 'bg-violet-500 text-white' : 'text-gray-900',
                  'group flex gap-2 w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
                :href="route('auth.projects.create')"
              >
                <IconBuildingEstate
                  :active="active"
                  class="text-violet-400 size-7"
                  aria-hidden="true"
                />
                <span>Add project</span>
              </Link>
            </MenuItem>

            <MenuItem v-slot="{ active }">
              <Link
                as="button"
                :class="[
                  active ? 'bg-violet-500 text-white' : 'text-gray-900',
                  'group flex gap-2 w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
                :href="route('auth.downloads.create')"
              >
                <IconBalloon
                  :active="active"
                  class="text-violet-400 size-7"
                  aria-hidden="true"
                />
                <span>Add logo</span>
              </Link>
            </MenuItem>
          </div>

        </MenuItems>

      </transition>

    </Menu>

  </Navheader>

  <main class="w-full max-w-2xl px-4 py-10 mx-auto md:pt-16 sm:px-6 lg:px-8">
    <div class="w-full mx-auto">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
              You're logged in!
            </div>
        </div>
    </div>
  </main>

  <Footnote />

</template>
