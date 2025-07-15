<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";
import { computed, onMounted, ref, watch } from "vue";
import { Customer, Project, GalleryImage } from "@/types";
import AuthLayout from "@/layouts/AuthLayout.vue";
import Navheader from "@/components/backend/Navheader.vue";
import InputError from "@/components/InputError.vue";
import Spinner from "@/components/Spinner.vue";

// Icons
import {
  IconPlus,
  IconDisc,
  IconX,
  IconCalendar,
  IconUser,
  IconTag,
  IconCode,
  IconCurrencyDollar,
  IconClock,
  IconStar,
  IconEye,
  IconLink,
  IconBrandGithub,
  IconBrandFigma,
  IconBrandBehance,
  IconBrandDribbble,
  IconPhoto,
  IconTrash
} from "@tabler/icons-vue";

// UI Components
import MazSelect, { MazSelectOption } from 'maz-ui/components/MazSelect';
import MazInput from 'maz-ui/components/MazInput';
import MazTextarea from 'maz-ui/components/MazTextarea';
import { DatePicker } from 'v-calendar';
import { UseDark } from "@vueuse/components";
import { ModalLink } from '@inertiaui/modal-vue';

// File upload
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import type { FilePond } from "filepond";

// Rich text editor
import PreTap from "@/components/PreTap.vue";

// Calendar styles
import 'v-calendar/style.css';

interface FormProps {
  project: Partial<Project>;
  customers: Customer[];
  selectedCustomer?: Customer;
  productionTypes: string[];
  categories: string[];
  isEditing: boolean;
}

const props = defineProps<FormProps>();

// File upload setup
const FilePondInput = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginFileValidateSize,
  FilePondPluginImagePreview
);

// Refs
const projectGalleryPond = ref<FilePond | null>(null);
const projectImages = ref([]);
const tipTapRef = ref();

// Form setup
const form = useForm({
  // Basic Information
  name: props.project.name || '',
  description: props.project.description || '',
  short_description: props.project.short_description || '',
  customer_id: props.project.customer_id || props.selectedCustomer?.id || null,

  // Project Details
  production_type: props.project.production_type || '',
  category: props.project.category || '',
  status: props.project.status || 'not_started',
  priority: props.project.priority || 'medium',

  // Timeline
  start_date: props.project.start_date || null,
  end_date: props.project.end_date || null,

  // Resources
  estimated_hours: props.project.estimated_hours || 0,
  actual_hours: props.project.actual_hours || 0,
  budget: props.project.budget || 0,

  // Technical Details
  technologies: props.project.technologies || [],
  features: props.project.features || [],

  // Project Story
  challenges: props.project.challenges || '',
  solutions: props.project.solutions || '',
  results: props.project.results || '',
  client_feedback: props.project.client_feedback || '',

  // Settings
  is_featured: props.project.is_featured || false,
  is_public: props.project.is_public !== undefined ? props.project.is_public : true,
  sort_order: props.project.sort_order || 0,

  // SEO
  meta_title: props.project.meta_title || '',
  meta_description: props.project.meta_description || '',

  // External Links
  live_url: props.project.live_url || '',
  github_url: props.project.github_url || '',
  figma_url: props.project.figma_url || '',
  behance_url: props.project.behance_url || '',
  dribbble_url: props.project.dribbble_url || '',

  // Media
  captured_media: [] as File[],
});

// Computed properties
const customerOptions = computed(() =>
  props.customers.map(customer => ({
    value: customer.id,
    label: customer.display_name || customer.full_name || `${customer.first_name} ${customer.last_name}`.trim(),
    company: customer.company_name || 'Individual',
  }))
);

const productionTypeOptions = computed(() =>
  props.productionTypes.map(type => ({ value: type, label: type }))
);

const categoryOptions = computed(() =>
  props.categories.map(category => ({ value: category, label: category }))
);

const statusOptions = [
  { value: 'not_started', label: 'Not Started', color: 'gray' },
  { value: 'in_progress', label: 'In Progress', color: 'blue' },
  { value: 'on_hold', label: 'On Hold', color: 'yellow' },
  { value: 'completed', label: 'Completed', color: 'green' },
  { value: 'cancelled', label: 'Cancelled', color: 'red' },
];

