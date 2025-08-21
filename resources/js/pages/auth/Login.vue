<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';

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

  <form @submit.prevent="submit" class="space-y-6">
    <div class="space-y-2">
      <Label for="email">Email</Label>
      <Input
        id="email"
        v-model="form.email"
        type="email"
        placeholder="Enter your login email"
        autocomplete="username"
        required
        autofocus
      />
      <InputError :message="form.errors.email" />
    </div>

    <div class="space-y-2">
      <Label for="password">Password</Label>
      <Input
        id="password"
        v-model="form.password"
        type="password"
        placeholder="Enter your password"
        required
      />
      <InputError :message="form.errors.password" />
    </div>

    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <Checkbox
          id="remember"
          v-model:checked="form.remember"
        />
        <Label for="remember" class="text-sm font-normal">
          Keep me signed in
        </Label>
      </div>

      <Link
        v-if="canResetPassword"
        :href="route('password.request')"
        class="text-sm text-primary hover:text-primary/80 underline"
      >
        Forgot your password?
      </Link>
    </div>

    <Button
      type="submit"
      class="w-full"
      :disabled="form.processing"
    >
      <span v-if="form.processing">Signing in...</span>
      <span v-else>Log in</span>
    </Button>
  </form>
</template>
