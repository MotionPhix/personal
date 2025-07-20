<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed, onMounted, nextTick, watch } from "vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import { gsap } from "gsap";

// Icons
import {
  ArrowLeft,
  Upload,
  Save,
  X,
  Plus,
  Star,
  Globe,
  Tag,
  Building,
  FolderOpen,
  FileText,
  Image,
  Eye,
  AlertCircle,
  CheckCircle,
  Loader2,
  Sparkles,
  Zap,
  Download
} from "lucide-vue-next";

// UI Components
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Switch } from "@/components/ui/switch";
import { Badge } from "@/components/ui/badge";
import { Progress } from "@/components/ui/progress";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { useStorage } from "@vueuse/core";

interface Props {
  options: {
    categories: string[];
    file_types: string[];
    brands: string[];
  };
}

const props = defineProps<Props>();

defineOptions({
  layout: AuthLayout
});

// Form setup
const form = useForm({
  title: '',
  description: '',
  brand: '',
  category: null as string | null,
  is_featured: false,
  is_public: true,
  sort_order: 0,
  meta_title: '',
  meta_description: '',
  tags: [] as string[],
  poster_image: null as File | null,
  download_file: null as File | null,
});

// Category creation form
const categoryForm = useForm({
  name: '',
});

// Reactive state
const activeTab = useStorage('downloads_active_tab', 'basic');
const posterPreview = ref<string | null>(null);
const downloadFileInfo = ref<{ name: string; size: number; type: string } | null>(null);
const uploadProgress = ref(0);
const isUploading = ref(false);
const newTag = ref('');
const showSuccessAnimation = ref(false);

// Category modal state
const showCategoryModal = ref(false);
const availableCategories = ref([...props.options.categories]);

// Refs for animations and auto-growing textarea
const headerRef = ref<HTMLElement>();
const contentRef = ref<HTMLElement>();
const cardsRef = ref<HTMLElement[]>([]);
const descriptionTextarea = ref<HTMLTextAreaElement>();
const metaDescriptionTextarea = ref<HTMLTextAreaElement>();

// Auto-growing textarea functionality
const autoGrowTextarea = (textarea: HTMLTextAreaElement | null) => {
  if (!textarea) return;

  textarea.style.height = 'auto';
  textarea.style.height = textarea.scrollHeight + 'px';
};

// Handle textarea input events
const handleDescriptionInput = () => {
  nextTick(() => {
    autoGrowTextarea(descriptionTextarea.value);
  });
};

const handleMetaDescriptionInput = () => {
  nextTick(() => {
    autoGrowTextarea(metaDescriptionTextarea.value);
  });
};

// Watch for description changes to auto-grow
watch(() => form.description, () => {
  nextTick(() => {
    autoGrowTextarea(descriptionTextarea.value);
  });
});

// Watch for meta description changes to auto-grow
watch(() => form.meta_description, () => {
  nextTick(() => {
    autoGrowTextarea(metaDescriptionTextarea.value);
  });
});

