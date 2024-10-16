<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import type { FilePond } from "filepond";

import { Modal } from '@inertiaui/modal-vue'

import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFilePoster from "filepond-plugin-file-poster";

// Import FilePond styles
import "filepond/dist/filepond.min.css";

// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import VueFilePond from "vue-filepond";

import { IconFileUpload, IconPhotoUp } from "@tabler/icons-vue";

import MazInput from "maz-ui/components/MazInput";

import MazBtn from "maz-ui/components/MazBtn"

import InputLabel from "@/Components/InputLabel.vue";

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

const logoModalRef = ref()

// Submit the form
const submit = () => {
  form.post(route('auth.downloads.store'), {
    preserveScroll: true,
    onSuccess: () => {
      posterPond.value = undefined;
      logoPond.value = undefined;

      form.reset()

      logoModalRef.value.close()
    }
  });
};
</script>

<template>
  <Modal
    ref="logoModalRef"
    panel-classes="bg-gray-100 rounded-lg dark:bg-gray-700"
    position="top">

    <h1 class="text-2xl font-bold sm:text-xl dark:text-white">
      Upload a new logo
    </h1>

    <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
      Upload a logo in <strong>.svg</strong>, <strong>.ai</strong>,
      <strong>.cdr</strong>, or <strong>.pdf</strong> format.
    </p>

    <form @submit.prevent="submit" class="flex flex-col mt-8">
      <!-- Brand Name Input -->
      <div class="mb-4">
        <InputLabel
          for="brand"
          value="Brand name" />

        <MazInput
          type="text"
          v-model="form.brand"
          id="brand"
          placeholder="Enter brand name"
          class="w-full mt-2"
          rounded-size="md"
          size="lg"
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
          labelIdle='Upload your poster or <span class="filepond--label-action">Browse</span>'
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

      <div>

        <!-- Submit Button -->
        <MazBtn
          type="submit"
          class="mt-4"
          rounded-size="md"
          color="success"
          :loading="form.processing">

          <template #left-icon>
            <IconPhotoUp size="24" />
          </template>

          Upload

        </MazBtn>

      </div>
    </form>

  </Modal>
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
