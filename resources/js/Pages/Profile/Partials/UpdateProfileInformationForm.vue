<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import MazBtn from 'maz-ui/components/MazBtn'
import MazInput from 'maz-ui/components/MazInput'
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
  mustVerifyEmail?: Boolean;
  status?: String;
}>();

const user = usePage().props.auth.user;

const form = useForm({
  first_name: user.first_name,
  last_name: user.last_name,
  email: user.email,
});
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Information</h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        Update your account's profile information and email address.
      </p>
    </header>

    <form
      @submit.prevent="form.patch(route('profile.update'))"
      class="mt-6 space-y-6">
      <div>
        <InputLabel
          for="first_name"
          value="First Name" />

        <MazInput
          id="first_name"
          type="text"
          class="mt-1"
          v-model="form.first_name"
          required
          autofocus
          rounded-size="md"
          size="lg"
          block />

        <InputError
          class="mt-2"
          :message="form.errors.first_name" />
      </div>

      <div>
        <InputLabel
          for="last_name"
          value="Last Name" />

        <MazInput
          id="last_name"
          type="text"
          class="mt-1"
          v-model="form.last_name"
          rounded-size="md"
          size="lg"
          block
          required/>

        <InputError
          class="mt-2"
          :message="form.errors.last_name" />
      </div>

      <div>
        <InputLabel
          for="email"
          value="Email" />

        <MazInput
          id="email"
          type="email"
          class="mt-1"
          v-model="form.email"
          rounded-size="md"
          size="lg"
          block
          required />

        <InputError
          class="mt-2"
          :message="form.errors.email" />
      </div>

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
          Your email address is unverified.
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
            Re-send the verification email.
          </Link>
        </p>

        <div
          v-show="status === 'verification-link-sent'"
          class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">
          A new verification link has been sent to your email address.
        </div>
      </div>

      <div class="flex items-center gap-4">
        <MazBtn
        :disabled="form.processing"
          rounded-size="md"
          size="md">
        Save
      </MazBtn>

        <Transition enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0">
          <p v-if="form.recentlySuccessful"
             class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
        </Transition>
      </div>
    </form>
  </section>
</template>