// Computed
const formattedFileSize = computed(() => {
  if (!downloadFileInfo.value) return '';
  const bytes = downloadFileInfo.value.size;
  const k = 1024;
  const sizes = ['B', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
});

const canSubmit = computed(() => {
  return form.title && form.download_file && !form.processing;
});

const completionPercentage = computed(() => {
  let completed = 0;
  const total = 6;

  if (form.title) completed++;
  if (form.description) completed++;
  if (form.brand) completed++;
  if (form.category) completed++;
  if (form.download_file) completed++;
  if (posterPreview.value) completed++;

  return Math.round((completed / total) * 100);
});

const metaTitlePreview = computed(() => {
  return form.meta_title || `${form.title} - Download`;
});

const metaDescriptionPreview = computed(() => {
  return form.meta_description || form.description?.substring(0, 160) || '';
});

const canCreateCategory = computed(() => {
  return categoryForm.name.trim().length > 0 && !categoryForm.processing;
});

// Category management
const openCategoryModal = () => {
  showCategoryModal.value = true;
  categoryForm.reset();

  nextTick(() => {
    const modal = document.querySelector('[role="dialog"]');
    if (modal) {
      gsap.fromTo(modal,
        { scale: 0.95, opacity: 0 },
        { scale: 1, opacity: 1, duration: 0.2, ease: "power2.out" }
      );
    }
  });
};

const createCategory = () => {
  if (!canCreateCategory.value) return;

  // Simulate API call - replace with actual API call
  const newCategory = categoryForm.name.trim();

  // Add to available categories
  availableCategories.value.push(newCategory);

  // Set as selected category
  form.category = newCategory;

  // Close modal
  showCategoryModal.value = false;

  // Reset form
  categoryForm.reset();

  // Show success notification
  showNotification(`Category "${newCategory}" created successfully!`, 'success');

  // Animate the select to show the new selection
  nextTick(() => {
    const selectTrigger = document.querySelector('[data-testid="category-select"]');
    if (selectTrigger) {
      gsap.fromTo(selectTrigger,
        { scale: 1.05, backgroundColor: 'rgba(34, 197, 94, 0.1)' },
        { scale: 1, backgroundColor: 'transparent', duration: 0.5, ease: "power2.out" }
      );
    }
  });
};

// File handling methods
const handlePosterUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (file) {
    if (!file.type.startsWith('image/')) {
      showNotification('Please select an image file', 'error');
      return;
    }

    if (file.size > 5 * 1024 * 1024) {
      showNotification('Image file must be less than 5MB', 'error');
      return;
    }

    form.poster_image = file;

    const reader = new FileReader();
    reader.onload = (e) => {
      posterPreview.value = e.target?.result as string;

      nextTick(() => {
        const previewEl = document.querySelector('.poster-preview');
        if (previewEl) {
          gsap.fromTo(previewEl,
            { scale: 0.8, opacity: 0, y: 20 },
            { scale: 1, opacity: 1, y: 0, duration: 0.5, ease: "back.out(1.7)" }
          );
        }
      });
    };
    reader.readAsDataURL(file);

    showNotification('Poster image uploaded successfully!', 'success');
  }
};

const handleDownloadFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (file) {
    if (file.size > 50 * 1024 * 1024) {
      showNotification('Download file must be less than 50MB', 'error');
      return;
    }

    form.download_file = file;
    downloadFileInfo.value = {
      name: file.name,
      size: file.size,
      type: file.type
    };

    nextTick(() => {
      const fileInfoEl = document.querySelector('.file-info');
      if (fileInfoEl) {
        gsap.fromTo(fileInfoEl,
          { x: -20, opacity: 0 },
          { x: 0, opacity: 1, duration: 0.4, ease: "power2.out" }
        );
      }
    });

    showNotification('Download file uploaded successfully!', 'success');
  }
};

const removePosterImage = () => {
  const previewEl = document.querySelector('.poster-preview');
  if (previewEl) {
    gsap.to(previewEl, {
      scale: 0.8,
      opacity: 0,
      y: -20,
      duration: 0.3,
      ease: "power2.in",
      onComplete: () => {
        form.poster_image = null;
        posterPreview.value = null;
      }
    });
  } else {
    form.poster_image = null;
    posterPreview.value = null;
  }
};

const removeDownloadFile = () => {
  const fileInfoEl = document.querySelector('.file-info');
  if (fileInfoEl) {
    gsap.to(fileInfoEl, {
      x: 20,
      opacity: 0,
      duration: 0.3,
      ease: "power2.in",
      onComplete: () => {
        form.download_file = null;
        downloadFileInfo.value = null;
      }
    });
  } else {
    form.download_file = null;
    downloadFileInfo.value = null;
  }
};

// Tag management
const addTag = () => {
  if (newTag.value.trim() && !form.tags.includes(newTag.value.trim())) {
    form.tags.push(newTag.value.trim());
    newTag.value = '';

    nextTick(() => {
      const tags = document.querySelectorAll('.tag-item');
      const lastTag = tags[tags.length - 1];
      if (lastTag) {
        gsap.fromTo(lastTag,
          { scale: 0, opacity: 0 },
          { scale: 1, opacity: 1, duration: 0.3, ease: "back.out(1.7)" }
        );
      }
    });
  }
};

const removeTag = (index: number) => {
  const tagEl = document.querySelectorAll('.tag-item')[index];
  if (tagEl) {
    gsap.to(tagEl, {
      scale: 0,
      opacity: 0,
      duration: 0.2,
      ease: "power2.in",
      onComplete: () => {
        form.tags.splice(index, 1);
      }
    });
  } else {
    form.tags.splice(index, 1);
  }
};