const priorityOptions = [
  { value: 'low', label: 'Low', color: 'green' },
  { value: 'medium', label: 'Medium', color: 'yellow' },
  { value: 'high', label: 'High', color: 'orange' },
  { value: 'urgent', label: 'Urgent', color: 'red' },
];

// Technology and feature management
const newTechnology = ref('');
const newFeature = ref('');

const addTechnology = () => {
  if (newTechnology.value.trim() && !form.technologies.includes(newTechnology.value.trim())) {
    form.technologies.push(newTechnology.value.trim());
    newTechnology.value = '';
  }
};

const removeTechnology = (index: number) => {
  form.technologies.splice(index, 1);
};

const addFeature = () => {
  if (newFeature.value.trim() && !form.features.includes(newFeature.value.trim())) {
    form.features.push(newFeature.value.trim());
    newFeature.value = '';
  }
};

const removeFeature = (index: number) => {
  form.features.splice(index, 1);
};

// File upload handling
const handlePondInit = () => {
  if (props.project.gallery_images && props.project.gallery_images.length > 0) {
    projectImages.value = props.project.gallery_images.map((image: GalleryImage) => ({
      source: image.url,
      options: { type: 'server' },
    })) as any;
  } else if (props.project.media && props.project.media.length > 0) {
    projectImages.value = props.project.media.map((image) => ({
      source: image.original_url,
      options: { type: 'server' },
    })) as any;
  }
};

// Form submission
const onSubmit = () => {
  const formData = {
    ...form.data(),
    captured_media: projectGalleryPond.value?.getFiles().map((file) => file.file) || [],
  };

  if (props.isEditing && props.project.uuid) {
    form.transform(() => ({ ...formData, _method: 'put' }))
      .post(route("admin.projects.update", props.project.uuid), {
        preserveScroll: true,
        onSuccess: () => {
          // Success handled by redirect
        }
      });
  } else {
    form.transform(() => formData)
      .post(route('admin.projects.store'), {
        preserveScroll: true,
        onSuccess: () => {
          form.reset();
          projectGalleryPond.value?.removeFiles();
          tipTapRef.value?.resetEditorContent();
        },
      });
  }
};

// Customer assignment from modal
const onAssignContact = () => {
  if (props.selectedCustomer) {
    form.customer_id = props.selectedCustomer.id;
  }
};

// Auto-generate meta title from project name
watch(() => form.name, (newName) => {
  if (newName && !form.meta_title) {
    form.meta_title = `${newName} - Portfolio Project`;
  }
});

// Lifecycle
onMounted(() => {
  handlePondInit();
});

defineOptions({
  layout: AuthLayout,
});
</script>

