<script setup lang="ts">
import InputLabel from '@/Components/InputLabel.vue';

import MazPhoneNumberInput, { CountryCode } from 'maz-ui/components/MazPhoneNumberInput';

import PreTap from '@/Components/PreTap.vue';

import TextInput from '@/Components/TextInput.vue';

import AppLayout from '@/Layouts/AppLayout.vue';

import { Head, useForm } from '@inertiajs/vue3';

import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';

const contactForm = useForm({
  name: '',
  email: '',
  phone: '',
  company: '',
  message: '',
})

const countryCode = ref<CountryCode>('MW')

const onSubmit = () => {

  contactForm.post(route('contact.send'), {
    preserveScroll: true,
    onSuccess: () => {
      contactForm.reset();
    }
  })

}

defineOptions({
  layout: AppLayout
})
</script>

<template>

  <Head title="Contact Me" />

  <main class="w-full max-w-2xl px-4 pt-10 mx-auto md:pt-16 sm:px-6 lg:px-8">

     <!-- Title -->
     <div class="max-w-3xl mb-10 lg:mb-14">

      <h2 class="text-2xl font-semibold dark:text-white text-neutral-500 md:text-4xl md:leading-tight">
        Contact me
      </h2>

      <p class="mt-1 text-neutral-400">
        I'd love to talk about how I can help you.
      </p>

    </div>
    <!-- End Title -->

    <form
      @submit.prevent="onSubmit"
      class="flex flex-col gap-6">

      <div>
        <InputLabel
          value="Full name"
          for="name" />

        <TextInput
          placeholder="Enter your full name"
          name="name"
          id="name"
          v-model="contactForm.name"
          class="w-full my-2" />

        <InputError :message="contactForm.errors.name" />

      </div>

      <div>

        <InputLabel
          for="email"
          value="Email address" />

        <TextInput
          placeholder="Enter your email address"
          name="email"
          id="email"
          v-model="contactForm.email"
          class="w-full my-2" />

        <InputError :message="contactForm.errors.email" />

      </div>

      <div>

        <InputLabel
          for="phone"
          value="Phone" />

        <MazPhoneNumberInput
          :show-code-on-list="false"
          v-model="contactForm.phone"
          v-model:country-code="countryCode"
          class="w-full my-2"
          placeholder="Enter your phone number"
          no-country-selector
          no-example
          :size="'xl'"
          block
          rounded-size="md"
          orientation="responsive"
          :preferred-countries="['MW', 'ZM', 'ZA', 'ZW', 'GB', 'US']"
        />

        <InputError :message="contactForm.errors.phone" />

      </div>

      <div>
        <InputLabel
          for="company"
          value="Company" />

        <TextInput
          id="company"
          class="w-full my-2"
          placeholder="Enter your company name"
          v-model="contactForm.company"
        />

      </div>

      <div class="flex flex-col gap-4">
        <InputLabel
          for="message"
          value="Message"
          class="mb-1" />

        <PreTap
          v-model="contactForm.message"
          placeholder="How can I assist you? Be a bit verbose" />

        <p
          class="text-sm text-red-500"
          v-if="contactForm.errors.message">
          {{ contactForm.errors.message }}
        </p>
      </div>

      <div>

        <button
          type="submit"
          class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm gap-x-2 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
          Send Message
          <svg
            class="shrink-0 size-4"
            xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M5 12h14"></path>
            <path d="m12 5 7 7-7 7"></path>
          </svg>
        </button>

      </div>

    </form>

  </main>

</template>