// Form submission
const submit = () => {
  if (!canSubmit.value) return;

  isUploading.value = true;
  uploadProgress.value = 0;

  const progressTl = gsap.timeline();
  progressTl.to(uploadProgress, {
    value: 90,
    duration: 2,
    ease: "power2.out"
  });

  form.post(route('admin.downloads.store'), {
    onSuccess: () => {
      uploadProgress.value = 100;
      showSuccessAnimation.value = true;

      gsap.timeline()
        .to('.upload-progress', { scale: 1.05, duration: 0.2 })
        .to('.upload-progress', { scale: 1, duration: 0.2 })
        .call(() => {
          setTimeout(() => {
            isUploading.value = false;
          }, 1000);
        });
    },
    onError: () => {
      isUploading.value = false;
      uploadProgress.value = 0;
      showNotification('Failed to create download. Please try again.', 'error');
    }
  });
};

// Auto-generate meta title
const updateMetaTitle = () => {
  if (!form.meta_title && form.title) {
    form.meta_title = `${form.title} - Download`;
  }
};

// Notification system
const showNotification = (message: string, type: 'success' | 'error') => {
  console.log(`${type}: ${message}`);
};

// Animation functions
const animateHeader = () => {
  if (headerRef.value) {
    gsap.fromTo(headerRef.value,
      { y: -30, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.6, ease: "power3.out" }
    );
  }
};

const animateContent = () => {
  if (contentRef.value) {
    gsap.fromTo(contentRef.value,
      { y: 20, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.6, delay: 0.2, ease: "power2.out" }
    );
  }
};

const animateCards = () => {
  if (cardsRef.value.length > 0) {
    gsap.fromTo(cardsRef.value,
      { y: 30, opacity: 0 },
      {
        y: 0,
        opacity: 1,
        duration: 0.5,
        stagger: 0.1,
        ease: "power2.out"
      }
    );
  }
};

// Lifecycle
onMounted(() => {
  nextTick(() => {
    animateHeader();
    animateContent();
    animateCards();

    // Initialize auto-growing textareas
    autoGrowTextarea(descriptionTextarea.value);
    autoGrowTextarea(metaDescriptionTextarea.value);
  });
});

// Watch for tab changes
watch(activeTab, () => {
  nextTick(() => {
    animateCards();
  });
});
</script>

