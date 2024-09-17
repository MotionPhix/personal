<script setup lang="ts">
import { Head, Link, router, useForm } from "@inertiajs/vue3";

import Form from 'vform'

import InputError from "@/Components/InputError.vue";

import ContactSelector from "@/Components/Contact/ContactSelector.vue";

import AuthLayout from "@/Layouts/AuthLayout.vue";

import { IconPlus, IconUpload, IconX } from "@tabler/icons-vue";

import Spinner from "@/Components/Spinner.vue";

import TextInput from "@/Components/TextInput.vue";

import { UseDark } from "@vueuse/components";

import { DatePicker } from 'v-calendar'

import { ref, watch } from "vue";

import { useFileDialog } from "@vueuse/core"

import 'v-calendar/style.css'

import { Project } from "@/types";

import Navheader from "@/Components/Backend/Navheader.vue";

import PreTap from "@/Components/PreTap.vue";

const props = defineProps<{
  project: Project;
}>();

// Handle file dialog
const { files: newFiles, open: openFileDialog } = useFileDialog({
  accept: 'image/*',
  multiple: true,
});

const form = useForm({
  name: props.project.name,
  description: props.project.description,
  customer_id: props.project?.customer_id,
  production: props.project.production ?? new Date(),
  media: props.project.media
});

// Watch for new files and generate a preview
watch(newFiles, (newFilesArray) => {
  const filesArray = Array.from(newFilesArray as any); // Convert FileList to array
  filesArray.forEach((file) => {
    form.media?.push({
      file,
      preview: URL.createObjectURL(file), // Generate preview URL
    });
  });
});

// Remove an image from the form (either existing or newly added)
const removeImage = (index: number) => {
  form.media?.splice(index, 1);
};

function onSubmit() {

  if (props.project.pid) {

    form.transform((data) => {
      const formData: Partial<any> = {
        ...data,
        _method: 'patch',
      };

      return formData

    }).post(route("auth.projects.update", props.project.pid), {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
      }
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
        class="flex items-center w-full gap-1 mx-auto dark:text-white dark:border-gray-700"
      >
      <h2 class="text-xl font-semibold dark:text-gray-300 sm:inline-block">
        New project
      </h2>

      <span class="flex-1"></span>

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

  <article class="sm:px-6 lg:px-8">

    <section class="max-w-2xl px-6 py-12 mx-auto">

      <form>

        <div
          class="grid grid-cols-1 gap-4 mb-4 sm:gap-8 sm:grid-cols-2">

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

            <PreTap
              v-model="form.description"
              placeholder="Say a few things worthy noting about the project" />

            <InputError :message="form.errors.description" />
          </div>

          <div class="col-span-2">
            <label
              for="media"
              class="flex items-center justify-between w-full mb-2 text-sm font-medium text-gray-900 dark:text-white">
              <span>
                Project images
              </span>

              <button
                type="button"
                @click="openFileDialog"
                class="flex items-center p-1 font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400">
                <IconPlus stroke="2.5" class="size-5"/>
              </button>
            </label>

            <ul
              class="my-6 bg-white divide-y divide-gray-200 shadow rounded-xl dark:bg-gray-800 dark:divide-gray-700"
              v-if="form.media?.length">
              <li v-for="(image, index) in form.media" :key="index"
                  class="flex items-center p-3 space-x-2">
                <div class="flex-shrink-0 bg-gray-300 w-9 h-9 dark:bg-gray-600">
                  <img
                    :src="image.id ? image.original_url : image.preview"
                    class="w-full h-full rounded"
                    :alt="image.name">
                </div>

                <div
                  class="flex-1 text-sm text-gray-700 truncate dark:text-gray-300">
                  {{ image.file_name ?? image.file.name }}
                </div>

                <button
                  class="text-sm text-indigo-600 underline dark:text-indigo-400"
                  @click="removeImage(index)"
                  type="button">
                  <IconX class="size-5" stroke="2.5" />
                </button>
              </li>
            </ul>

            <InputError :message="form.errors.media" />
          </div>

        </div>

      </form>

    </section>

  </article>

</template>
