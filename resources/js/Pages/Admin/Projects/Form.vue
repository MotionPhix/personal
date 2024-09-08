<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";

import InputError from "@/Components/InputError.vue";

import ContactSelector from "@/Components/Contact/ContactSelector.vue";

import AuthLayout from "@/Layouts/AuthLayout.vue";

import { IconPlus } from "@tabler/icons-vue";

import Spinner from "@/Components/Spinner.vue";

import TextInput from "@/Components/TextInput.vue";

import { UseDark } from "@vueuse/components";

import { DatePicker } from 'v-calendar'

import { onMounted, ref } from "vue";

import 'v-calendar/style.css'

import TipTap from "@/Components/TipTap.vue";

import { Project } from "@/types";

import Navheader from "@/Components/Backend/Navheader.vue";

// Import Vue FilePond
import vueFilePond from "vue-filepond";

// Import FilePond styles
import "filepond/dist/filepond.min.css";

// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

import type { FilePond } from "filepond";

// Create component
const FilePondInput = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginFileValidateSize,
  FilePondPluginImagePreview
);

const singlePond = ref<FilePond | null>(null);
const multiPond = ref<FilePond | null>(null);

const singleFile = ref(null);
const multiFile = ref(null);

const props = defineProps<{
  project: Project;
}>();

const form = useForm({
  name: props.project.name,
  description: props.project.description,
  poster: props.project.poster,
  customer_id: props.project?.customer_id,
  production: props.project.production ?? new Date(),
  images: props.project.images
});

// Handle file selection for poster
const handlePosterChange = () => {

  const fileItem = singlePond.value?.getFile(); // Check if there's a file

  if (fileItem && fileItem.file) {
    // Only assign if the file exists
    form.poster = fileItem.file as File;
  } else {
    console.error('No file found in FilePond');
  }

};

// Handle file selection for images (multiple)
const handleImagesChange = () => {

  const files = multiPond.value?.getFiles(); // Get the array of FilePond files

  if (files && files.length) {

    // Map over the files and return their file objects
    form.images = files.map(fileItem => fileItem.file as File);

  } else {

    form.images = []

  }

};

function onSubmit() {

  form.transform((data) => {

    let formData: Partial<Project> = {
      name: data.name,
      poster: data.poster,
      production: data.production,
      customer_id: data.customer_id,
    };

    if (!! data.description) {
      formData.description = data.description
    }

    if (data.images?.length && data.images[0] instanceof File) {
      formData.images = data.images
    }

    return formData;

  })

  if (props.project.pid) {

    form.patch(`/auth/projects/${props.project.pid}`, {
      preserveScroll: true,

      onSuccess: () => {
        form.reset()
      },
    });

    return;

  }

  form.post(route('auth.projects.store'), {
    preserveScroll: true,

    onSuccess: () => {
      form.reset()
    },
  });
}

const disabledDates = ref([
  {
    repeat: {
      weekdays: [1, 7],
    },
  },
])

onMounted(() => {
  if (props.project.poster) {
    singleFile.value = [{
      source: props.project.poster,
      options: {
        type: 'server',
      },
    }];
  }

  if (props.project.images) {
    multiFile.value = props.project.images.map((image: Image) => ({
      source: image.src,           // The URL or relative path of the image
      options: {
        type: 'server',             // Mark the file as a "local" file, not a new upload
        file: {
          name: image.id,          // Optional: Use the image id or name
          type: image.mime_type,   // The MIME type of the file (e.g., 'image/jpeg')
        },
        metadata: {
          size: image.size,        // Optional: If you have the size information
        },
      },
    }));
  }
})

defineOptions({
  layout: AuthLayout,
});
</script>

<template>
    <Head
      :title="props.project.pid ? `Edit ${props.project.name}` : 'New project'"
    />

    <Navheader>

      <nav
      class="flex items-center w-full gap-6 mx-auto dark:text-white dark:border-gray-700"
    >
      <h2
        class="hidden font-semibold text-gray-800 dark:text-gray-300 sm:inline-block"
      >
        New project
      </h2>

      <span class="flex-1 hidden sm:inline-block"></span>

      <button
        type="submit"
        @click.prevent="onSubmit"
        :disabled="form.processing"
        class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 shadow-sm -ms-px first:rounded-s-lg first:ms-0 rounded-s-lg rounded-e-lg focus:z-10 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">

        <IconPlus stroke="2.5" size="16" />

        <span>
          {{ props.project.pid ? "Update" : "Create" }}
        </span>

        <Spinner v-if="form.processing" />

      </button>

      <Link
        as="button"
        :href="route('auth.projects.index')"
        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 border border-transparent rounded-lg gap-x-2 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
        Cancel
      </Link>
    </nav>

    </Navheader>

  <main class="sm:px-6 lg:px-8">

    <section class="max-w-2xl px-6 py-12 mx-auto">

      <form>
        <div class="grid grid-cols-1 gap-4 mb-4 sm:gap-8 sm:grid-cols-2">

          <!-- Poster Upload -->
          <div class="col-span-2">
            <label for="poster" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
              Project poster
            </label>

              <FilePondInput
                name="Project Poster Image"
                ref="singlePond"
                maxFileSize="1MB"
                :files="singleFile"
                label-idle="Drop your poster here..."
                v-bind:allow-multiple="false"
                accepted-file-types="image/*"
                @addfile="handlePosterChange"
              />

            <InputError :message="form.errors.poster" />
          </div>

          <section class="grid col-span-2 gap-8 sm:grid-cols-2">

            <div>
              <label
                for="name"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Project name
              </label>

              <TextInput
                id="name"
                v-model="form.name"
                placeholder="Type project's name"
                class="w-full"
                type="text"
              />

              <InputError :message="form.errors.name" />
            </div>

            <div>
              <label
                for="company"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Customer
              </label>

              <ContactSelector
                v-model="form.customer_id"
                placeholder="Pick a project's customer" />

              <InputError :message="form.errors.customer_id" />
            </div>

          </section>

          <div class="col-span-2">
            <label
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
              Completion date
            </label>

            <UseDark v-slot="{ isDark }">

              <DatePicker
                v-model="form.production"
                is-required show-weeknumbers
                :disabled-dates="disabledDates"
                :is-dark="isDark"
                title-position="left"
                view="weekly"
                expanded
                :masks="{
                  input: 'DD-MM-YYYY',
                }" />

            </UseDark>

            <InputError :message="form.errors.production" />
          </div>

          <div class="col-span-2">
            <label
              for="description"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
              Description
            </label>

            <TipTap
              v-model="form.description"
              placeholder="Say a few things worthy noting about the project" />

            <InputError :message="form.errors.description" />
          </div>

          <!-- Images Upload -->
          <div class="col-span-2">
            <label
              for="images"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
              Project Images
            </label>

            <FilePondInput
              name="Project Images"
              ref="multiPond"
              :files="multiFile"
              maxFileSize="2MB"
              label-idle="Drop project images here..."
              v-bind:allow-multiple="true"
              v-bind:allowImagePreview="false"
              accepted-file-types="image/jpeg, image/png, image/jpg, image/jfif, image/gif"
              @addfile="handleImagesChange"
              @removefile="handleImagesChange"
            />

            <InputError :message="form.errors.images" />
          </div>

        </div>
      </form>

    </section>

  </main>
</template>
