<script setup lang="ts">
import MazCheckbox from 'maz-ui/components/MazCheckbox'
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import MazBtn from 'maz-ui/components/MazBtn'
import MazInput from 'maz-ui/components/MazInput'
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
  canResetPassword?: boolean;
  status?: string;
}>();

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {

  form.post(route('login'), {

    onFinish: () => {

      form.reset('password');

    },

  });

};

defineOptions({
  layout: GuestLayout,
})
</script>

<template>

  <Head title="Log in" />

  <form
    @submit.prevent="submit"
    class="space-y-6">
    <div class="grid gap-y-2">
      <InputLabel
        for="email"
        value="Email"
      />

      <MazInput
        id="email"
        type="email"
        block
        autocomplete="username"
        placeholder="Enter your login email"
        v-model="form.email"
        rounded-size="md"
        color="success"
        autofocus
        size="lg"
        required
      />

      <InputError
        :message="form.errors.email"
      />
    </div>

    <div class="grid gap-y-2">
      <InputLabel
        for="password"
        value="Password"
      />

      <MazInput
        id="password"
        type="password"
        block
        placeholder="Enter your password"
        v-model="form.password"
        rounded-size="md"
        color="success"
        size="lg"
        required />

      <InputError
        class="mt-2"
        :message="form.errors.password"
      />
    </div>

    <div class="flex mt-4 justify-between items-center">

      <MazCheckbox
        name="remember"
        color="success"
        v-model="form.remember">
        <span class="dark:text-neutral-400">
          Keep me signed in
        </span>
      </MazCheckbox>

      <Link v-if="canResetPassword"
        :href="route('password.request')"
        class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
        Forgot your password?
      </Link>

    </div>

    <div class="flex items-center justify-end mt-10">

      <MazBtn
        class="ms-4m"
        color="success"
        rounded-size="md"
        :loading="form.processing"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
        type="submit"
        block>
        Log in
      </MazBtn>

    </div>

  </form>

</template>
