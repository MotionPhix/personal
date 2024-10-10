<script setup lang="ts">
import MazCheckbox from 'maz-ui/components/MazCheckbox'
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
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
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <MazInput
                  id="email"
                  type="email"
                  class="block w-full mt-1"
                  autocomplete="username"
                  placeholder="Enter your login email"
                  v-model="form.email"
                  rounded-size="md"
                  color="success"
                  autofocus
                  size="lg"
                  required
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <MazInput
                  id="password"
                  type="password"
                  class="block w-full mt-1"
                  placeholder="Enter your password"
                  v-model="form.password"
                  rounded-size="md"
                  color="success"
                  size="lg"
                  required
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
              <MazCheckbox
                name="remember"
                color="success"
                v-model:checked="form.remember"
                label="Keep me signed in" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </Link>

                <MazBtn
                  class="ms-4"
                  color="success"
                  rounded-size="md"
                  :loading="form.processing"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                  type="submit">
                  Log in
                </MazBtn>
            </div>
        </form>
    </GuestLayout>
</template>
