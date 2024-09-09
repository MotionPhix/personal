<script setup lang="ts">
import IconContactAdd from '@/Components/Icon/IconContactAdd.vue';
import { useContactStore } from '@/Stores/contactStore';
import { Customer } from '@/types';
import { Link } from '@inertiajs/vue3';
import { IconPencil, IconTrash } from '@tabler/icons-vue';
import { storeToRefs } from 'pinia';

const props = defineProps<{
  contacts: Customer[]
}>()

const contactStore = useContactStore()

const {
  selectedContacts
} = storeToRefs(contactStore)
</script>

<template>
  <nav
    class="flex items-center w-full gap-6 dark:text-white dark:border-gray-700"
      v-if="!selectedContacts.length">

    <Link
      as="button"
      v-if="props.contacts.length"
      :href="route('auth.customer.create')"
      class="py-1.5 font-semibold transition duration-300 hover:opacity-70">

      <h2 class="flex items-center text-xl gap-2 dark:text-gray-300">

        <IconContactAdd class="size-5" /> <span>Add new</span>

      </h2>

    </Link>

    <h2 v-else class="text-xl font-semibold dark:text-gray-300 sm:inline-block">
      Explore customers
    </h2>

  </nav>

  <nav
    class="flex items-center w-full gap-6 dark:text-white dark:border-gray-700"
    v-if="selectedContacts.length && Object.keys(props.contacts).length">

    <Link
      as="button"
      :href="route('auth.customer.edit', selectedContacts[0])"
      v-if="selectedContacts.length === 1 && $page.url === '/auth/customers'"
      class="flex items-center gap-2 py-2 font-semibold transition duration-300 hover:opacity-70">
      <IconPencil class="w-5 h-5 stroke-current" /> <span class="hidden md:inline-flex">Edit</span>
    </Link>

    <Link
      as="button"
      method="delete"
      :href="route('auth.customer.index', { ids: selectedContacts } as any)"
      class="flex items-center gap-2 py-2 font-semibold transition duration-300 hover:opacity-70">
      <IconTrash class="w-5 h-5 stroke-current" /> <span class="hidden md:inline-flex">Delete</span>
    </Link>

  </nav>
</template>
