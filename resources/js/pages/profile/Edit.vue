<script setup lang="ts">
import DeleteUserForm from '@/pages/profile/partials/DeleteUserForm.vue';
import UpdatePasswordForm from '@/pages/profile/partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/pages/profile/partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';
import Navheader from '@/components/backend/Navheader.vue';

defineProps<{
  mustVerifyEmail?: boolean;
  status?: string;
}>();

defineOptions({
  layout: AuthLayout
})
</script>

<template>

  <Head title="Profile" />

  <Navheader>
    <h2
      class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      Profile
    </h2>
  </Navheader>

  <div class="py-12">
    <div class="space-y-6 sm:px-6 lg:px-8">
      <div
        class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail"
        :status="status" />
      </div>

      <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
        <UpdatePasswordForm />
      </div>

      <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
        <DeleteUserForm />
      </div>
    </div>
  </div>

</template>
