<script lang="ts" setup>
import { Modal } from '@inertiaui/modal-vue'
import VueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import { useForm } from '@inertiajs/vue3';
import { onBeforeUnmount, ref } from 'vue';
import { FilePond } from 'filepond';
import MazInput from 'maz-ui/components/MazInput';
import MazTextarea from 'maz-ui/components/MazTextarea';

const FilePondInput = VueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginFileValidateSize,
  FilePondPluginImagePreview
);

const logoFile = ref<FilePond | null>(null);

const form = useForm({
  name: '',
  email: '',
  description: '',
  logo: null
})

const submitForm = () => {

  form.post('/upload-logo', {

    onSuccess: () => {

      form.reset();
      alert('Logo uploaded successfully!');

    },

    onError: (error) => console.log(error)

  });

  /*form
    .transform((data) => {

      const formData: Partial<any> = {
        ...data,
        logo: logoFile.value?.getFile()?.file
      };

      return formData;

    }).post(route('auth.projects.store'), {

      preserveScroll: true,

      onSuccess: () => {
        form.reset()
        logoFile.value?.removeFiles();
      },

    });*/

};

const handleFilePond = (file: FilePond) => {

  form.logo = file as any

};

onBeforeUnmount(() => {
  logoFile.value?.destroy
})
</script>

<template>

  <Modal
    max-width="xl">

    <div class="w-full mx-auto">

      <h1 class="text-2xl font-bold mb-8">Upload your pixelated logo</h1>

      <form
        @submit.prevent="submitForm">

        <section class="grid grid-cols-1 sm:grid-cols-2 gap-2">

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
              Full Name
            </label>

            <MazInput
              v-model="form.name"
              placeholder="Enter your full name"
              rounded-size="md"
              type="text"
              size="lg"
              block />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
              Email
            </label>

            <MazInput
              v-model="form.email"
              placeholder="Enter your email address"
              rounded-size="md"
              type="email"
              size="lg"
              block />
          </div>

        </section>

        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">
            Description
          </label>

          <MazTextarea
            v-model="form.description"
            placeholder="Describe your logo and desired output"
            rounded-size="md" />
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">
            Upload Logo
          </label>

          <FilePondInput
            name="Project images"
            ref="logoFile"
            max-file-size="2MB"
            credits="false"
            accepted-file-types="image/*"
            label-idle="Drop project images here..."
            :allow-multiple="false"
            :allow-mage-preview="true"
            @updatefiles="handleFilePond" />
        </div>

        <button
          type="submit"
          class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
          Submit
        </button>

      </form>

    </div>

  </Modal>

</template>
