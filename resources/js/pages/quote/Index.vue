<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ArrowRight, Upload, FileText, Palette, Code, Camera, Megaphone } from 'lucide-vue-next';

gsap.registerPlugin(ScrollTrigger);

const quoteForm = useForm({
  name: '',
  email: '',
  phone: '',
  company: '',
  project_type: '',
  budget_range: '',
  timeline: '',
  description: '',
  goals: '',
  target_audience: '',
  additional_info: '',
  files: [] as File[]
});

// Project types with icons and descriptions
const projectTypes = [
  {
    value: 'web_design',
    label: 'Web Design',
    icon: Code,
    description: 'Custom website design and development'
  },
  {
    value: 'branding',
    label: 'Branding & Identity',
    icon: Palette,
    description: 'Logo design, brand guidelines, and visual identity'
  },
  {
    value: 'photography',
    label: 'Photography',
    icon: Camera,
    description: 'Product, portrait, and commercial photography'
  },
  {
    value: 'marketing',
    label: 'Digital Marketing',
    icon: Megaphone,
    description: 'Social media, advertising, and marketing materials'
  },
  {
    value: 'print_design',
    label: 'Print Design',
    icon: FileText,
    description: 'Brochures, business cards, and print materials'
  }
];

const budgetRanges = [
  { value: 'under_1000', label: 'Under $1,000' },
  { value: '1000_5000', label: '$1,000 - $5,000' },
  { value: '5000_10000', label: '$5,000 - $10,000' },
  { value: '10000_25000', label: '$10,000 - $25,000' },
  { value: 'over_25000', label: 'Over $25,000' },
  { value: 'discuss', label: 'Let\'s discuss' }
];

const timelines = [
  { value: 'asap', label: 'ASAP' },
  { value: '1_2_weeks', label: '1-2 weeks' },
  { value: '1_month', label: '1 month' },
  { value: '2_3_months', label: '2-3 months' },
  { value: '3_6_months', label: '3-6 months' },
  { value: 'flexible', label: 'Flexible' }
];

const fileInputRef = ref<HTMLInputElement>();
const mainRef = ref<HTMLElement>();

const onSubmit = () => {
  const formData = new FormData();

  // Add form fields
  Object.keys(quoteForm.data()).forEach(key => {
    if (key !== 'files') {
      formData.append(key, quoteForm[key]);
    }
  });

  // Add files
  quoteForm.files.forEach((file, index) => {
    formData.append(`files[${index}]`, file);
  });

  quoteForm.post(route('quote.store'), {
    data: formData,
    preserveScroll: true,
    onSuccess: () => {
      quoteForm.reset();
      if (fileInputRef.value) {
        fileInputRef.value.value = '';
      }
    }
  });
};

const handleFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files) {
    quoteForm.files = Array.from(target.files);
  }
};

const removeFile = (index: number) => {
  quoteForm.files.splice(index, 1);
  if (fileInputRef.value) {
    fileInputRef.value.value = '';
  }
};

