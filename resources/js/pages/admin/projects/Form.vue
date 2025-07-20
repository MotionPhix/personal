<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";
import { computed, onMounted, ref, watch } from "vue";
import { Customer, Project, GalleryImage } from "@/types";
import AuthLayout from "@/layouts/AuthLayout.vue";
import InputError from "@/components/InputError.vue";

// Icons
import {
  Plus,
  Save,
  X,
  Calendar,
  User,
  Tag,
  Code,
  DollarSign,
  Clock,
  Star,
  Eye,
  Link as LinkIcon,
  Github,
  Figma,
  Image as ImageIcon,
  Trash2,
  Building2,
  FileText,
  Settings,
  Globe,
  Target,
  Zap,
  ArrowLeft,
  Loader2,
  Upload,
  AlertCircle,
  CheckCircle2
} from "lucide-vue-next";

// UI Components
import DatePicker from '@/components/DatePicker.vue';
import { ModalLink } from '@inertiaui/modal-vue';
import ImageUploader from '@/components/ImageUploader.vue';

// Rich text editor
import PreTap from "@/components/PreTap.vue";
import {Input} from "@/components/ui/input";
import {Label} from "@/components/ui/label";
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue} from "@/components/ui/select";
import {Textarea} from "@/components/ui/textarea"
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement, NumberFieldInput
} from "@/components/ui/number-field";
import {Button} from "@/components/ui/button";
import {Switch} from "@/components/ui/switch";

interface FormProps {
  project: Partial<Project>;
  customers: Customer[];
  selectedCustomer?: Customer;
  productionTypes: string[];
  categories: string[];
  isEditing: boolean;
}

const props = defineProps<FormProps>();

// Refs
const tipTapRef = ref();
const posterUploaderRef = ref();
const galleryUploaderRef = ref();

// Track existing media URLs
const existingPosterImages = ref<string[]>([]);
const existingGalleryImages = ref<string[]>([]);

// Form setup
const form = useForm({
  // Basic Information
  name: props.project.name || '',
  description: props.project.description || '',
  short_description: props.project.short_description || '',
  customer_id: props.project.customer?.uuid || props.selectedCustomer?.uuid || null,

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

  // Media - for new uploads
  captured_media: [] as File[],
  poster_image: null as File | null,

  // Keep track of existing media (for editing)
  existing_gallery_images: [] as string[],
  existing_poster_image: null as string | null,
});

const productionTypeOptions = computed(() =>
  props.productionTypes.map(type => ({ value: type, label: type }))
);

const categoryOptions = computed(() =>
  props.categories.map(category => ({ value: category, label: category }))
);

