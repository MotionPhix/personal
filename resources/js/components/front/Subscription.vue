<script setup lang="ts">
import {useForm} from '@inertiajs/vue3'

const form = useForm({
  email: null,
})

function onSubmit() {
  form.post('/subscribe', {
    preserveScroll: true,
    onSuccess: () => form.reset('email'),
  })
}

</script>

<template>
  <section class="relative mx-auto max-w-7xl overflow-hidden rounded-lg shadow-lg">
    <!-- Background image with overlay -->
    <div class="absolute inset-0">
      <img
        class="h-full w-full object-cover"
        src="https://images.unsplash.com/photo-1665686377065-08ba896d16fd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=700&h=800&q=80"
        alt="Subscribe Background">
      <div
        class="absolute inset-0 bg-gradient-to-b from-white/55 to-white/70 dark:from-neutral-900/65 dark:to-neutral-900/40"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 px-4 py-12 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-3xl text-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-neutral-100 sm:text-3xl md:text-4xl">
          Stay Updated
        </h1>
        <p class="mt-4 text-base text-gray-600 dark:text-neutral-400 sm:text-lg">
          Join our mailing list to receive the latest news and updates.
        </p>
      </div>

      <div class="mx-auto mt-8 max-w-xl">
        <form @submit.prevent="onSubmit" class="space-y-4">
          <div
            class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white/50 p-2 backdrop-blur-sm dark:border-neutral-700 dark:bg-neutral-900/50 sm:flex-row sm:gap-2">
            <div class="relative flex-grow">
              <label for="hero-input" class="sr-only">Subscribe</label>
              <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                <svg
                  class="size-4 text-gray-400 dark:text-neutral-600"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor">
                  <rect width="20" height="16" x="2" y="4" rx="2"/>
                  <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
              </div>
              <input
                type="email"
                id="hero-input"
                name="hero-input"
                v-model="form.email"
                class="block w-full rounded-lg border-transparent bg-white px-3 py-2.5 ps-10 text-gray-900 placeholder-gray-500 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder-neutral-400"
                placeholder="Enter your email"
                required>
            </div>

            <button
              type="submit"
              :disabled="form.processing"
              class="inline-flex items-center justify-center gap-x-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 disabled:opacity-50 dark:bg-blue-600 dark:hover:bg-blue-500">
              <span>Subscribe</span>
              <svg
                class="size-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path d="M5 12h14"/>
                <path d="m12 5 7 7-7 7"/>
              </svg>
            </button>
          </div>

          <div class="text-center">
            <div v-if="form.errors.email" class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.email }}
            </div>
            <p v-else class="text-sm text-gray-500 dark:text-neutral-400">
              No spam, unsubscribe at any time.
            </p>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>
