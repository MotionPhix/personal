<script setup lang="ts">
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import ToastList from '@/Components/Backend/ToastList.vue';
import { useDark } from '@vueuse/core';
import Footnote from '@/Components/Front/Footnote.vue';
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import {
  IconPlus,
  IconUserPlus,
  IconBuildingEstate,
  IconBalloon,
} from "@tabler/icons-vue";

const showingNavigationDropdown = ref(false);

const isDark = useDark()

</script>

<template>

  <div>

    <ToastList />

    <div class="min-h-screen">
      <nav class="bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700">
        <!-- Primary Navigation Menu -->
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="flex items-center shrink-0">
                <Link :href="route('dashboard')">
                <ApplicationLogo class="block w-auto text-gray-800 fill-current h-9 dark:text-gray-200" />
                </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <NavLink
                  :href="route('dashboard')"
                  :active="route().current('dashboard')">
                  Dashboard
                </NavLink>

                <NavLink
                  :href="route('auth.customer.index')"
                  :active="$page.url.startsWith('/auth/customers')">
                  Customers
                </NavLink>

                <NavLink
                  :href="route('auth.projects.index')"
                  :active="$page.url.startsWith('/auth/projects')">
                  Projects
                </NavLink>

                <NavLink
                  :href="route('auth.downloads.index')"
                  :active="$page.url.startsWith('/auth/downloads')">
                  Downloads
                </NavLink>
              </div>

              <div class="flex-1 inline-block sm:hidden" />

              <div class="flex items-center ml-6">
                <Menu as="div" class="relative z-40 inline-block mt-2 text-left">
                  <div>
                    <MenuButton
                      class="inline-flex justify-center w-full"
                    >
                      <IconPlus
                        class="text-gray-500 transition duration-200 size-8 sm:size-6"
                        aria-hidden="true"
                        stroke="2"
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
                      class="absolute left-0 w-40 -mt-10 bg-white divide-y divide-gray-100 rounded-md shadow-lg sm:right-0 ring-1 ring-black/5 focus:outline-none"
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

            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
              <!-- Settings Dropdown -->
              <div class="relative ms-3">
                <Dropdown align="right"
                          width="48">
                  <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button type="button"
                              class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                        {{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}

                        <svg class="ms-2 -me-0.5 h-4 w-4"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20"
                             fill="currentColor">
                          <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                    <DropdownLink :href="route('logout')"
                                  method="post"
                                  as="button">
                      Log Out
                    </DropdownLink>
                  </template>
                </Dropdown>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -me-2 sm:hidden">
              <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                      class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400">
                <svg class="w-6 h-6"
                     stroke="currentColor"
                     fill="none"
                     viewBox="0 0 24 24">
                  <path :class="{
                    hidden: showingNavigationDropdown,
                    'inline-flex': !showingNavigationDropdown,
                  }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                  <path :class="{
                    hidden: !showingNavigationDropdown,
                    'inline-flex': showingNavigationDropdown,
                  }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
             class="sm:hidden">
          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink :href="route('dashboard')"
                               :active="route().current('dashboard')">
              Dashboard
            </ResponsiveNavLink>
          </div>

          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink :href="route('auth.customer.index')"
                               :active="$page.url.startsWith('/auth/customers')">
              Customers
            </ResponsiveNavLink>
          </div>

          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink :href="route('auth.projects.index')"
                               :active="$page.url.startsWith('/auth/projects')">
              Projects
            </ResponsiveNavLink>
          </div>

          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink :href="route('auth.downloads.index')"
                              :active="$page.url.startsWith('/auth/downloads')">
              Downloads
            </ResponsiveNavLink>
          </div>

          <!-- Responsive Settings Options -->
          <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
              <div class="text-base font-medium text-gray-800 dark:text-gray-200">
                {{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}
              </div>

              <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
            </div>

            <div class="mt-3 space-y-1">
              <ResponsiveNavLink :href="route('profile.edit')">
                Profile
              </ResponsiveNavLink>

              <ResponsiveNavLink :href="route('logout')"
                                 method="post"
                                 as="button">
                Log Out
              </ResponsiveNavLink>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Content -->
      <main>
        <slot />
      </main>

      <Footnote />

    </div>

  </div>

</template>
