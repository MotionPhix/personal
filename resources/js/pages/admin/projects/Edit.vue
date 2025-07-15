<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthLayout from "@/layouts/AuthLayout.vue";
import ProjectForm from "./Form.vue";
import { Customer, Project } from "@/types";

interface EditProps {
  project: {
    data: Project;
    meta?: any;
  };
  customers: Customer[];
  productionTypes: string[];
  categories: string[];
}

const props = defineProps<EditProps>();

defineOptions({
  layout: AuthLayout,
});
</script>

<template>
  <Head :title="`Edit ${props.project.data.name}`" />

  <ProjectForm
    :project="props.project.data"
    :customers="customers"
    :production-types="productionTypes"
    :categories="categories"
    :is-editing="true"
  />
</template>
