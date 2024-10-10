<script setup lang="ts">
import InputLabel from '@/Components/InputLabel.vue';

import MazPhoneNumberInput, { CountryCode } from 'maz-ui/components/MazPhoneNumberInput';

import PreTap from '@/Components/PreTap.vue';

import TextInput from '@/Components/TextInput.vue';

import AppLayout from '@/Layouts/AppLayout.vue';

import { Head, useForm } from '@inertiajs/vue3';

import { onMounted, ref } from 'vue';

import InputError from '@/Components/InputError.vue';

import { gsap } from 'gsap';

import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

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

const mainRef = ref(null);

onMounted(() => {
  const main = mainRef.value as any;
  const title = main.querySelector('.title-section');
  const formFields = main.querySelectorAll('form > div');

  // Animate title
  gsap.from(title.children, {
    opacity: 0,
    y: 30,
    duration: 0.8,
    stagger: 0.2,
    ease: "power2.out"
  });

  // Animate form fields
  gsap.from(formFields, {
    opacity: 0,
    y: 20,
    duration: 0.6,
    stagger: 0.1,
    delay: 0.5,
    scrollTrigger: {
      trigger: formFields[0],
      start: 'top bottom-=100',
      toggleActions: 'play none none reverse'
    }
  });

  // Animate submit button
  gsap.from('button[type="submit"]', {
    opacity: 0,
    y: 20,
    duration: 0.6,
    delay: 1,
    scrollTrigger: {
      trigger: 'button[type="submit"]',
      start: 'top bottom-=50',
      toggleActions: 'play none none reverse'
    }
  });
});

defineOptions({
  layout: AppLayout
})
</script>

<template>

  <Head title="Contact Me" />

  <article
    class="w-full max-w-2xl px-4 pt-10 mx-auto md:pt-16 sm:px-6 lg:px-8"
    ref="mainRef">

     <!-- Title -->
     <div class="w-full mb-10 lg:mb-14 title-section">

      <h2 class="text-2xl font-semibold dark:text-white text-neutral-800 md:text-4xl md:leading-tight">
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

  </article>

</template>
