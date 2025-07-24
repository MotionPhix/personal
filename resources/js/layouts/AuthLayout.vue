<script setup lang="ts">
import {ref} from 'vue';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import NavLink from '@/components/NavLink.vue';
import ResponsiveNavLink from '@/components/ResponsiveNavLink.vue';
import NotificationContainer from '@/components/ui/NotificationContainer.vue';
import {Link, router} from '@inertiajs/vue3';
import ToastList from '@/components/backend/ToastList.vue';
import {useDark} from '@vueuse/core';
import Footnote from '@/components/front/Footnote.vue';
import {
  IconPlus,
  IconUserPlus,
  IconBuildingEstate,
  IconBalloon,
} from "@tabler/icons-vue";
import {ModalLink} from '@inertiaui/modal-vue';
import {DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger} from "@/components/ui/dropdown-menu";
import {Toaster} from "vue-sonner";
import {Button} from "@/components/ui/button";
import {Power} from 'lucide-vue-next'

const showingNavigationDropdown = ref(false);

const isDark = useDark()

</script>

<template>

  <div>

    <ToastList/>
    <Toaster rich-colors position="top-right"/>
    <NotificationContainer/>

    <div class="min-h-screen">
      <nav class="bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700">
        <!-- Primary Navigation Menu -->
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="flex items-center shrink-0">
                <Link :href="route('admin.dashboard')">
                  <ApplicationLogo class="block w-auto text-gray-800 fill-current h-9 dark:text-gray-200"/>
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
                  :href="route('admin.customers.index')"
                  :active="$page.url.startsWith('/admin/customers')">
                  Customers
                </NavLink>

                <NavLink
                  :href="route('admin.projects.index')"
                  :active="$page.url.startsWith('/admin/projects')">
                  Projects
                </NavLink>

                <NavLink
                  :href="route('admin.downloads.index')"
                  :active="$page.url.startsWith('/admin/downloads')">
                  Downloads
                </NavLink>
              </div>

              <div class="flex-1 inline-block sm:hidden"/>

              <div class="flex items-center ml-6">
                <DropdownMenu>
                  <DropdownMenuTrigger>
                    <IconPlus
                      class="text-gray-500 transition duration-200 size-8 sm:size-6"
                      aria-hidden="true"
                      stroke="2"
                    />
                  </DropdownMenuTrigger>

                  <DropdownMenuContent align="start" :side-offset="-32">
                    <DropdownMenuItem
                      @click="router.visit(route('admin.customers.create'), { preserveScroll: true })">
                      <IconUserPlus
                        class="text-violet-400 size-7"
                        aria-hidden="true"
                      />

                      <span>Add customer</span>
                    </DropdownMenuItem>

                    <DropdownMenuItem
                      @click="router.visit(route('auth.projects.create'), { preserveScroll: true })">
                      <IconBuildingEstate
                        class="text-violet-400 size-7"
                        aria-hidden="true"
                      />
                      <span>Add project</span>
                    </DropdownMenuItem>

                    <DropdownMenuItem as-child>
                      <ModalLink
                        as="button"
                        class="w-full"
                        :href="route('admin.downloads.create')">
                        <IconBalloon
                          class="text-violet-400 size-7"
                          aria-hidden="true"
                        />
                        <span>Add logo</span>
                      </ModalLink>
                    </DropdownMenuItem>

                  </DropdownMenuContent>

                </DropdownMenu>
              </div>

            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
              <!-- Settings Dropdown -->
              <div class="relative ms-3">
                <DropdownMenu>
                  <DropdownMenuTrigger>
                    <Button>
                      {{ `${$page.props.auth.user.first_name} ${$page.props.auth.user.last_name}` }}
                    </Button>
                  </DropdownMenuTrigger>

                  <DropdownMenuContent>
                    <DropdownMenuItem
                      @click="() => router.visit(route('admin.profile.edit'))">
                      Profile
                    </DropdownMenuItem>

                    <DropdownMenuItem
                      @click="() => router.post(route('logout'))">
                      <Power />
                      Sign out
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
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
                        d="M4 6h16M4 12h16M4 18h16"/>
                  <path :class="{
                    hidden: !showingNavigationDropdown,
                    'inline-flex': showingNavigationDropdown,
                  }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"/>
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
            <ResponsiveNavLink :href="route('admin.customers.index')"
                               :active="$page.url.startsWith('/admin/customers')">
              Customers
            </ResponsiveNavLink>
          </div>

          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink :href="route('admin.projects.index')"
                               :active="$page.url.startsWith('/admin/projects')">
              Projects
            </ResponsiveNavLink>
          </div>

          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink
              :href="route('admin.downloads.index')"
              :active="$page.url.startsWith('/admin/downloads')">
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
              <ResponsiveNavLink :href="route('admin.profile.edit')">
                Profile
              </ResponsiveNavLink>

              <ResponsiveNavLink
                :href="route('logout')"
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
        <slot/>
      </main>

      <Footnote/>

    </div>

  </div>

</template>
