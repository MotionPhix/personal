<script setup lang="ts">
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
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
  brand: null,
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

  <div class="max-w-2xl py-8 mx-auto">
    <h1 class="text-4xl font-bold text-center dark:text-white">Upload a new logo</h1>
    <p class="mt-2 text-lg text-center text-gray-600 dark:text-gray-400">
      Upload a logo in SVG, AI, CDR, or PDF format.
    </p>

    <form @submit.prevent="submit" class="flex flex-col gap-4 mt-8">
      <!-- Brand Name Input -->
      <div class="mb-6">
        <label for="brand" class="block text-gray-800 dark:text-white">Brand name</label>
        <input
          type="text"
          v-model="form.brand"
          id="brand"
          placeholder="Enter brand name"
          class="w-full px-4 py-3 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white dark:border-gray-600"
          required
        />
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
          class="filepond dark:border-gray-700 dark:bg-gray-900 dark:text-white"
        />
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
          class="filepond dark:border-gray-700 dark:bg-gray-900 dark:text-white"
        />
      </div>

      <!-- Submit Button -->
      <button
        type="submit"
        class="self-end max-w-full px-4 py-2 font-semibold text-white bg-green-500 rounded-lg sm:max-w-xs hover:bg-green-600 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
        Upload Logo
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
