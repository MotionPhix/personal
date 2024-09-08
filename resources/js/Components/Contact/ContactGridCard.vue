<script setup lang="ts">
import { useContactStore } from '@/Stores/contactStore';
import { Link, router } from '@inertiajs/vue3';
import { IconFileExport, IconPencil } from '@tabler/icons-vue';
import { storeToRefs } from 'pinia';
import { computed } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Customer } from '@/types';

const props = defineProps<{
  contact: Customer,
}>()

const contactStore = useContactStore()

const full_name = computed(() => `${props.contact?.first_name} ${props.contact?.last_name}`)

const param: any = computed(() => route().params)

const {

  selectedContacts

} = storeToRefs(contactStore)

const { selectContact, unselectContact, resetSelectedContacts } = contactStore

function isSelected(contactId?: string) {

  return (selectedContacts.value.includes(contactId));

}

function onContactSelect(contactId?: string) {

  if (isSelected(contactId)) {

    unselectContact(contactId)

  } else {

    selectContact(contactId)

  }
}

router.on('navigate', (e) => {

  if (e.detail.page.component !== 'Component/Compose' && selectedContacts.value.length > 0) {

    resetSelectedContacts()

  }

})
</script>

<template>
  <li
    class="relative flex px-4 py-3 transition duration-300 ease-in-out border border-gray-300 rounded-xl dark:border-gray-700 sm:py-4 hover:bg-gray-200 dark:hover:bg-gray-800 group"
    :class="{ 'bg-gray-300 dark:bg-gray-700': props.contact.cid === param.contact }">
    <div
      class="absolute z-20 flex items-center justify-center flex-shrink-0 w-10 h-10 font-semibold transition duration-300 rounded-full cursor-pointer hover:bg-transparent group"
      :class="selectedContacts.length ? '' : 'bg-lime-500 text-lime-900'">

      <span
        v-if="!selectedContacts.length"
        class="transition duration-300 empty:hidden group-hover:hidden">
        {{ `${props.contact.first_name[0]}${props.contact.last_name[0]}` }}
      </span>

      <span
        class="transition duration-300 group-hover:inline-flex"
        :class="selectedContacts.length ? 'inline-flex' : 'hidden'">
        <Checkbox
          @click="onContactSelect(props.contact.cid)"
          :checked="isSelected(props.contact.cid)" />
      </span>
    </div>

    <Link
      class="flex items-center w-full gap-6 pl-16 text-left"
      :href="route('auth.customer.edit', props.contact.cid)" as="button"
      preserve-scroll>

      <div class="flex-1 min-w-0">

        <p class="flex items-center gap-2 text-xl font-medium text-gray-900 truncate text-balance dark:text-white">

          <span>{{ full_name }}</span>

        </p>

        <strong
          class="block text-sm font-thin text-gray-500 truncate dark:text-gray-500"
          v-if="props.contact?.job_title">

          {{ props.contact?.job_title }}

        </strong>

      </div>

    </Link>

    <div
      class="absolute z-20 items-center justify-end hidden gap-2 px-2 pt-1 bg-gray-300 rounded cursor-pointer group-hover:flex dark:bg-neutral-700 bottom-2 right-2">

        <p class="text-sm text-gray-500 transition duration-300 hover:text-blue-500 dark:text-gray-400">
            <Link
              as="button"
              :href="route('auth.projects.create', props.contact.cid)">
              <IconFileExport stroke="2.5" class="w-6 h-6" />
            </Link>
        </p>

        <p
          class="text-sm text-gray-500 transition hover:text-blue-500 duration-00 dark:text-gray-400">
          <Link
            as="button"
            :href="route('auth.customer.edit', props.contact.cid)">

            <IconPencil stroke="2.5" class="w-6 h-6" />

          </Link>
        </p>

    </div>

  </li>
</template>