const formatFileSize = (bytes: number) => {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

onMounted(() => {
  const main = mainRef.value;
  if (!main) return;

  const title = main.querySelector('.title-section');
  const cards = main.querySelectorAll('.project-type-card');
  const formFields = main.querySelectorAll('.form-field');

  // Animate title
  if (title) {
    gsap.from(title.children, {
      opacity: 0,
      y: 30,
      duration: 0.8,
      stagger: 0.2,
      ease: "power2.out"
    });
  }

  // Animate project type cards
  gsap.from(cards, {
    opacity: 0,
    y: 20,
    duration: 0.6,
    stagger: 0.1,
    delay: 0.3,
    scrollTrigger: {
      trigger: cards[0],
      start: 'top bottom-=100',
      toggleActions: 'play none none reverse'
    }
  });

  // Animate form fields
  gsap.from(formFields, {
    opacity: 0,
    y: 20,
    duration: 0.6,
    stagger: 0.05,
    delay: 0.5,
    scrollTrigger: {
      trigger: formFields[0],
      start: 'top bottom-=100',
      toggleActions: 'play none none reverse'
    }
  });
});

defineOptions({
  layout: AppLayout
});
</script>

<template>
  <Head title="Get a Quote" />

  <article
    ref="mainRef"
    class="w-full max-w-4xl px-4 pt-10 mx-auto md:pt-16 sm:px-6 lg:px-8 pb-20"
  >
    <!-- Title -->
    <div class="w-full mb-12 lg:mb-16 title-section">
      <h1 class="text-3xl font-bold tracking-tight text-foreground md:text-5xl md:leading-tight">
        Get a Quote
      </h1>
      <p class="mt-4 text-lg text-muted-foreground max-w-2xl">
        Tell us about your project and we'll provide you with a detailed quote.
        Upload reference files to help us understand your vision better.
      </p>
    </div>

    <!-- Project Types -->
    <div class="mb-12">
      <h2 class="text-xl font-semibold mb-6">What type of project do you have in mind?</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <Card
          v-for="type in projectTypes"
          :key="type.value"
          class="project-type-card cursor-pointer transition-all hover:shadow-md hover:scale-105"
          :class="quoteForm.project_type === type.value ? 'ring-2 ring-primary' : ''"
          @click="quoteForm.project_type = type.value"
        >
          <CardHeader class="pb-3">
            <div class="flex items-center space-x-3">
              <div class="p-2 rounded-lg bg-primary/10">
                <component :is="type.icon" class="h-5 w-5 text-primary" />
              </div>
              <CardTitle class="text-base">{{ type.label }}</CardTitle>
            </div>
          </CardHeader>
          <CardContent>
            <CardDescription>{{ type.description }}</CardDescription>
          </CardContent>
        </Card>
      </div>
      <InputError :message="quoteForm.errors.project_type" class="mt-2" />
    </div>

    <!-- Quote Form -->
    <form @submit.prevent="onSubmit" class="space-y-8">
      <!-- Personal Information -->
      <div class="space-y-6">
        <h2 class="text-xl font-semibold">Contact Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="form-field space-y-2">
            <Label for="name">Full Name *</Label>
            <Input
              id="name"
              v-model="quoteForm.name"
              placeholder="Enter your full name"
              required
            />
            <InputError :message="quoteForm.errors.name" />
          </div>

          <div class="form-field space-y-2">
            <Label for="email">Email Address *</Label>
            <Input
              id="email"
              v-model="quoteForm.email"
              type="email"
              placeholder="Enter your email address"
              required
            />
            <InputError :message="quoteForm.errors.email" />
          </div>

          <div class="form-field space-y-2">
            <Label for="phone">Phone Number</Label>
            <Input
              id="phone"
              v-model="quoteForm.phone"
              type="tel"
              placeholder="Enter your phone number"
            />
            <InputError :message="quoteForm.errors.phone" />
          </div>

          <div class="form-field space-y-2">
            <Label for="company">Company/Organization</Label>
            <Input
              id="company"
              v-model="quoteForm.company"
              placeholder="Enter your company name"
            />
            <InputError :message="quoteForm.errors.company" />
          </div>
        </div>
      </div>

      <!-- Project Details -->
      <div class="space-y-6">
        <h2 class="text-xl font-semibold">Project Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="form-field space-y-2">
            <Label for="budget_range">Budget Range *</Label>
            <Select v-model="quoteForm.budget_range" required>
              <SelectTrigger>
                <SelectValue placeholder="Select your budget range" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="budget in budgetRanges"
                  :key="budget.value"
                  :value="budget.value"
                >
                  {{ budget.label }}
                </SelectItem>
              </SelectContent>
            </Select>
            <InputError :message="quoteForm.errors.budget_range" />
          </div>

          <div class="form-field space-y-2">
            <Label for="timeline">Timeline *</Label>
            <Select v-model="quoteForm.timeline" required>
              <SelectTrigger>
                <SelectValue placeholder="When do you need this completed?" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="timeline in timelines"
                  :key="timeline.value"
                  :value="timeline.value"
                >
                  {{ timeline.label }}
                </SelectItem>
              </SelectContent>
            </Select>
            <InputError :message="quoteForm.errors.timeline" />
          </div>
        </div>

        <div class="form-field space-y-2">
          <Label for="description">Project Description *</Label>
          <Textarea
            id="description"
            v-model="quoteForm.description"
            placeholder="Describe your project in detail. What do you need? What are your requirements?"
            rows="4"
            required
          />
          <InputError :message="quoteForm.errors.description" />
        </div>

        <div class="form-field space-y-2">
          <Label for="goals">Project Goals</Label>
          <Textarea
            id="goals"
            v-model="quoteForm.goals"
            placeholder="What are you hoping to achieve with this project? What success looks like?"
            rows="3"
          />
          <InputError :message="quoteForm.errors.goals" />
        </div>

        <div class="form-field space-y-2">
          <Label for="target_audience">Target Audience</Label>
          <Input
            id="target_audience"
            v-model="quoteForm.target_audience"
            placeholder="Who is your target audience? (e.g., young professionals, families, businesses)"
          />
          <InputError :message="quoteForm.errors.target_audience" />
        </div>
      </div>

      <!-- File Upload -->
      <div class="space-y-4">
        <h2 class="text-xl font-semibold">Reference Files</h2>
        <p class="text-sm text-muted-foreground">
          Upload any reference materials, inspiration images, existing brand assets, or documents that will help us understand your project better.
        </p>

        <div class="form-field">
          <Label for="files">Upload Files (Optional)</Label>
          <div class="mt-2">
            <input
              ref="fileInputRef"
              type="file"
              multiple
              accept="image/*,.pdf,.doc,.docx,.txt"
              @change="handleFileUpload"
              class="hidden"
              id="file-upload"
            />
            <Button
              type="button"
              variant="outline"
              @click="fileInputRef?.click()"
              class="w-full h-24 border-dashed"
            >
              <div class="flex flex-col items-center space-y-2">
                <Upload class="h-6 w-6 text-muted-foreground" />
                <span class="text-sm">Click to upload files</span>
                <span class="text-xs text-muted-foreground">
                  Images, PDFs, Documents (Max 10MB each)
                </span>
              </div>
            </Button>
          </div>

          <!-- File List -->
          <div v-if="quoteForm.files.length > 0" class="mt-4 space-y-2">
            <div
              v-for="(file, index) in quoteForm.files"
              :key="index"
              class="flex items-center justify-between p-3 bg-muted rounded-lg"
            >
              <div class="flex items-center space-x-3">
                <FileText class="h-4 w-4 text-muted-foreground" />
                <div>
                  <p class="text-sm font-medium">{{ file.name }}</p>
                  <p class="text-xs text-muted-foreground">{{ formatFileSize(file.size) }}</p>
                </div>
              </div>
              <Button
                type="button"
                variant="ghost"
                size="sm"
                @click="removeFile(index)"
              >
                Remove
              </Button>
            </div>
          </div>

          <InputError :message="quoteForm.errors.files" />
        </div>
      </div>

      <!-- Additional Information -->
      <div class="form-field space-y-2">
        <Label for="additional_info">Additional Information</Label>
        <Textarea
          id="additional_info"
          v-model="quoteForm.additional_info"
          placeholder="Any other details, specific requirements, or questions you'd like to share?"
          rows="3"
        />
        <InputError :message="quoteForm.errors.additional_info" />
      </div>

      <!-- Submit Button -->
      <div class="pt-6">
        <Button
          type="submit"
          class="w-full md:w-auto"
          size="lg"
          :disabled="quoteForm.processing"
        >
          <span v-if="quoteForm.processing">Sending Quote Request...</span>
          <span v-else class="flex items-center gap-2">
            Send Quote Request
            <ArrowRight class="h-4 w-4" />
          </span>
        </Button>

        <p class="mt-4 text-sm text-muted-foreground">
          We'll review your request and get back to you within 24 hours with a detailed quote and next steps.
        </p>
      </div>
    </form>
  </article>
</template>

<style scoped>
/* Custom animations */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.project-type-card {
  animation: fadeInUp 0.6s ease-out forwards;
}
</style>
