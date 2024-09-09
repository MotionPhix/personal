<script setup lang="ts">
import AddressInput from "@/Components/AddressInput.vue";

import Spinner from "@/Components/Spinner.vue";

import InputError from "@/Components/InputError.vue";

import TextInput from "@/Components/TextInput.vue";

import AuthLayout from "@/Layouts/AuthLayout.vue";

import {Head, Link, useForm} from "@inertiajs/vue3";

import { IconPlus } from "@tabler/icons-vue";

import { Customer } from "@/types";

import Navheader from "@/Components/Backend/Navheader.vue";

import Footnote from "@/Components/Front/Footnote.vue";

const props = defineProps<{

  customer: Customer;

}>();

const form = useForm({
  first_name: props.customer.first_name,
  last_name: props.customer.last_name,
  job_title: props.customer.job_title ?? "",
  company_name: props.customer.company_name ?? "",
  address: {
    city: props.customer.address?.city ?? "",
    street: props.customer.address?.street ?? "",
    state: props.customer.address?.state ?? "",
    country: props.customer.address?.country ?? "",
  },
});

function onSubmit() {
  form.transform((data) => {
    let formData: Partial<Customer> = {
      first_name: data.first_name,
      last_name: data.last_name,
      job_title: data.job_title,
      company_name: data.company_name,
    };

    // Include optional fields only if they are filled
    if (!!data?.job_title) formData.job_title = data?.job_title;

    if (!!data.company_name) formData.company_name = data.company_name;

    if (!!data?.address?.city) formData.address = data?.address;

    return formData;
  });

  if (props.customer.cid) {
    form.patch(route("auth.customer.update", props.customer.cid), {
      preserveScroll: true,

      onSuccess: () => {

        form.reset();

      },
    });

    return;
  }

  form.post(route("auth.customer.store"), {
    preserveScroll: true,

    onSuccess: () => {

      form.reset();

    },
  });
}

defineOptions({

  layout: AuthLayout,

});
</script>

<template>
  <Head
    :title="
      props.customer.cid
        ? `Edit ${customer.first_name} ${customer.last_name}`
        : 'New customer'
    "
  />

  <Navheader>

    <h2 class="text-xl font-semibold dark:text-gray-300 sm:inline-block">
      {{ props.customer.cid ? `Edit ${customer.first_name} ${customer.last_name}` : 'New customer'}}
    </h2>

    <span class="flex-1"></span>

    <button
      type="submit"
      @click.prevent="onSubmit"
      :disabled="form.processing"
      class="inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-200 shadow-sm -ms-px first:rounded-s-lg first:ms-0 rounded-s-lg rounded-e-lg focus:z-10 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">

      <IconPlus stroke="2.5" size="16" />

      <span>
        {{ props.customer.cid ? "Update" : "Create" }}
      </span>

      <Spinner v-if="form.processing" />

    </button>

    <Link
      as="button"
      :href="route('auth.customer.index')"
      class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 border border-transparent rounded-lg gap-x-2 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
      Cancel
    </Link>

  </Navheader>

  <main class="w-full max-w-2xl px-4 py-10 mx-auto md:pt-16 sm:px-6 lg:px-8">

    <form
      class="flex flex-col w-full gap-6 px-4 md:mx-auto">
      <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
          Customer profile
        </h2>

        <p class="text-sm text-gray-600 dark:text-gray-400">
          Manage customer information, and company.
        </p>
      </div>

      <section class="grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div class="flex-1">
        <label
          for="name"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          First name
        </label>

        <TextInput
          id="name"
          type="text"
          class="w-full"
          v-model="form.first_name"
          placeholder="Enter first name"
        />

        <InputError :message="$page.props.errors.first_name" />
      </div>

      <div class="flex-1">
        <label
          for="last_name"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Surname
        </label>

        <TextInput
          type="text"
          class="w-full"
          id="last_name"
          v-model="form.last_name"
          placeholder="Type surname"
        />

        <InputError :message="$page.props.errors.last_name" />
      </div>
    </section>

      <section
        class="flex flex-col gap-6">
        <div>
          <label
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Company
          </label>

          <TextInput
            type="text"
            class="w-full"
            id="company_name"
            v-model="form.company_name"
            placeholder="Type company name"
          />

          <InputError :message="$page.props.errors['company_name']" />
        </div>

        <div>
          <label
            for="job_title"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Job title
          </label>

          <TextInput
            type="text"
            class="w-full"
            id="job_title"
            v-model="form.job_title"
            placeholder="Enter job title"
          />

          <InputError :message="$page.props.errors['job_title']" />
        </div>

        <div>
          <AddressInput v-model="form.address" />
        </div>
      </section>
    </form>

  </main>

  <Footnote />

</template>