const statusOptions = [
  { value: 'not_started', label: 'Not Started', color: 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300' },
  { value: 'in_progress', label: 'In Progress', color: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' },
  { value: 'on_hold', label: 'On Hold', color: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' },
  { value: 'completed', label: 'Completed', color: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' },
  { value: 'cancelled', label: 'Cancelled', color: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' },
];

const priorityOptions = [
  { value: 'low', label: 'Low', color: 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300' },
  { value: 'medium', label: 'Medium', color: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' },
  { value: 'high', label: 'High', color: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300' },
  { value: 'urgent', label: 'Urgent', color: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' },
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

// Initialize existing media
const initializeExistingMedia = () => {
  // Initialize poster image
  if (props.project.poster_url) {
    existingPosterImages.value = [props.project.poster_url];
    form.existing_poster_image = props.project.poster_url;
  }

  // Initialize gallery images
  if (props.project.gallery_images && props.project.gallery_images.length > 0) {
    existingGalleryImages.value = props.project.gallery_images.map((image: GalleryImage) => image.url);
    form.existing_gallery_images = [...existingGalleryImages.value];
  } else if (props.project.media && props.project.media.length > 0) {
    existingGalleryImages.value = props.project.media.map((image) => image.original_url);
    form.existing_gallery_images = [...existingGalleryImages.value];
  }

  console.log('Initialized existing media:', {
    poster: existingPosterImages.value,
    gallery: existingGalleryImages.value
  });
};

// Handle poster image changes
const handlePosterChange = (file: File | null) => {
  console.log('Poster changed:', file);
  form.poster_image = file;
};

// Handle gallery images changes
const handleGalleryChange = (files: File[]) => {
  console.log('Gallery changed:', files);
  form.captured_media = files;
};

// Handle poster uploader file changes (includes existing files tracking)
const handlePosterFilesChanged = (files: any[]) => {
  console.log('Poster files changed:', files);

  // Update existing poster images list
  const existingFiles = files.filter(f => f.isExisting).map(f => f.url);
  form.existing_poster_image = existingFiles.length > 0 ? existingFiles[0] : null;

  console.log('Updated existing poster:', form.existing_poster_image);
};

// Handle gallery uploader file changes (includes existing files tracking)
const handleGalleryFilesChanged = (files: any[]) => {
  console.log('Gallery files changed:', files);

  // Update existing gallery images list
  const existingFiles = files.filter(f => f.isExisting).map(f => f.url);
  form.existing_gallery_images = existingFiles;

  console.log('Updated existing gallery:', form.existing_gallery_images);
};

// Handle upload errors
const handleUploadError = (message: string) => {
  console.error('Upload error:', message);
  // You can show a toast notification here
};

// Form submission
const onSubmit = () => {
  console.log('=== FORM SUBMISSION DEBUG ===');

  // Get files from uploaders
  const newGalleryFiles = form.captured_media;
  const newPosterFile = form.poster_image;

  console.log('New gallery files:', newGalleryFiles);
  console.log('New poster file:', newPosterFile);
  console.log('Existing gallery images:', form.existing_gallery_images);
  console.log('Existing poster image:', form.existing_poster_image);

  // Build the form data object
  const formData = {
    ...form.data(),
    // Files for upload
    captured_media: newGalleryFiles,
    poster_image: newPosterFile,
  };

  // Only include existing media fields if we're editing and there are existing files to preserve
  if (props.isEditing) {
    // Only add existing_gallery_images if there are files to preserve
    if (form.existing_gallery_images.length > 0) {
      formData.existing_gallery_images = form.existing_gallery_images;
    }

    // Only add existing_poster_image if there's a file to preserve
    if (form.existing_poster_image) {
      formData.existing_poster_image = form.existing_poster_image;
    }
  }

  console.log('Final form data being sent:', {
    hasNewPoster: !!newPosterFile,
    hasExistingPoster: !!form.existing_poster_image,
    newGalleryCount: newGalleryFiles.length,
    existingGalleryCount: form.existing_gallery_images.length,
    formData: formData
  });

  if (props.isEditing && props.project.uuid) {
    form.transform(() => ({ ...formData, _method: 'put' }))
      .post(route("admin.projects.update", props.project.uuid), {
        preserveScroll: true,
        onSuccess: () => {
          console.log('Project updated successfully');
        },
        onError: (errors) => {
          console.error('Update errors:', errors);
        }
      });
  } else {
    // For new projects, we don't need existing files
    const { existing_gallery_images, existing_poster_image, ...createData } = formData;

    form.transform(() => createData)
      .post(route('admin.projects.store'), {
        preserveScroll: true,
        onSuccess: () => {
          console.log('Project created successfully');
          form.reset();

          // Reset uploaders
          posterUploaderRef.value?.cleanup();
          galleryUploaderRef.value?.cleanup();
          tipTapRef.value?.resetEditorContent();
        },
        onError: (errors) => {
          console.error('Create errors:', errors);
        }
      });
  }
};

// Customer assignment from modal
const onAssignContact = () => {
  if (props.selectedCustomer) {
    form.customer_id = props.selectedCustomer.uuid;
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
  initializeExistingMedia();
});

defineOptions({
  layout: AuthLayout,
});
</script>

<template>
  <Head :title="isEditing ? `Edit ${project.name}` : 'Create New Project'" />

  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-2">
          <div>
            <Link
              v-if="isEditing && project.uuid"
              :href="route('admin.projects.show', project.uuid)"
              class="py-1 flex items-center gap-x-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            >
              <ArrowLeft class="size-4" /> Project details
            </Link>

            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
              {{ isEditing ? 'Edit Project' : 'Create New Project' }}
            </h1>

            <p class="text-sm text-gray-500 dark:text-gray-400" v-if="isEditing">
              {{ project.name }}
            </p>
          </div>

          <div class="flex items-center space-x-3">
            <Link
              :href="route('admin.projects.index')"
              class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors"
            >
              Cancel
            </Link>

            <Button
              type="submit"
              variant="default"
              @click.prevent="onSubmit"
              :disabled="form.processing">
              <Loader2 v-if="form.processing" class="animate-spin mr-2" />
              <Save v-else class="mr-2" />
              {{ isEditing ? 'Update' : 'Create' }}
            </Button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Content -->
    <div class="px-4 sm:px-6 lg:px-8 py-8">
      <div class="max-w-3xl mx-auto">
        <form @submit.prevent="onSubmit" class="space-y-8">

          <!-- Basic Information -->
          <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                <FileText class="h-5 w-5 text-blue-600 dark:text-blue-400" />
              </div>

              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Basic Information
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Essential project details and client information
                </p>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
              <!-- Project Name -->
              <div>
                <Label for="name">
                  Project Name <span class="text-red-500">*</span>
                </Label>

                <Input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  placeholder="Enter project name"
                />

                <InputError :message="form.errors.name" />
              </div>

              <!-- Customer Selection -->
              <div>
                <Label for="customer">
                  Customer <span class="text-red-500">*</span>
                </Label>

                <div class="relative">
                  <Select
                    v-model="form.customer_id"
                    required>
                    <SelectTrigger>
                      <SelectValue placeholder="Select a customer" />
                    </SelectTrigger>

                    <SelectContent>
                      <SelectItem :value="null">None</SelectItem>
                      <SelectItem
                        v-for="customer in customers"
                        :key="customer.value" :value="customer.value">
                        {{ customer.label }} {{ customer.company !== 'Individual' ? `(${customer.company})` : '' }}
                      </SelectItem>
                    </SelectContent>
                  </Select>

                  <ModalLink
                    :href="route('admin.customers.create')"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors"
                    :data="{ 'modal': 'show' }"
                    @close="onAssignContact"
                    preserve-scroll
                    type="button"
                    as="button">
                    <Plus class="h-4 w-4" />
                  </ModalLink>
                </div>

                <InputError :message="form.errors.customer_id" />
              </div>

              <!-- Short Description -->
              <div class="col-span-2">
                <Label for="short_description">
                  Short Description
                </Label>

                <Textarea
                  id="short_description"
                  v-model="form.short_description"
                  rows="3"
                  placeholder="Brief description for project cards and previews"
                />

                <InputError :message="form.errors.short_description" />
              </div>
            </div>
          </div>

          <!-- Project Classification -->
          <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                <Tag class="h-5 w-5 text-purple-600 dark:text-purple-400" />
              </div>

              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Project Classification
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Categorize and organize your project
                </p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Production Type -->
              <div>
                <Label for="production_type">
                  Production Type
                </Label>

                <Select
                  v-model="form.production_type">
                  <SelectTrigger>
                    <SelectValue placeholder="Select type" />
                  </SelectTrigger>

                  <SelectContent>
                    <SelectItem
                      v-for="type in productionTypeOptions"
                      :key="type.value" :value="type.value">
                      {{ type.label }}
                    </SelectItem>
                  </SelectContent>
                </Select>

                <InputError :message="form.errors.production_type" />
              </div>

              <!-- Category -->
              <div>
                <Label for="category">
                  Category
                </Label>

                <Select
                  v-model="form.category">
                  <SelectTrigger>
                    <SelectValue placeholder="Select category" />
                  </SelectTrigger>

                  <SelectContent>
                    <SelectItem
                      v-for="category in categoryOptions"
                      :key="category.value" :value="category.value">
                      {{ category.label }}
                    </SelectItem>
                  </SelectContent>
                </Select>

                <InputError :message="form.errors.category" />
              </div>

              <!-- Status -->
              <div>
                <Label for="status">
                  Status
                </Label>

                <Select
                  v-model="form.status">
                  <SelectTrigger>
                    <SelectValue placeholder="Select status" />
                  </SelectTrigger>

                  <SelectContent>
                    <SelectItem
                      v-for="status in statusOptions"
                      :key="status.value" :value="status.value">
                      {{ status.label }}
                    </SelectItem>
                  </SelectContent>
                </Select>

                <InputError :message="form.errors.status" />
              </div>

              <!-- Priority -->
              <div>
                <Label for="priority">
                  Priority
                </Label>

                <Select
                  v-model="form.priority">
                  <SelectTrigger>
                    <SelectValue placeholder="Select priority" />
                  </SelectTrigger>

                  <SelectContent>
                    <SelectItem
                      v-for="priority in priorityOptions"
                      :key="priority.value" :value="priority.value">
                      {{ priority.label }}
                    </SelectItem>
                  </SelectContent>
                </Select>

                <InputError :message="form.errors.priority" />
              </div>
            </div>
          </div>

          <!-- Timeline & Budget -->
          <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                <Calendar class="h-5 w-5 text-green-600 dark:text-green-400" />
              </div>

              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Timeline & Budget
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Project scheduling and resource planning
                </p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Start Date -->
              <div>
                <Label>
                  Start Date
                </Label>

                <DatePicker
                  v-model="form.start_date"
                  class="w-full"
                />

                <InputError :message="form.errors.start_date" />
              </div>

              <!-- End Date -->
              <div>
                <Label>
                  End Date
                </Label>

                <DatePicker
                  v-model="form.end_date"
                  class="w-full"
                />
                <InputError :message="form.errors.end_date" />
              </div>

              <!-- Budget -->
              <div>
                <NumberField
                  id="budget"
                  v-model="form.budget"
                  :format-options="{
                    style: 'currency',
                    currency: 'MWK',
                    currencyDisplay: 'code',
                    currencySign: 'accounting',
                  }"
                  :min="0">
                  <Label for="budget">Budget (MWK)</Label>
                  <NumberFieldContent>
                    <NumberFieldDecrement />
                    <NumberFieldInput />
                    <NumberFieldIncrement />
                  </NumberFieldContent>
                </NumberField>

                <InputError :message="form.errors.budget" />
              </div>

              <!-- Estimated Hours -->
              <div>
                <NumberField
                  id="estimated_hours"
                  v-model="form.estimated_hours"
                  :format-options="{
                    style: 'decimal',
                    trailingZeroDisplay: 'auto'
                  }"
                  :min="0">
                  <Label for="estimated_hours">Estimated Hours</Label>
                  <NumberFieldContent>
                    <NumberFieldDecrement />
                    <NumberFieldInput />
                    <NumberFieldIncrement />
                  </NumberFieldContent>
                </NumberField>

                <InputError :message="form.errors.estimated_hours" />
              </div>

              <!-- Actual Hours -->
              <div>
                <NumberField
                  id="actual_hours"
                  v-model="form.actual_hours"
                  :format-options="{
                    style: 'decimal',
                    trailingZeroDisplay: 'auto'
                  }"
                  :min="0">
                  <Label for="actual_hours">Actual Hours</Label>
                  <NumberFieldContent>
                    <NumberFieldDecrement />
                    <NumberFieldInput />
                    <NumberFieldIncrement />
                  </NumberFieldContent>
                </NumberField>

                <InputError :message="form.errors.actual_hours" />
              </div>

              <!-- Sort Order -->
              <div>
                <NumberField
                  id="sort_order"
                  v-model="form.sort_order"
                  :min="0">
                  <Label for="sort_order">Sort Order</Label>
                  <NumberFieldContent>
                    <NumberFieldDecrement />
                    <NumberFieldInput />
                    <NumberFieldIncrement />
                  </NumberFieldContent>
                </NumberField>

                <InputError :message="form.errors.sort_order" />
              </div>
            </div>
          </div>

          <!-- Technologies -->
          <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-orange-100 dark:bg-orange-900 rounded-lg">
                <Code class="h-5 w-5 text-orange-600 dark:text-orange-400" />
              </div>
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Technologies Used</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Technical stack and tools</p>
              </div>
            </div>

            <div class="space-y-4">
              <!-- Add Technology -->
              <div class="flex gap-x-2">
                <Input
                  v-model="newTechnology"
                  type="text"
                  placeholder="Add a technology (e.g., Laravel, Vue.js)"
                  class="flex-1"
                  @keyup.enter="addTechnology"
                />

                <Button
                  v-if="newTechnology !== ''"
                  size="icon"
                  type="button"
                  variant="ghost"
                  @click="addTechnology">
                  <Plus />
                </Button>
              </div>

              <!-- Technology Tags -->
              <div class="flex flex-wrap gap-2" v-if="form.technologies.length > 0">
                <span
                  v-for="(tech, index) in form.technologies"
                  :key="index"
                  class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full dark:bg-blue-900/30 dark:text-blue-300"
                >
                  {{ tech }}
                  <button
                    type="button"
                    @click="removeTechnology(index)"
                    class="ml-1 hover:text-blue-600 transition-colors"
                  >
                    <X class="h-3 w-3" />
                  </button>
                </span>
              </div>
            </div>
          </div>

          <!-- Features -->
          <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                <Star class="h-5 w-5 text-yellow-600 dark:text-yellow-400" />
              </div>
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Key Features</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Highlight important project features</p>
              </div>
            </div>

            <div class="space-y-4">
              <!-- Add Feature -->
              <div class="flex gap-2">
                <Input
                  v-model="newFeature"
                  type="text"
                  placeholder="Add a key feature"
                  class="flex-1"
                  @keyup.enter="addFeature"
                />

                <Button
                  v-if="newFeature !== ''"
                  type="button"
                  @click="addFeature">
                  <Plus />
                </Button>
              </div>

              <!-- Feature List -->
              <div class="space-y-2" v-if="form.features.length > 0">
                <div
                  v-for="(feature, index) in form.features"
                  :key="index"
                  class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                >
                  <span class="text-gray-900 dark:text-gray-100">{{ feature }}</span>
                  <button
                    type="button"
                    @click="removeFeature(index)"
                    class="text-red-500 hover:text-red-700 transition-colors"
                  >
                    <Trash2 class="h-4 w-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Project Description -->
          <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                <FileText class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
              </div>
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Project Description
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Detailed project information
                </p>
              </div>
            </div>

            <PreTap
              ref="tipTapRef"
              v-model="form.description"
              placeholder="Describe the project in detail..."
            />

            <InputError :message="form.errors.description" />
          </div>

          <!-- Project Story -->
          <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-pink-100 dark:bg-pink-900 rounded-lg">
                <Target class="h-5 w-5 text-pink-600 dark:text-pink-400" />
              </div>

              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Project Story
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Challenges, solutions, and outcomes
                </p>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
              <!-- Challenges -->
              <div>
                <Label
                  for="challenges">
                  Challenges
                </Label>

                <textarea
                  id="challenges"
                  v-model="form.challenges"
                  rows="4"
                  auto-resize
                  placeholder="What challenges did you face?"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                ></textarea>
                <InputError :message="form.errors.challenges" />
              </div>

              <!-- Solutions -->
              <div>
                <Label for="solutions">
                  Solutions
                </Label>
                <textarea
                  id="solutions"
                  v-model="form.solutions"
                  rows="4"
                  placeholder="How did you solve them?"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                ></textarea>
                <InputError :message="form.errors.solutions" />
              </div>

              <!-- Results -->
              <div>
                <Label for="results">
                  Results
                </Label>
                <textarea
                  id="results"
                  v-model="form.results"
                  rows="4"
                  placeholder="What were the outcomes?"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                ></textarea>
                <InputError :message="form.errors.results" />
              </div>

              <!-- Client Feedback -->
              <div>
                <Label for="client_feedback">
                  Client Feedback
                </Label>

                <textarea
                  id="client_feedback"
                  v-model="form.client_feedback"
                  rows="4"
                  placeholder="What did the client say?"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                ></textarea>
                <InputError :message="form.errors.client_feedback" />
              </div>
            </div>
          </div>

          <!-- External Links -->
          <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-cyan-100 dark:bg-cyan-900 rounded-lg">
                <LinkIcon class="h-5 w-5 text-cyan-600 dark:text-cyan-400" />
              </div>
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">External Links</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Project URLs and social links</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Live URL -->
              <div>
                <Label for="live_url">
                  Live Site URL
                </Label>
                <div class="relative">
                  <Globe class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                  <Input
                    id="live_url"
                    v-model="form.live_url"
                    type="url"
                    class="pl-9"
                    placeholder="https://example.com"
                  />
                </div>
                <InputError :message="form.errors.live_url" />
              </div>

              <!-- GitHub URL -->
              <div>
                <Label for="github_url">
                  GitHub Repository
                </Label>

                <div class="relative">
                  <Github class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                  <Input
                    id="github_url"
                    v-model="form.github_url"
                    type="url"
                    class="pl-9"
                    placeholder="https://github.com/username/repo"
                  />
                </div>
                <InputError :message="form.errors.github_url" />
              </div>

              <!-- Figma URL -->
              <div>
                <Label for="figma_url">
                  Figma Design
                </Label>

                <div class="relative">
                  <Figma class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                  <Input
                    id="figma_url"
                    v-model="form.figma_url"
                    type="url"
                    class="pl-9"
                    placeholder="https://figma.com/file/..."
                  />
                </div>
                <InputError :message="form.errors.figma_url" />
              </div>

              <!-- Behance URL -->
              <div>
                <Label for="behance_url">
                  Behance Project
                </Label>

                <div class="relative">
                  <Eye class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                  <Input
                    id="behance_url"
                    v-model="form.behance_url"
                    type="url"
                    class="pl-9"
                    placeholder="https://behance.net/gallery/..."
                  />
                </div>
                <InputError :message="form.errors.behance_url" />
              </div>

              <!-- Dribbble URL -->
              <div>
                <Label for="dribbble_url">
                  Dribbble Shot
                </Label>

                <div class="relative">
                  <Zap class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                  <Input
                    id="dribbble_url"
                    v-model="form.dribbble_url"
                    type="url"
                    class="pl-9"
                    placeholder="https://dribbble.com/shots/..."
                  />
                </div>
                <InputError :message="form.errors.dribbble_url" />
              </div>
            </div>
          </div>

          <!-- Project Poster -->
          <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                <ImageIcon class="h-5 w-5 text-purple-600 dark:text-purple-400" />
              </div>

              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                  Project Poster
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                  Upload the main poster/hero image for this project
                </p>
              </div>
            </div>

            <ImageUploader
              ref="posterUploaderRef"
              :model-value="form.poster_image"
              :existing-files="existingPosterImages"
              :multiple="false"
              :max-files="1"
              :max-file-size="5"
              placeholder="Drop poster image here or click to browse"
              @update:model-value="handlePosterChange"
              @files-changed="handlePosterFilesChanged"
              @error="handleUploadError"
            />

            <InputError :message="form.errors.poster_image" />
          </div>

          <!-- Project Gallery -->
          <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                <ImageIcon class="h-5 w-5 text-red-600 dark:text-red-400" />
              </div>
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Project Gallery</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Upload additional project images and screenshots</p>
              </div>
            </div>

            <ImageUploader
              ref="galleryUploaderRef"
              :model-value="form.captured_media"
              :existing-files="existingGalleryImages"
              :multiple="true"
              :max-files="10"
              :max-file-size="5"
              placeholder="Drop project images here or click to browse"
              @update:model-value="handleGalleryChange"
              @files-changed="handleGalleryFilesChanged"
              @error="handleUploadError"
            />

            <InputError :message="form.errors.captured_media" />
          </div>

          <!-- SEO & Settings -->
          <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center space-x-3 mb-6">
              <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <Settings class="h-5 w-5 text-gray-600 dark:text-gray-400" />
              </div>
              <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">SEO & Settings</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Search optimization and project settings</p>
              </div>
            </div>

            <div class="space-y-6">
              <!-- SEO Fields -->
              <div class="grid grid-cols-1 gap-6">
                <div>
                  <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Meta Title
                  </label>
                  <input
                    id="meta_title"
                    v-model="form.meta_title"
                    type="text"
                    placeholder="SEO title for search engines"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
                  <InputError :message="form.errors.meta_title" />
                </div>

                <div>
                  <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Meta Description
                  </label>
                  <textarea
                    id="meta_description"
                    v-model="form.meta_description"
                    rows="3"
                    placeholder="SEO description for search engines"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  ></textarea>
                  <InputError :message="form.errors.meta_description" />
                </div>
              </div>

              <!-- Settings -->
              <div class="flex flex-wrap gap-6">
                <Label class="flex items-center">
                  <Switch
                    :model-value="form.is_featured"
                    @update:model-value="form.is_featured = !form.is_featured"
                  />

                  <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Featured Project
                  </span>
                </Label>

                <Label class="flex items-center">
                  <Switch
                    :model-value="form.is_public"
                    @update:model-value="form.is_public = !form.is_public"
                  />
                  <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Public Project
                  </span>
                </Label>
              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Date picker styling */
:deep(.vc-container) {
  @apply w-full;
}

:deep(.vc-input) {
  @apply w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent;
}
</style>
