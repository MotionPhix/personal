<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthLayout from "@/layouts/AuthLayout.vue";
import ProjectForm from "./Form.vue";
import { Customer, Project } from "@/types";

interface CreateProps {
  customers: Customer[];
  selectedCustomer?: Customer;
  productionTypes: string[];
  categories: string[];
}

const props = defineProps<CreateProps>();

// Create empty project for the form
const emptyProject: Partial<Project> = {
  name: '',
  description: '',
  short_description: '',
  customer_id: props.selectedCustomer?.id,
  production_type: '',
  category: '',
  status: 'not_started',
  priority: 'medium',
  start_date: '',
  end_date: '',
  estimated_hours: 0,
  actual_hours: 0,
  budget: 0,
  technologies: [],
  features: [],
  challenges: '',
  solutions: '',
  results: '',
  client_feedback: '',
  is_featured: false,
  is_public: true,
  live_url: '',
  github_url: '',
  figma_url: '',
  behance_url: '',
  dribbble_url: '',
  meta_title: '',
  meta_description: '',
};

defineOptions({
  layout: AuthLayout,
});
</script>

<template>
  <Head title="Create New Project" />

  <ProjectForm
    :project="emptyProject"
    :customers="customers"
    :selected-customer="selectedCustomer"
    :production-types="productionTypes"
    :categories="categories"
    :is-editing="false"
  />
</template>