<template>
  <Head :title="isEditing ? `Edit ${project.name}` : 'Create New Project'" />

  <Navheader>
    <nav class="flex items-center w-full gap-1 mx-auto dark:text-white dark:border-gray-700">
      <h2 class="gap-2 text-xl font-semibold dark:text-gray-300 sm:inline-flex">
        <span>{{ isEditing ? 'Editing' : 'Creating' }}</span>
        <span class="hidden sm:inline-flex">Project</span>
      </h2>

      <span class="flex-1"></span>

      <!-- Save Button -->
      <button
        type="submit"
        @click.prevent="onSubmit"
        :disabled="form.processing"
        class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none dark:focus:ring-offset-gray-800">
        <IconDisc v-if="!form.processing" stroke="2" size="16" />
        <Spinner v-if="form.processing" />
        <span>{{ isEditing ? "Update Project" : "Create Project" }}</span>
      </button>

      <!-- Cancel Button -->
      <Link
        as="button"
        :href="route('admin.projects.index')"
        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
        Cancel
      </Link>
    </nav>
  </Navheader>

  <article class="sm:px-6 lg:px-8">
    <section class="max-w-4xl px-6 py-8 mx-auto">

      <form @submit.prevent="onSubmit" class="space-y-8">

        <!-- Basic Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <IconUser size="20" />
            Basic Information
          </h3>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- Project Name -->
            <div class="sm:col-span-2">
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Project Name *
              </label>
              <MazInput
                id="name"
                v-model="form.name"
                placeholder="Enter project name"
                size="lg"
                block
                required
              />
              <InputError :message="form.errors.name" />
            </div>

            <!-- Customer -->
            <div>
              <label for="customer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Customer *
              </label>
              <div class="relative">
                <MazSelect
                  v-model="form.customer_id"
                  :options="customerOptions"
                  placeholder="Select a customer"
                  :search="customerOptions.length > 5"
                  size="lg"
                  block
                  required
                >
                  <template #option="{ option, isSelected }">
                    <div class="w-full" :class="isSelected ? 'font-semibold' : ''">
                      <div class="font-medium">{{ option.label }}</div>
                      <div class="text-sm text-gray-500">{{ option.company }}</div>
                    </div>
                  </template>
                </MazSelect>

                <ModalLink
                  :href="route('admin.customers.create')"
                  class="absolute z-10 flex items-center justify-center transition duration-200 bg-blue-600 rounded-lg right-2 bottom-2 size-8 hover:bg-blue-700"
                  :data="{ 'modal': 'show' }"
                  @close="onAssignContact"
                  preserve-scroll
                  as="button">
                  <IconPlus class="size-4 text-white" />
                </ModalLink>
              </div>
              <InputError :message="form.errors.customer_id" />
            </div>

            <!-- Production Type -->
            <div>
              <label for="production_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Production Type
              </label>
              <MazSelect
                v-model="form.production_type"
                :options="productionTypeOptions"
                placeholder="Select production type"
                size="lg"
                block
              />
              <InputError :message="form.errors.production_type" />
            </div>

            <!-- Short Description -->
            <div class="sm:col-span-2">
              <label for="short_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Short Description
              </label>
              <MazTextarea
                v-model="form.short_description"
                placeholder="Brief description for project cards and previews"
                rows="3"
                block
              />
              <InputError :message="form.errors.short_description" />
            </div>
          </div>
        </div>

        <!-- Project Details -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <IconTag size="20" />
            Project Details
          </h3>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Category -->
            <div>
              <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Category
              </label>
              <MazSelect
                v-model="form.category"
                :options="categoryOptions"
                placeholder="Select category"
                size="lg"
                block
              />
              <InputError :message="form.errors.category" />
            </div>

            <!-- Status -->
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Status
              </label>
              <MazSelect
                v-model="form.status"
                :options="statusOptions"
                placeholder="Select status"
                size="lg"
                block
              />
              <InputError :message="form.errors.status" />
            </div>

            <!-- Priority -->
            <div>
              <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Priority
              </label>
              <MazSelect
                v-model="form.priority"
                :options="priorityOptions"
                placeholder="Select priority"
                size="lg"
                block
              />
              <InputError :message="form.errors.priority" />
            </div>

            <!-- Sort Order -->
            <div>
              <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Sort Order
              </label>
              <MazInput
                v-model="form.sort_order"
                type="number"
                placeholder="0"
                size="lg"
                block
              />
              <InputError :message="form.errors.sort_order" />
            </div>
          </div>
        </div>

        <!-- Timeline & Resources -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <IconCalendar size="20" />
            Timeline & Resources
          </h3>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Start Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Start Date
              </label>
              <UseDark v-slot="{ isDark }">
                <DatePicker
                  v-model="form.start_date"
                  :is-dark="isDark"
                  :masks="{ input: 'DD-MM-YYYY' }"
                />
              </UseDark>
              <InputError :message="form.errors.start_date" />
            </div>

            <!-- End Date -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                End Date
              </label>
              <UseDark v-slot="{ isDark }">
                <DatePicker
                  v-model="form.end_date"
                  :is-dark="isDark"
                  :masks="{ input: 'DD-MM-YYYY' }"
                />
              </UseDark>
              <InputError :message="form.errors.end_date" />
            </div>

            <!-- Budget -->
            <div>
              <label for="budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Budget ($)
              </label>
              <MazInput
                v-model="form.budget"
                type="number"
                placeholder="0.00"
                size="lg"
                block
              />
              <InputError :message="form.errors.budget" />
            </div>

            <!-- Estimated Hours -->
            <div>
              <label for="estimated_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Estimated Hours
              </label>
              <MazInput
                v-model="form.estimated_hours"
                type="number"
                placeholder="0"
                size="lg"
                block
              />
              <InputError :message="form.errors.estimated_hours" />
            </div>

            <!-- Actual Hours -->
            <div>
              <label for="actual_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Actual Hours
              </label>
              <MazInput
                v-model="form.actual_hours"
                type="number"
                placeholder="0"
                size="lg"
                block
              />
              <InputError :message="form.errors.actual_hours" />
            </div>
          </div>
        </div>

        <!-- Technologies -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <IconCode size="20" />
            Technologies Used
          </h3>

          <div class="space-y-4">
            <!-- Add Technology -->
            <div class="flex gap-2">
              <MazInput
                v-model="newTechnology"
                placeholder="Add a technology (e.g., Laravel, Vue.js)"
                size="lg"
                class="flex-1"
                @keyup.enter="addTechnology"
              />
              <button
                type="button"
                @click="addTechnology"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <IconPlus size="16" />
              </button>
            </div>

            <!-- Technology Tags -->
            <div class="flex flex-wrap gap-2" v-if="form.technologies.length > 0">
              <span
                v-for="(tech, index) in form.technologies"
                :key="index"
                class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full dark:bg-blue-900/30 dark:text-blue-300">
                {{ tech }}
                <button
                  type="button"
                  @click="removeTechnology(index)"
                  class="ml-1 hover:text-blue-600">
                  <IconX size="14" />
                </button>
              </span>
            </div>
          </div>
        </div>

        <!-- Features -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <IconStar size="20" />
            Key Features
          </h3>

          <div class="space-y-4">
            <!-- Add Feature -->
            <div class="flex gap-2">
              <MazInput
                v-model="newFeature"
                placeholder="Add a key feature"
                size="lg"
                class="flex-1"
                @keyup.enter="addFeature"
              />
              <button
                type="button"
                @click="addFeature"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                <IconPlus size="16" />
              </button>
            </div>

            <!-- Feature List -->
            <div class="space-y-2" v-if="form.features.length > 0">
              <div
                v-for="(feature, index) in form.features"
                :key="index"
                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <span class="text-gray-900 dark:text-gray-100">{{ feature }}</span>
                <button
                  type="button"
                  @click="removeFeature(index)"
                  class="text-red-500 hover:text-red-700">
                  <IconTrash size="16" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Project Description -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Project Description
          </h3>

          <PreTap
            ref="tipTapRef"
            v-model="form.description"
            placeholder="Describe the project in detail..."
          />
          <InputError :message="form.errors.description" />
        </div>

        <!-- Project Story -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            Project Story
          </h3>

          <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Challenges -->
            <div>
              <label for="challenges" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Challenges
              </label>
              <MazTextarea
                v-model="form.challenges"
                placeholder="What challenges did you face?"
                rows="4"
                block
              />
              <InputError :message="form.errors.challenges" />
            </div>

            <!-- Solutions -->
            <div>
              <label for="solutions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Solutions
              </label>
              <MazTextarea
                v-model="form.solutions"
                placeholder="How did you solve them?"
                rows="4"
                block
              />
              <InputError :message="form.errors.solutions" />
            </div>

            <!-- Results -->
            <div>
              <label for="results" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Results
              </label>
              <MazTextarea
                v-model="form.results"
                placeholder="What were the outcomes?"
                rows="4"
                block
              />
              <InputError :message="form.errors.results" />
            </div>

            <!-- Client Feedback -->
            <div>
              <label for="client_feedback" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Client Feedback
              </label>
              <MazTextarea
                v-model="form.client_feedback"
                placeholder="What did the client say?"
                rows="4"
                block
              />
              <InputError :message="form.errors.client_feedback" />
            </div>
          </div>
        </div>

        <!-- External Links -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <IconLink size="20" />
            External Links
          </h3>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <!-- Live URL -->
            <div>
              <label for="live_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Live Site URL
              </label>
              <MazInput
                v-model="form.live_url"
                type="url"
                placeholder="https://example.com"
                size="lg"
                block
              />
              <InputError :message="form.errors.live_url" />
            </div>

            <!-- GitHub URL -->
            <div>
              <label for="github_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                GitHub Repository
              </label>
              <MazInput
                v-model="form.github_url"
                type="url"
                placeholder="https://github.com/username/repo"
                size="lg"
                block
              />
              <InputError :message="form.errors.github_url" />
            </div>

            <!-- Figma URL -->
            <div>
              <label for="figma_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Figma Design
              </label>
              <MazInput
                v-model="form.figma_url"
                type="url"
                placeholder="https://figma.com/file/..."
                size="lg"
                block
              />
              <InputError :message="form.errors.figma_url" />
            </div>

            <!-- Behance URL -->
            <div>
              <label for="behance_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Behance Project
              </label>
              <MazInput
                v-model="form.behance_url"
                type="url"
                placeholder="https://behance.net/gallery/..."
                size="lg"
                block
              />
              <InputError :message="form.errors.behance_url" />
            </div>

            <!-- Dribbble URL -->
            <div>
              <label for="dribbble_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Dribbble Shot
              </label>
              <MazInput
                v-model="form.dribbble_url"
                type="url"
                placeholder="https://dribbble.com/shots/..."
                size="lg"
                block
              />
              <InputError :message="form.errors.dribbble_url" />
            </div>
          </div>
        </div>

        <!-- Project Media -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <IconPhoto size="20" />
            Project Media
          </h3>

          <FilePondInput
            name="project_images"
            ref="projectGalleryPond"
            :files="projectImages"
            max-file-size="10MB"
            credits="false"
            :store-as-file="true"
            accepted-file-types="image/*"
            label-idle="Drop project images here or click to browse..."
            :allow-multiple="true"
            :allow-image-preview="true"
            :allow-paste="true"
            :allow-reorder="true"
            @init="handlePondInit"
          />

          <div v-if="projectGalleryPond?.getFiles().length">
            <div v-for="(img, index) in projectGalleryPond?.getFiles()" :key="index">
              <InputError
                v-if="(form.errors as Record<string, any>)[`captured_media.${index}`]"
                :message="(form.errors as Record<string, any>)[`captured_media.${index}`]"
              />
            </div>
          </div>

          <InputError v-else :message="form.errors.captured_media" />
        </div>

        <!-- SEO & Settings -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
            SEO & Settings
          </h3>

          <div class="space-y-6">
            <!-- SEO Fields -->
            <div class="grid grid-cols-1 gap-6">
              <div>
                <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Meta Title
                </label>
                <MazInput
                  v-model="form.meta_title"
                  placeholder="SEO title for search engines"
                  size="lg"
                  block
                />
                <InputError :message="form.errors.meta_title" />
              </div>

              <div>
                <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Meta Description
                </label>
                <MazTextarea
                  v-model="form.meta_description"
                  placeholder="SEO description for search engines"
                  rows="3"
                  block
                />
                <InputError :message="form.errors.meta_description" />
              </div>
            </div>

            <!-- Settings -->
            <div class="flex flex-wrap gap-6">
              <label class="flex items-center">
                <input
                  type="checkbox"
                  v-model="form.is_featured"
                  class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Featured Project</span>
              </label>

              <label class="flex items-center">
                <input
                  type="checkbox"
                  v-model="form.is_public"
                  class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                />
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Public Project</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
          <Link
            as="button"
            :href="route('admin.projects.index')"
            class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
            Cancel
          </Link>

          <button
            type="submit"
            :disabled="form.processing"
            class="inline-flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none">
            <IconDisc v-if="!form.processing" size="16" />
            <Spinner v-if="form.processing" />
            {{ isEditing ? "Update Project" : "Create Project" }}
          </button>
        </div>

      </form>
    </section>
  </article>
</template>

<style scoped>
.m-select .m-select-list__scroll-wrapper {
  @apply scrollbar scrollbar-none;
}

.m-select-list {
  @apply w-full mt-2;
}

/* FilePond custom styling */
:deep(.filepond--root) {
  @apply rounded-lg;
}

:deep(.filepond--panel-root) {
  @apply bg-gray-50 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600;
}

:deep(.filepond--drop-label) {
  @apply text-gray-600 dark:text-gray-400;
}
</style>
