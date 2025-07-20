<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import { X, Upload, Image as ImageIcon, AlertCircle, Eye } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';

interface UploadedFile {
  id: string;
  file?: File;
  url: string;
  name: string;
  size: number;
  type: string;
  isExisting: boolean;
  preview?: string;
}

interface Props {
  modelValue?: File[] | File | null;
  existingFiles?: string[];
  multiple?: boolean;
  maxFiles?: number;
  maxFileSize?: number; // in MB
  acceptedTypes?: string[];
  placeholder?: string;
  disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  multiple: false,
  maxFiles: 10,
  maxFileSize: 5,
  acceptedTypes: () => ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'],
  placeholder: 'Drop images here or click to browse',
  disabled: false,
});

const emit = defineEmits<{
  'update:modelValue': [value: File[] | File | null];
  'filesChanged': [files: UploadedFile[]];
  'error': [message: string];
}>();

// Refs
const fileInput = ref<HTMLInputElement>();
const dragOver = ref(false);
const uploadedFiles = ref<UploadedFile[]>([]);

// Computed
const acceptAttribute = computed(() => props.acceptedTypes.join(','));
const maxFileSizeBytes = computed(() => props.maxFileSize * 1024 * 1024);

const canAddMore = computed(() => {
  if (!props.multiple) return uploadedFiles.value.length === 0;
  return uploadedFiles.value.length < props.maxFiles;
});

const newFiles = computed(() =>
  uploadedFiles.value.filter(f => !f.isExisting && f.file).map(f => f.file!)
);

const existingFileUrls = computed(() =>
  uploadedFiles.value.filter(f => f.isExisting).map(f => f.url)
);

// Watchers
watch(() => props.existingFiles, (newExisting) => {
  if (newExisting) {
    loadExistingFiles(newExisting);
  }
}, { deep: true });

watch(newFiles, (files) => {
  if (props.multiple) {
    emit('update:modelValue', files);
  } else {
    emit('update:modelValue', files[0] || null);
  }
  emit('filesChanged', uploadedFiles.value);
}, { deep: true });

// Methods
const generateId = () => Math.random().toString(36).substr(2, 9);

const formatFileSize = (bytes: number): string => {
  if (bytes === 0) return '0 B';
  const k = 1024;
  const sizes = ['B', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
};

const validateFile = (file: File): string | null => {
  if (!props.acceptedTypes.includes(file.type)) {
    return `File type ${file.type} is not supported. Accepted types: ${props.acceptedTypes.join(', ')}`;
  }

  if (file.size > maxFileSizeBytes.value) {
    return `File size (${formatFileSize(file.size)}) exceeds maximum allowed size (${props.maxFileSize}MB)`;
  }

  return null;
};

const createFilePreview = (file: File): Promise<string> => {
  return new Promise((resolve) => {
    const reader = new FileReader();
    reader.onload = (e) => resolve(e.target?.result as string);
    reader.readAsDataURL(file);
  });
};

const loadExistingFiles = async (urls: string[]) => {
  const existingUploaded: UploadedFile[] = [];

  for (const url of urls) {
    if (url && url.trim()) {
      try {
        // Extract filename from URL
        const urlParts = url.split('/');
        const filename = urlParts[urlParts.length - 1] || 'image';

        existingUploaded.push({
          id: generateId(),
          url: url,
          name: filename,
          size: 0, // We don't know the size of existing files
          type: 'image/*',
          isExisting: true,
          preview: url
        });
      } catch (error) {
        console.warn('Failed to load existing file:', url, error);
      }
    }
  }

  // Only add existing files that aren't already in the list
  const existingIds = uploadedFiles.value.filter(f => f.isExisting).map(f => f.url);
  const newExisting = existingUploaded.filter(f => !existingIds.includes(f.url));

  uploadedFiles.value = [
    ...uploadedFiles.value.filter(f => !f.isExisting),
    ...newExisting
  ];
};

const addFiles = async (files: FileList | File[]) => {
  const fileArray = Array.from(files);

  for (const file of fileArray) {
    // Check if we can add more files
    if (!canAddMore.value) {
      emit('error', `Maximum ${props.maxFiles} files allowed`);
      break;
    }

    // Validate file
    const error = validateFile(file);
    if (error) {
      emit('error', error);
      continue;
    }

    // Check for duplicates
    const isDuplicate = uploadedFiles.value.some(f =>
      f.file && f.file.name === file.name && f.file.size === file.size
    );

    if (isDuplicate) {
      emit('error', `File "${file.name}" is already selected`);
      continue;
    }

    try {
      const preview = await createFilePreview(file);

      const uploadedFile: UploadedFile = {
        id: generateId(),
        file,
        url: URL.createObjectURL(file),
        name: file.name,
        size: file.size,
        type: file.type,
        isExisting: false,
        preview
      };

      if (props.multiple) {
        uploadedFiles.value.push(uploadedFile);
      } else {
        // For single file, replace existing
        uploadedFiles.value.forEach(f => {
          if (f.url && !f.isExisting) {
            URL.revokeObjectURL(f.url);
          }
        });
        uploadedFiles.value = [uploadedFile];
      }
    } catch (error) {
      emit('error', `Failed to process file "${file.name}"`);
    }
  }
};

const removeFile = (id: string) => {
  const fileIndex = uploadedFiles.value.findIndex(f => f.id === id);
  if (fileIndex > -1) {
    const file = uploadedFiles.value[fileIndex];

    // Revoke object URL to prevent memory leaks
    if (file.url && !file.isExisting) {
      URL.revokeObjectURL(file.url);
    }

    uploadedFiles.value.splice(fileIndex, 1);
  }
};

const openFileDialog = () => {
  if (!props.disabled) {
    fileInput.value?.click();
  }
};

const handleFileSelect = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    addFiles(target.files);
    target.value = ''; // Reset input
  }
};

