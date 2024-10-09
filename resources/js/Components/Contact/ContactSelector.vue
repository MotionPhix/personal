<script setup>
import { ref, computed, onMounted } from 'vue'

import { IconCheck, IconPlus, IconSelector } from '@tabler/icons-vue'

import { router } from '@inertiajs/vue3'

import { ModalLink } from '@inertiaui/modal-vue'

import axios from 'axios';

import {
  Combobox,
  ComboboxInput,
  ComboboxButton,
  ComboboxOptions,
  ComboboxOption,
  TransitionRoot,
} from '@headlessui/vue'

const props = defineProps({
  modelValue: {
    type: [Number, String],
    default: '',
  },

  placeholder: {
    type: String,
    default: 'Pick a contact person'
  }
})

const emit = defineEmits(['update:modelValue'])

const people = ref([])

let query = ref('')

let filteredPeople = computed(() =>
  query.value === ''
    ? people.value
    : people.value.filter((person) =>
        person.name
          .toLowerCase()
          .replace(/\s+/g, '')
          .includes(query.value.toLowerCase().replace(/\s+/g, ''))
      )
)

async function onFetchContacts() {

  await axios.get(route('api.customers.index')).then((resp) => {

    resp.data.contacts.forEach((contact, idx) => {

      const firm = contact.firm ? ` | ${contact.firm.name}` : '';

      people.value[idx] = {

        id: contact.cid,

        name: `${contact.first_name} ${contact.last_name}${firm}`,

      };

    });

  });

}

onMounted(() => {

  onFetchContacts()

})

const onFetchSelectedPersonName = (id) => {

  const person = people.value.find(person => person.id === id);

  return person ? person.name : '';

};
</script>

<template>

    <Combobox
      :modelValue="modelValue"
      @update:modelValue="value => emit('update:modelValue', value)"
      by="id">

      <div class="relative mt-1">

        <div>
          <ComboboxInput
            class="w-full py-3.5 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
            :placeholder="props.placeholder"
            :value="onFetchSelectedPersonName(props.modelValue)"
            @change="query = $event.target.value"
          />



          <div
            class="absolute inset-y-0 right-7 flex items-center z-10">

            <ModalLink
              :href="route('auth.customer.create')"
              class="flex items-center w-full bg-gray-700 rounded-xl"
              :data="{ 'modal': 'show' }"
              @close="onFetchContacts"
              preserve-scroll
              as="button">

              <span
                class="left-0 block text-gray-300 truncate font-semibold dark:text-gray-300">
                <IconPlus class="size-5" />
              </span>
            </ModalLink>

          </div>

          <ComboboxButton
            class="absolute inset-y-0 right-0 flex items-center pr-2"
          >
            <IconSelector
              class="w-5 h-5 text-gray-400"
              aria-hidden="true"
              stroke="2.5"
            />
          </ComboboxButton>
        </div>

        <TransitionRoot
          leave="transition ease-in duration-100"
          leaveFrom="opacity-100"
          leaveTo="opacity-0"
          @after-leave="query = ''"
        >
          <ComboboxOptions
            class="absolute z-20 w-full py-1 mt-1 overflow-auto text-base bg-white rounded-md shadow-lg dark:bg-gray-800 dark:text-white max-h-36 ring-1 ring-black/5 focus:outline-none sm:text-sm"
          >
            <div
              v-if="filteredPeople.length === 0 && query !== ''"
              class="relative px-4 py-2 text-gray-700 cursor-default select-none"
            >
              Nothing found.
            </div>

            <ComboboxOption
              v-for="person in filteredPeople"
              as="template"
              :key="person.id"
              :value="person.id"
            >

              <li
                class="relative py-2 pl-10 pr-4 cursor-default select-none dark:hover:bg-gray-700 group"
                :class="{ 'dark:bg-lime-500': person.id === props.modelValue }">
                <span
                  class="block truncate"
                  :class="{ 'font-semibold dark:text-gray-900 dark:group-hover:text-white': person.id === props.modelValue }">
                  {{ person.name }}
                </span>

                <span
                  v-if="person.id === props.modelValue"
                  class="absolute inset-y-0 left-0 flex items-center pl-3 dark:text-gray-900 dark:group-hover:text-white">
                  <IconCheck stroke="2.5" class="w-5 h-5" aria-hidden="true" />
                </span>
              </li>

            </ComboboxOption>

          </ComboboxOptions>

        </TransitionRoot>

      </div>

    </Combobox>
</template>
