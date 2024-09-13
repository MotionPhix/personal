<script setup lang="ts">
import InputLabel from '@/Components/InputLabel.vue';
import PreTap from '@/Components/PreTap.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const contactForm = useForm({
  name: '',
  email: '',
  phone: '',
  message: '',
})

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

      <h2 class="dark:text-white text-neutral-500 font-semibold text-2xl md:text-4xl md:leading-tight">
        Contact us
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
          class="mt-1 w-full" />

        <p class="text-red-500 text-sm" v-if="contactForm.errors.name">
          {{ contactForm.errors.name }}
        </p>

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
          class="mt-1 w-full" />

        <p class="text-red-500 text-sm" v-if="contactForm.errors.email">
          {{ contactForm.errors.email }}
        </p>

      </div>

      <div>

        <InputLabel
          for="phone"
          value="Phone" />

        <vue-tel-input
          class="mt-1 w-full"
          v-model="contactForm.phone" />

        <p class="text-red-500 text-sm" v-if="contactForm.errors.phone">
          {{ contactForm.errors.phone }}
        </p>

      </div>

      <div>
        <InputLabel
          for="message"
          value="Message"
          class="mb-1" />

        <PreTap
          v-model="contactForm.message"
          placeholder="How can I assist you? Be a bit verbose" />

        <p
          class="text-red-500 text-sm"
          v-if="contactForm.errors.message">
          {{ contactForm.errors.message }}
        </p>
      </div>

      <!-- <button
        type="submit"
        class="bg-blue-500 text-white py-2 px-4">
        Send Message
      </button> -->

      <div>

        <button
          type="submit"
          class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
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