const handleDrop = (event: DragEvent) => {
  event.preventDefault();
  dragOver.value = false;

  if (props.disabled) return;

  const files = event.dataTransfer?.files;
  if (files && files.length > 0) {
    addFiles(files);
  }
};

const handleDragOver = (event: DragEvent) => {
  event.preventDefault();
  if (!props.disabled) {
    dragOver.value = true;
  }
};

const handleDragLeave = (event: DragEvent) => {
  event.preventDefault();
  dragOver.value = false;
};

// Cleanup on unmount
const cleanup = () => {
  uploadedFiles.value.forEach(file => {
    if (file.url && !file.isExisting) {
      URL.revokeObjectURL(file.url);
    }
  });
};

// Expose methods for parent component
defineExpose({
  addFiles,
  removeFile,
  cleanup,
  getNewFiles: () => newFiles.value,
  getExistingFiles: () => existingFileUrls.value,
  getAllFiles: () => uploadedFiles.value
});

// Cleanup on unmount
onMounted(() => {
  return cleanup;
});
</script>

<template>
  <div class="w-full">
    <!-- Hidden file input -->
    <input
      ref="fileInput"
      type="file"
      :accept="acceptAttribute"
      :multiple="multiple"
      class="hidden"
      @change="handleFileSelect"
    />

    <!-- Upload Area -->
    <div
      v-if="canAddMore"
      :class="[
        'relative border-2 border-dashed rounded-lg p-6 transition-all duration-200 cursor-pointer',
        dragOver
          ? 'border-primary bg-primary/5 scale-[1.02]'
          : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500',
        disabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-gray-800/50'
      ]"
      @click="openFileDialog"
      @drop="handleDrop"
      @dragover="handleDragOver"
      @dragleave="handleDragLeave">
      <div class="flex flex-col items-center justify-center text-center">
        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
          <Upload class="w-6 h-6 text-gray-600 dark:text-gray-400" />
        </div>

        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
          {{ placeholder }}
        </h3>

        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
          {{ multiple ? `Up to ${maxFiles} files` : 'Single file' }} •
          Max {{ maxFileSize }}MB each •
          {{ acceptedTypes.map(type => type.split('/')[1].toUpperCase()).join(', ') }}
        </p>

        <Button variant="outline" size="sm" type="button">
          <ImageIcon class="w-4 h-4 mr-2" />
          Choose {{ multiple ? 'Files' : 'File' }}
        </Button>
      </div>

      <!-- Drag overlay -->
      <div
        v-if="dragOver"
        class="absolute inset-0 bg-primary/10 border-2 border-primary rounded-lg flex items-center justify-center">
        <div class="text-primary font-medium">
          Drop {{ multiple ? 'files' : 'file' }} here
        </div>
      </div>
    </div>

    <!-- File Preview Grid -->
    <div
      v-if="uploadedFiles.length > 0"
      :class="[
        'grid gap-4 mt-4',
        multiple ? 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4' : 'grid-cols-1 max-w-full'
      ]">
      <div
        v-for="file in uploadedFiles"
        :key="file.id"
        class="relative group bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-md transition-all duration-200"
      >
        <!-- Image Preview -->
        <div
          class="relative overflow-hidden bg-gray-100 dark:bg-gray-700"
          :class="{
            'aspect-square': multiple,
            'aspect-auto': !multiple
          }">
          <img
            v-if="file.preview"
            :src="file.preview"
            :alt="file.name"
            class="w-full object-cover"
            loading="lazy"
          />

          <div
            v-else
            class="w-full h-full flex items-center justify-center"
          >
            <ImageIcon class="w-8 h-8 text-gray-400" />
          </div>

          <!-- File Info Overlay -->
          <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-200">
            <div class="absolute top-2 left-2">
              <Badge
                :variant="file.isExisting ? 'secondary' : 'default'"
                class="text-xs font-mono"
              >
                {{ file.isExisting ? 'Existing' : formatFileSize(file.size) }}
              </Badge>
            </div>

            <!-- Actions -->
            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
              <div class="flex gap-1">
                <Button
                  v-if="file.preview"
                  variant="secondary"
                  size="icon"
                  class="w-6 h-6"
                  @click.stop="() => window.open(file.preview, '_blank')"
                >
                  <Eye class="w-3 h-3" />
                </Button>

                <Button
                  variant="destructive"
                  size="icon"
                  class="w-6 h-6"
                  @click.stop="removeFile(file.id)"
                >
                  <X class="w-3 h-3" />
                </Button>
              </div>
            </div>
          </div>
        </div>

        <!-- File Details -->
        <div class="p-3">
          <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate" :title="file.name">
            {{ file.name }}
          </h4>

          <div class="flex items-center justify-between mt-1">
            <span class="text-xs text-gray-500 dark:text-gray-400">
              {{ file.type.split('/')[1].toUpperCase() }}
            </span>

            <Badge
              v-if="!file.isExisting"
              variant="outline"
              class="text-xs"
            >
              New
            </Badge>
          </div>
        </div>
      </div>
    </div>

    <!-- Add More Button (for multiple files) -->
    <div
      v-if="multiple && uploadedFiles.length > 0 && canAddMore"
      class="mt-4">
      <Button
        variant="outline"
        size="sm"
        @click="openFileDialog"
        :disabled="disabled">
        <Upload class="w-4 h-4 mr-2" />
        Add More Files
      </Button>
    </div>

    <!-- File Count Info -->
    <div
      v-if="multiple && uploadedFiles.length > 0"
      class="mt-2 text-sm text-gray-500 dark:text-gray-400">
      {{ uploadedFiles.length }} of {{ maxFiles }} files selected
      <span v-if="newFiles.length > 0">
        ({{ newFiles.length }} new)
      </span>
    </div>
  </div>
</template>

<style scoped>
/* Custom scrollbar for file grid */
.grid::-webkit-scrollbar {
  width: 4px;
}

.grid::-webkit-scrollbar-track {
  background: transparent;
}

.grid::-webkit-scrollbar-thumb {
  background: hsl(var(--border));
  border-radius: 2px;
}

.grid::-webkit-scrollbar-thumb:hover {
  background: hsl(var(--border) / 0.8);
}
</style>
