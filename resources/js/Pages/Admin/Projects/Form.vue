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

import { onBeforeUnmount, ref } from "vue";

import 'v-calendar/style.css'

import { Project } from "@/types";

import Navheader from "@/Components/Backend/Navheader.vue";

import vueFilePond from "vue-filepond";

import "filepond/dist/filepond.min.css";

import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";

import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

import FilePondPluginImagePreview from "filepond-plugin-image-preview";

import type { FilePond } from "filepond";

import PreTap from "@/Components/PreTap.vue";

const FilePondInput = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginFileValidateSize,
  FilePondPluginImagePreview
);

const projectGalleryPond = ref<FilePond | null>(null);
const projectImages = ref([]);

const props = defineProps<{
  project: Project;
}>();

const form = useForm({
  name: props.project.name,
  description: props.project.description,
  customer_id: props.project?.customer_id,
  production: props.project.production ?? new Date(),
  captured_media: ''
});

const handlePondInit = () => {

  if (props.project.media) {

    projectImages.value = props.project.media.map((image) => ({

      source: image.original_url,

      options: {

        type: 'server',

      },

    })) as any;

  }

}

function onSubmit() {

  if (props.project.pid) {

    form.transform((data) => {
      const formData: Partial<any> = {
        ...data,
        captured_media: projectGalleryPond.value?.getFiles().map((img) => img.source),
        _method: 'put',
      };

      return formData

    }).post(route("auth.projects.update", props.project.pid), {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
        projectGalleryPond.value?.removeFiles();
      }
    });

    return;

  }

  form
    .transform((data) => {

      const formData: Partial<any> = {
        ...data,
        captured_media: projectGalleryPond.value?.getFiles().map((img) => img.source),
      };

      return formData;

    }).post(route('auth.projects.store'), {
    preserveScroll: true,

    onSuccess: () => {
      form.reset()
      projectGalleryPond.value?.removeFiles();
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

onBeforeUnmount(() => {
  projectGalleryPond.value?.destroy
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

          <div class="flex flex-col col-span-2">

            <label
              for="Media"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
              Project Media
            </label>

            <FilePondInput
              name="Project images"
              ref="projectGalleryPond"
              :files="projectImages"
              max-file-size="2MB"
              credits="false"
              :storeAsFile="true"
              label-idle="Drop project images here..."
              :allow-multiple="true"
              :allow-mage-preview="true"
              :allow-paste="true"
              :allow-reorder="true"
              @init="handlePondInit"
            />
            <!-- accepted-file-types="image/jpeg, image/png" -->

            <div v-if="projectGalleryPond?.getFiles().length">

              <div v-for="(img, index) in projectGalleryPond?.getFiles()" :key="index">

                <InputError
                  v-if="form.errors[`captured_media.${index}`]"
                  :message="form.errors[`captured_media.${index}`]"
                />

              </div>

            </div>

            <InputError
              v-else
              :message="form.errors.captured_media" />

          </div>

        </div>

      </form>

    </section>

  </article>

</template>
