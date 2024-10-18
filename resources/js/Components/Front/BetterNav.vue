<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { IconBalloon, IconBuildingEstate, IconFileSymlink, IconMenu, IconMenu4, IconPlus, IconUserPlus, IconX } from '@tabler/icons-vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { ModalLink } from '@inertiaui/modal-vue';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';

</script>

<template>
  <nav
    class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-neutral-200 dark:border-neutral-800">

    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto px-4 py-1">

      <!-- <a href="https://flowbite.com/"
         class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="https://flowbite.com/docs/images/logo.svg"
             class="h-8"
             alt="Flowbite Logo">
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Flowbite</span>
      </a> -->


      <Link
        class="flex items-center gap-2 text-xl font-semibold rounded-md dark:text-gray-300 focus:outline-none focus:opacity-80" :href="route('home')" aria-label="Ultrashots">
        <ApplicationLogo class="w-7" />
        <span class="text-2xl ">ultrashots</span>
      </Link>

      <div
        class="flex md:order-2 gap-3 md:gap-4">

        <ModalLink
          as="button"
          position="top"
          :href="route('fix-my-logo')"
          class="text-white flex items-center gap-2 bg-lime-700 hover:bg-lime-800 focus:ring-4 focus:outline-none focus:ring-lime-300 font-medium rounded-md text-sm px-2 sm:px-4 py-2 text-center dark:bg-lime-600 dark:hover:bg-lime-700 dark:focus:ring-lime-800">
          <IconFileSymlink class="size-5" />
          <span>Fix My Logo</span>
        </ModalLink>

        <!-- <button
          data-collapse-toggle="navbar-sticky"
          type="button"
          class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
          aria-controls="navbar-sticky"
          aria-expanded="false">

          <span class="sr-only">Open main menu</span>

          <IconMenu4 stroke="2.5" class="hs-collapse-open:hidden shrink-0 size-6" />

          <IconX stroke="2.5" class="hs-collapse-open:block hidden shrink-0 size-5" />

        </button> -->

        <Menu as="div" class="relative z-40 sm:hidden inline-block mt-2 text-left">

          <div>

            <MenuButton
              class="inline-flex justify-center w-full"
            >

              <IconMenu
                stroke="2.5"
                class="shrink-0 size-6 text-neutral-600 dark:text-neutral-300 transition duration-200 sm:size-6" />

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
              class="absolute right-0 w-40 bg-white divide-y divide-gray-100 rounded-md shadow-lg sm:right-0 ring-1 ring-black/5 focus:outline-none"
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

      </div>

      <div
        class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1"
        id="navbar-sticky">

        <ul
          class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">

          <li>
            <a href="#"
               class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500"
               aria-current="page">Home</a>
          </li>

          <li>
            <a href="#"
               class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">About</a>
          </li>

          <li>
            <a href="#"
               class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Services</a>
          </li>

          <li>
            <a href="#"
               class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Contact</a>
          </li>

        </ul>

      </div>

    </div>

  </nav>
</template>
