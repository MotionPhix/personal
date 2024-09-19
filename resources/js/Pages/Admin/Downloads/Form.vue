<script setup lang="ts">
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import type { FilePond } from "filepond";

import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFilePoster from "filepond-plugin-file-poster";

// Import FilePond styles
import "filepond/dist/filepond.min.css";

// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import VueFilePond from "vue-filepond";

import AuthLayout from "@/Layouts/AuthLayout.vue";
import { IconFileUpload } from "@tabler/icons-vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Navheader from "@/Components/Backend/Navheader.vue";
import InputError from "@/Components/InputError.vue";

// Register FilePond plugins
const Uploader = VueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginFileValidateSize,
  FilePondPluginImagePreview,
  FilePondPluginFilePoster
);

// FilePond instance
const posterPond = ref<FilePond>();
const logoPond = ref<FilePond>();

// Define accepted file types
const acceptedFileTypes = [
  'application/pdf',
  'image/svg+xml',
  'application/postscript',
  'image/x-coreldraw'
];

// Form setup using Inertia.js
const form = useForm({
  brand: '',
  poster: <string | File>'',
  file_path: <string | File>'',
});

// Handle file selection for poster
const handleAddPoster = () => {

  const fileItem = posterPond.value?.getFile(); // Check if there's a file

  if (fileItem && fileItem.file) {
    // Only assign if the file exists
    form.poster = fileItem.file as File;
  } else {
    form.poster = ''
  }

};

// Handle file selection for poster
const handleAddLogo = () => {

  const fileItem = logoPond.value?.getFile(); // Check if there's a file

  if (fileItem && fileItem.file) {
    // Only assign if the file exists
    form.file_path = fileItem.file as File;
  } else {
    form.file_path = ''
  }

};

// Submit the form
const submit = () => {
  form.post(route('auth.downloads.store'), {
    preserveScroll: true,
    onSuccess: () => {
      posterPond.value = undefined;
      logoPond.value = undefined;

      form.reset()
    }
  });
};

defineOptions({
  layout: AuthLayout
})
</script>

<template>
  <Head title="Logo downloads" />

  <Navheader>

    <h2 class="text-xl font-semibold dark:text-gray-300 sm:inline-block">
      New brand
    </h2>

    <span class="flex-1"></span>

    <Link
      as="button"
      :href="route('auth.downloads.index')"
      class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 border border-transparent rounded-lg hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
      Cancel
    </Link>

  </Navheader>

  <div class="max-w-2xl px-4 py-8 mx-auto">
    <h1 class="text-2xl font-bold sm:text-xl dark:text-white">
      Upload a new logo
    </h1>

    <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
      Upload a logo in SVG, AI, CDR, or PDF format.
    </p>

    <form @submit.prevent="submit" class="flex flex-col mt-8">
      <!-- Brand Name Input -->
      <div class="mb-4">
        <InputLabel
          for="brand"
          value="Brand name" />

        <TextInput
          type="text"
          v-model="form.brand"
          id="brand"
          placeholder="Enter brand name"
          class="w-full mt-2"
        />

        <InputError :message="form.errors.brand" />
      </div>

      <div>
        <Uploader
          name="logo"
          ref="posterPond"
          credits="false"
          @addfile="handleAddPoster"
          acceptedFileTypes="image/png, image/jpeg"
          labelMaxFileSizeExceeded="The poster is too large"
          labelMaxFileSize="Max poster size is {filesize}"
          maxFileSize="1MB"
          labelIdle='Drop your poster or <span class="filepond--label-action">Browse</span>'
          :allowMultiple="false"
          class="mt-1 filepond dark:border-gray-700 dark:bg-gray-900 dark:text-white"
        />

        <InputError :message="form.errors.poster" />
      </div>

      <div>
        <Uploader
          name="logo"
          ref="logoPond"
          credits="false"
          @addfile="handleAddLogo"
          :acceptedFileTypes="acceptedFileTypes"
          labelMaxFileSizeExceeded="Your logo is too large"
          labelMaxFileSize="Max logo size is {filesize}"
          maxFileSize="2MB"
          labelIdle='Drop your logo or <span class="filepond--label-action">Browse</span>'
          :allowMultiple="false"
          class="mt-4 filepond dark:border-gray-700 dark:bg-gray-900 dark:text-white"
        />

        <InputError :message="form.errors.file_path" />
      </div>

      <!-- Submit Button -->
      <button
        type="submit"
        class="mt-4 flex items-center self-end max-w-sm gap-1 px-4 py-2.5 font-semibold text-white bg-green-500 rounded-lg sm:max-w-xs hover:bg-green-600 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
        <IconFileUpload class="size-8" /> <span>Upload</span>
      </button>
    </form>
  </div>
</template>

<style scoped>
/* FilePond custom styles for dark mode */
.filepond--root {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
}

.dark .filepond--root {
  border-color: #4b5563;
  background-color: #1f2937;
}

.dark .filepond--label-action {
  color: #9ca3af;
}
</style>