<template>
  <Head title="Create Download" />

  <div class="min-h-screen">
    <!-- Modern Header -->
    <div ref="headerRef" class="border-b bg-card/50 backdrop-blur-xl sticky top-0 z-50">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <!-- Left Section -->
          <div class="flex items-center gap-4">
            <Link :href="route('admin.downloads.index')" as="button">
              <Button variant="ghost" size="sm" class="gap-2">
                <ArrowLeft class="h-4 w-4" />
                <span class="hidden sm:inline">Back</span>
              </Button>
            </Link>

            <div class="h-6 w-px bg-border hidden sm:block" />

            <div class="flex items-center gap-3">
              <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10">
                <Download class="h-5 w-5 text-primary" />
              </div>

              <div class="hidden sm:block">
                <h1 class="text-lg font-semibold">Create Download</h1>
                <p class="text-sm text-muted-foreground">Add new downloadable content</p>
              </div>
            </div>
          </div>

          <!-- Right Section -->
          <div class="flex items-center gap-3">
            <!-- Progress Indicator -->
            <div class="hidden md:flex items-center gap-3">
              <div class="text-right">
                <div class="text-sm font-medium">{{ completionPercentage }}% Complete</div>
                <Progress v-model="completionPercentage" class="w-24 h-1.5" />
              </div>
              <Badge :variant="completionPercentage === 100 ? 'default' : 'secondary'" class="gap-1">
                <CheckCircle v-if="completionPercentage === 100" class="h-4 w-4" />
                {{ completionPercentage === 100 ? 'Ready' : 'Draft' }}
              </Badge>
            </div>

            <div class="h-6 w-px bg-border hidden md:block" />

            <!-- Action Buttons -->
            <Button
              size="icon"
              variant="outline"
              @click="activeTab = 'preview'"
              :disabled="!form.title"
              class="gap-2">
              <Eye class="h-4 w-4" />
              <span class="hidden sm:inline">Preview</span>
            </Button>

            <Button
              @click="submit"
              :disabled="!canSubmit || isUploading"
              size="sm">
              <Loader2 v-if="isUploading" class="h-4 w-4 animate-spin" />
              <Save v-else class="h-4 w-4" />
              {{ isUploading ? 'Creating...' : 'Create' }}
            </Button>
          </div>
        </div>
      </div>
    </div>

    <!-- Upload Progress Overlay -->
    <div v-if="isUploading" class="fixed inset-0 bg-background/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <Card class="w-full max-w-md upload-progress">
        <CardContent class="p-6 text-center space-y-4">
          <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 mx-auto">
            <Loader2 class="h-6 w-6 text-primary animate-spin" />
          </div>

          <div>
            <h3 class="font-semibold">Creating Download</h3>
            <p class="text-sm text-muted-foreground">Processing your files...</p>
          </div>

          <div class="space-y-2">
            <div class="flex justify-between text-sm">
              <span>Progress</span>
              <span>{{ Math.round(uploadProgress) }}%</span>
            </div>

            <Progress v-model="uploadProgress" class="h-2" />
          </div>

          <div v-if="showSuccessAnimation" class="text-green-600">
            <CheckCircle class="h-6 w-6 mx-auto" />
            <p class="text-sm mt-1">Success!</p>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Category Creation Modal -->
    <Dialog v-model:open="showCategoryModal">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle class="flex items-center gap-2">
            <FolderOpen class="h-5 w-5" />
            Create New Category
          </DialogTitle>
          <DialogDescription>
            Add a new category for organizing your downloads.
          </DialogDescription>
        </DialogHeader>

        <div class="space-y-4 py-4">
          <div class="space-y-2">
            <Label for="category-name">Category Name *</Label>
            <Input
              id="category-name"
              v-model="categoryForm.name"
              placeholder="Enter category name"
              :class="categoryForm.errors.name && 'border-destructive'"
              @keydown.enter.prevent="createCategory"
            />
            <p v-if="categoryForm.errors.name" class="text-sm text-destructive flex items-center gap-1">
              <AlertCircle class="h-3 w-3" />
              {{ categoryForm.errors.name }}
            </p>
          </div>
        </div>

        <DialogFooter>
          <Button variant="outline" @click="showCategoryModal = false">
            Cancel
          </Button>
          <Button
            @click="createCategory"
            :disabled="!canCreateCategory"
            class="gap-2"
          >
            <Loader2 v-if="categoryForm.processing" class="h-4 w-4 animate-spin" />
            <Plus v-else class="h-4 w-4" />
            Create Category
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Main Content -->
    <div ref="contentRef" class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">

      <!-- Mobile Progress -->
      <div class="md:hidden mb-6">
        <div class="flex items-center justify-between mb-2">
          <span class="text-sm font-medium">Progress</span>
          <span class="text-sm text-muted-foreground">{{ completionPercentage }}%</span>
        </div>
        <Progress :value="completionPercentage" class="h-2" />
      </div>

      <!-- Form Tabs -->
      <Tabs v-model="activeTab" class="space-y-6">
        <!-- Tab Navigation -->
        <div>
          <TabsList class="grid max-w-md mx-auto w-full grid-cols-2 md:grid-cols-4 h-auto p-1 bg-muted/50">
            <TabsTrigger value="basic" class="flex-col gap-1 py-3 data-[state=active]:bg-background">
              <FileText class="size-8" />
              <span class="text-xl">Basic</span>
            </TabsTrigger>

            <TabsTrigger value="files" class="flex-col gap-1 py-3 data-[state=active]:bg-background">
              <Upload class="size-8" />
              <span class="text-xl">Files</span>
            </TabsTrigger>

            <TabsTrigger value="seo" class="flex-col gap-1 py-3 data-[state=active]:bg-background">
              <Tag class="size-8" />
              <span class="text-xl">SEO</span>
            </TabsTrigger>

            <TabsTrigger value="preview" class="flex-col gap-1 py-3 data-[state=active]:bg-background">
              <Eye class="size-8" />
              <span class="text-xl">Preview</span>
            </TabsTrigger>
          </TabsList>
        </div>

        <!-- Basic Information Tab -->
        <TabsContent value="basic" class="space-y-6">

          <!-- Basic Details -->
          <Card ref="el => cardsRef[0] = el as HTMLElement">
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <FileText class="h-5 w-5" />
                Basic Information
              </CardTitle>
              <CardDescription>
                Enter the essential details for your download
              </CardDescription>
            </CardHeader>
            <CardContent class="space-y-6">

              <!-- Title -->
              <div class="space-y-2">
                <Label for="title">Title *</Label>
                <Input
                  id="title"
                  v-model="form.title"
                  placeholder="Enter download title"
                  :class="form.errors.title && 'border-destructive'"
                  @blur="updateMetaTitle"
                />
                <p v-if="form.errors.title" class="text-sm text-destructive flex items-center gap-1">
                  <AlertCircle class="h-3 w-3" />
                  {{ form.errors.title }}
                </p>
              </div>

              <!-- Description with Auto-growing Textarea -->
              <div class="space-y-2">
                <Label for="description">Description</Label>
                <Textarea
                  id="description"
                  ref="descriptionTextarea"
                  v-model="form.description"
                  placeholder="Describe your download"
                  class="resize-none min-h-[500px] transition-all duration-200"
                  @input="handleDescriptionInput"
                  rows="7"
                />
                <p class="text-xs text-muted-foreground">
                  {{ form.description?.length || 0 }}/500 characters
                </p>
              </div>

              <!-- Brand and Category -->
              <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                  <Label for="brand">Brand</Label>
                  <div class="relative">
                    <Building class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                      id="brand"
                      v-model="form.brand"
                      placeholder="Brand name"
                      class="pl-10"
                    />
                  </div>
                </div>

                <div class="space-y-2">
                  <Label for="category">Category</Label>
                  <div class="flex gap-2">
                    <div class="relative flex-1">
                      <FolderOpen class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground z-10" />
                      <Select v-model="form.category" data-testid="category-select">
                        <SelectTrigger class="pl-10">
                          <SelectValue placeholder="Select category" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectItem :value="null">No Category</SelectItem>
                          <SelectItem v-for="category in availableCategories" :key="category" :value="category">
                            {{ category }}
                          </SelectItem>
                        </SelectContent>
                      </Select>

                      <Button
                        type="button"
                        variant="outline"
                        size="icon"
                        @click="openCategoryModal"
                        class="shrink-0 absolute right-1 top-1/2 -translate-y-1/2">
                        <Plus class="h-4 w-4" />
                      </Button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tags -->
              <div class="space-y-3">
                <Label>Tags</Label>

                <div class="flex gap-2">
                  <div class="relative flex-1">
                    <Tag class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                      v-model="newTag"
                      placeholder="Add tag and press Enter"
                      class="pl-10 pr-12"
                      @keydown.enter.prevent="addTag"
                    />
                    <Button
                      class="absolute right-1 top-1/2 -translate-y-1/2"
                      type="button"
                      variant="ghost"
                      size="sm"
                      @click="addTag"
                      :disabled="!newTag.trim()">
                      <Plus class="h-4 w-4" />
                    </Button>
                  </div>
                </div>

                <div v-if="form.tags.length > 0" class="flex flex-wrap gap-2">
                  <Badge
                    v-for="(tag, index) in form.tags"
                    :key="index"
                    variant="secondary"
                    class="tag-item gap-1">
                    {{ tag }}
                    <button
                      type="button"
                      @click="removeTag(index)"
                      class="hover:text-destructive">
                      <X class="h-3 w-3" />
                    </button>
                  </Badge>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Settings -->
          <Card ref="el => cardsRef[1] = el as HTMLElement">
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Star class="h-5 w-5" />
                Settings
              </CardTitle>

              <CardDescription>
                Configure visibility and display options
              </CardDescription>
            </CardHeader>
            <CardContent class="space-y-6">

              <div class="grid gap-4 sm:grid-cols-2">
                <!-- Featured -->
                <div class="flex items-center justify-between rounded-lg border p-4">
                  <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                      <Star class="h-4 w-4 text-amber-500" />
                      <Label>Featured</Label>
                    </div>
                    <p class="text-sm text-muted-foreground">
                      Highlight prominently
                    </p>
                  </div>
                  <Switch v-model:checked="form.is_featured" />
                </div>

                <!-- Public -->
                <div class="flex items-center justify-between rounded-lg border p-4">
                  <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                      <Globe class="h-4 w-4 text-green-500" />
                      <Label>Public</Label>
                    </div>
                    <p class="text-sm text-muted-foreground">
                      Allow public access
                    </p>
                  </div>
                  <Switch v-model:checked="form.is_public" />
                </div>
              </div>

              <!-- Sort Order -->
              <div class="space-y-2">
                <Label for="sort_order">Sort Order</Label>
                <Input
                  id="sort_order"
                  v-model.number="form.sort_order"
                  type="number"
                  min="0"
                  placeholder="0 (automatic)"
                />
                <p class="text-sm text-muted-foreground">
                  Lower numbers appear first
                </p>
              </div>
            </CardContent>
          </Card>
        </TabsContent>

        <!-- Files Tab -->
        <TabsContent value="files" class="space-y-6">

          <!-- Poster Image -->
          <Card ref="el => cardsRef[2] = el as HTMLElement">
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Image class="h-5 w-5" />
                Poster Image
              </CardTitle>
              <CardDescription>
                Upload a preview image (optional)
              </CardDescription>
            </CardHeader>
            <CardContent>

              <div v-if="!posterPreview" class="relative">
                <div class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-muted-foreground/25 p-6 text-center hover:bg-muted/50 transition-colors">
                  <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-muted">
                    <Image class="h-6 w-6 text-muted-foreground" />
                  </div>
                  <div class="mt-4">
                    <p class="text-sm font-medium">Upload poster image</p>
                    <p class="text-xs text-muted-foreground">PNG, JPG, GIF up to 5MB</p>
                  </div>
                </div>
                <input
                  type="file"
                  accept="image/*"
                  class="absolute inset-0 cursor-pointer opacity-0"
                  @change="handlePosterUpload"
                />
              </div>

              <div v-else class="poster-preview relative">
                <img
                  :src="posterPreview"
                  alt="Preview"
                  class="mx-auto max-w-full rounded-lg border"
                />
                <Button
                  type="button"
                  variant="destructive"
                  size="sm"
                  class="absolute right-2 top-2"
                  @click="removePosterImage">
                  <X class="h-4 w-4" />
                </Button>
              </div>
            </CardContent>
          </Card>

          <!-- Download File -->
          <Card ref="el => cardsRef[3] = el as HTMLElement">
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <FileText class="h-5 w-5" />
                Download File *
              </CardTitle>
              <CardDescription>
                Upload the file users will download
              </CardDescription>
            </CardHeader>
            <CardContent>

              <div v-if="!downloadFileInfo" class="relative">
                <div class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-muted-foreground/25 p-6 text-center hover:bg-muted/50 transition-colors">
                  <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-muted">
                    <Upload class="h-6 w-6 text-muted-foreground" />
                  </div>
                  <div class="mt-4">
                    <p class="text-sm font-medium">Upload download file</p>
                    <p class="text-xs text-muted-foreground">Any file type up to 50MB</p>
                  </div>
                </div>
                <input
                  type="file"
                  class="absolute inset-0 cursor-pointer opacity-0"
                  @change="handleDownloadFileUpload"
                />
              </div>

              <div v-else class="file-info flex items-center justify-between rounded-lg border p-4">
                <div class="flex items-center gap-3">
                  <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-muted">
                    <FileText class="h-5 w-5" />
                  </div>
                  <div>
                    <p class="font-medium">{{ downloadFileInfo.name }}</p>
                    <p class="text-sm text-muted-foreground">{{ formattedFileSize }}</p>
                  </div>
                </div>
                <Button
                  type="button"
                  variant="ghost"
                  size="sm"
                  @click="removeDownloadFile">
                  <X class="h-4 w-4" />
                </Button>
              </div>

              <p v-if="form.errors.download_file" class="mt-2 text-sm text-destructive flex items-center gap-1">
                <AlertCircle class="h-3 w-3" />
                {{ form.errors.download_file }}
              </p>
            </CardContent>
          </Card>
        </TabsContent>

        <!-- SEO Tab -->
        <TabsContent value="seo" class="space-y-6">
          <Card ref="el => cardsRef[4] = el as HTMLElement">
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Tag class="h-5 w-5" />
                SEO & Meta
              </CardTitle>
              <CardDescription>
                Optimize for search engines
              </CardDescription>
            </CardHeader>
            <CardContent class="space-y-6">

              <!-- Meta Title -->
              <div class="space-y-2">
                <Label for="meta_title">Meta Title</Label>
                <Input
                  id="meta_title"
                  v-model="form.meta_title"
                  placeholder="SEO title for search engines"
                />
                <p class="text-xs text-muted-foreground">
                  {{ form.meta_title?.length || 0 }}/60 characters
                </p>
              </div>

              <!-- Meta Description with Auto-growing Textarea -->
              <div class="space-y-2">
                <Label for="meta_description">Meta Description</Label>
                <Textarea
                  id="meta_description"
                  ref="metaDescriptionTextarea"
                  v-model="form.meta_description"
                  placeholder="Brief description for search results"
                  rows="3"
                  class="resize-none min-h-[75px] transition-all duration-200"
                  @input="handleMetaDescriptionInput"
                />
                <p class="text-xs text-muted-foreground">
                  {{ form.meta_description?.length || 0 }}/160 characters
                </p>
              </div>

              <!-- SEO Preview -->
              <div class="space-y-2">
                <Label>Search Preview</Label>
                <div class="rounded-lg border p-4 space-y-1">
                  <h3 class="text-lg font-medium text-blue-600">
                    {{ metaTitlePreview }}
                  </h3>
                  <p class="text-sm text-green-600">
                    example.com/downloads/{{ form.title ? form.title.toLowerCase().replace(/\s+/g, '-') : 'download-title' }}
                  </p>
                  <p class="text-sm text-muted-foreground">
                    {{ metaDescriptionPreview }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>
        </TabsContent>

        <!-- Preview Tab -->
        <TabsContent value="preview" class="space-y-6">
          <Card ref="el => cardsRef[5] = el as HTMLElement">
            <CardHeader>
              <CardTitle class="flex items-center gap-2">
                <Eye class="h-5 w-5" />
                Preview
              </CardTitle>
              <CardDescription>
                How your download will appear
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div class="mx-auto max-w-sm">

                <!-- Download Card Preview -->
                <div class="overflow-hidden rounded-lg border bg-card">

                  <!-- Image -->
                  <div class="aspect-video bg-muted relative">
                    <img
                      v-if="posterPreview"
                      :src="posterPreview"
                      :alt="form.title"
                      class="h-full w-full object-cover"
                    />
                    <div v-else class="flex h-full w-full items-center justify-center">
                      <FileText class="h-12 w-12 text-muted-foreground" />
                    </div>

                    <!-- Badges -->
                    <div class="absolute right-3 top-3">
                      <Badge v-if="form.is_featured" class="bg-amber-500">
                        <Star class="mr-1 h-3 w-3" />
                        Featured
                      </Badge>
                    </div>

                    <div class="absolute bottom-3 left-3">
                      <Badge v-if="downloadFileInfo" variant="secondary">
                        {{ formattedFileSize }}
                      </Badge>
                    </div>
                  </div>

                  <!-- Content -->
                  <div class="p-4 space-y-3">
                    <div>
                      <h3 class="font-semibold">
                        {{ form.title || 'Download Title' }}
                      </h3>
                      <p v-if="form.brand" class="text-sm text-muted-foreground">{{ form.brand }}</p>
                    </div>

                    <div class="flex items-center justify-between text-xs text-muted-foreground">
                      <span v-if="form.category">{{ form.category }}</span>
                      <div class="flex items-center gap-2">
                        <span class="flex items-center gap-1">
                          <Download class="h-3 w-3" />
                          0
                        </span>
                      </div>
                    </div>

                    <Button variant="outline" size="sm" class="w-full" disabled>
                      <Download class="mr-2 h-3 w-3" />
                      Download
                    </Button>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </TabsContent>
      </Tabs>

      <!-- Form Errors -->
      <Alert v-if="Object.keys(form.errors).length > 0" variant="destructive">
        <AlertCircle class="h-4 w-4" />
        <AlertDescription>
          Please fix the errors above before submitting.
        </AlertDescription>
      </Alert>
    </div>
  </div>
</template>

<style scoped>
/* Smooth transitions */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* File input styling */
input[type="file"] {
  cursor: pointer;
}

/* Tab animations */
[data-state="active"] {
  animation: slideIn 0.2s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(4px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Enhanced focus states */
.focus\:scale-\[1\.02\]:focus {
  transform: scale(1.02);
}

/* Progress bar styling */
.completion-progress [data-state="complete"] {
  background: linear-gradient(90deg, hsl(var(--primary)), hsl(var(--primary)));
}

/* Auto-growing textarea styling */
textarea {
  field-sizing: content;
}

/* Fallback for browsers that don't support field-sizing */
@supports not (field-sizing: content) {
  textarea {
    overflow: hidden;
  }
}
</style>
