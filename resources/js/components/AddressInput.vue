<script setup>
import InputError from '@/components/InputError.vue';

import MazInput from 'maz-ui/components/MazInput'

import { usePage } from '@inertiajs/vue3';

import { onMounted } from 'vue';

const address = defineModel()

const page = usePage()

onMounted(() => {
  if (! address.value.street) {
    address.value = {
      street: '',
      city: '',
      state: '',
      country: '',
    }
  }
})

function onChangeType(type) {
  address.value.type = type;
}
</script>

<template>
  <div class="relative space-y-2 group first-letter:uppercase">
    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
      {{ address.type }} address
    </label>

    <section class="grid grid-cols-2 gap-6">

      <div class="col-span-2 sm:col-span-1">
        <MazInput
          type="text"
          v-model="address.street"
          placeholder="Enter street name"
          rounded-size="md"
          size="lg"
          block />

        <InputError :message="page.props.errors[`address.street`]" />
      </div>

      <div class="col-span-2 sm:col-span-1">
        <MazInput
          type="text"
          v-model="address.city"
          placeholder="Enter city name"
          rounded-size="md"
          size="lg"
          block />

        <InputError :message="page.props.errors[`address.city`]" />
      </div>

      <div class="col-span-2 sm:col-span-1">
        <MazInput
          type="text"
          v-model="address.state"
          placeholder="Enter state/region name"
          rounded-size="md"
          size="lg"
          block />

        <InputError :message="page.props.errors[`address.state`]" />
      </div>

      <div class="col-span-2 sm:col-span-1">

        <MazInput
          type="text"
          v-model="address.country"
          placeholder="Enter country name"
          rounded-size="md"
          size="lg"
          block />

        <InputError :message="page.props.errors[`address.country`]" />
      </div>

    </section>

    <InputError :message="page.props.errors[`address.type`]" />
  </div>

</template>
