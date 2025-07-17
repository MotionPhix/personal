<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { Project } from "@/types";
import { Head, Link } from "@inertiajs/vue3";
import {
  IconArrowLeft,
  IconExternalLink,
  IconBrandGithub,
  IconBrandFigma,
  IconBrandBehance,
  IconBrandDribbble,
  IconCalendar,
  IconClock,
  IconUser,
  IconTag,
  IconCode,
  IconStar
} from "@tabler/icons-vue";
import { vFullscreenImg } from 'maz-ui';
import { computed, onMounted, ref } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const props = defineProps<{
  project: {
    data: Project;
    meta?: any;
  };
  relatedProjects?: {
    data: Project[];
    meta?: any;
  };
}>();

defineOptions({
  layout: AppLayout
});

// Refs for animations
const heroRef = ref(null);
const contentRef = ref(null);
const galleryRef = ref(null);

// Computed properties
const projectData = computed(() => props.project.data);
const relatedProjectsData = computed(() => props.relatedProjects?.data || []);

const heroImage = computed(() => {
  if (projectData.value.poster_url) {
    return projectData.value.poster_url;
  }

  if (projectData.value.gallery_images && projectData.value.gallery_images.length > 0) {
    return projectData.value.gallery_images[0].large_url || projectData.value.gallery_images[0].url;
  }

  if (projectData.value.media && projectData.value.media.length > 0) {
    return projectData.value.media[0].original_url;
  }

  return '/assets/placeholder-project.jpg';
});

const galleryImages = computed(() => {
  if (projectData.value.gallery_images && projectData.value.gallery_images.length > 0) {
    return projectData.value.gallery_images;
  }

  // Fallback to legacy media format
  if (projectData.value.media && projectData.value.media.length > 0) {
    return projectData.value.media.map(media => ({
      id: media.id,
      name: media.name,
      url: media.original_url,
      thumb_url: media.original_url,
      medium_url: media.original_url,
      large_url: media.original_url
    }));
  }

  return [];
});

const customerName = computed(() => {
  const customer = projectData.value.customer;

  if (customer?.display_name) {
    return customer.display_name;
  }

  if (customer?.company_name) {
    return customer.company_name;
  }

  if (customer?.full_name) {
    return customer.full_name;
  }

  if (customer?.first_name || customer?.last_name) {
    return `${customer.first_name || ''} ${customer.last_name || ''}`.trim();
  }

  return 'Client';
});

const projectYear = computed(() => {
  if (projectData.value.end_date) {
    return new Date(projectData.value.end_date).getFullYear();
  }

  if (projectData.value.created_at) {
    return new Date(projectData.value.created_at).getFullYear();
  }

  return new Date().getFullYear();
});

const externalLinks = computed(() => {
  const links = [];
  const project = projectData.value;

  if (project.live_url) {
    links.push({
      name: 'Live Site',
      url: project.live_url,
      icon: IconExternalLink,
      color: 'text-blue-600 hover:text-blue-800'
    });
  }

  if (project.github_url) {
    links.push({
      name: 'GitHub',
      url: project.github_url,
      icon: IconBrandGithub,
      color: 'text-gray-800 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white'
    });
  }

  if (project.figma_url) {
    links.push({
      name: 'Figma',
      url: project.figma_url,
      icon: IconBrandFigma,
      color: 'text-purple-600 hover:text-purple-800'
    });
  }

  if (project.behance_url) {
    links.push({
      name: 'Behance',
      url: project.behance_url,
      icon: IconBrandBehance,
      color: 'text-blue-500 hover:text-blue-700'
    });
  }

  if (project.dribbble_url) {
    links.push({
      name: 'Dribbble',
      url: project.dribbble_url,
      icon: IconBrandDribbble,
      color: 'text-pink-500 hover:text-pink-700'
    });
  }

  return links;
});

// Animations
onMounted(() => {
  const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

  tl.from(heroRef.value, { opacity: 0, scale: 1.1, duration: 1.2 })
    .from(contentRef.value, { opacity: 0, y: 50, duration: 1 }, '-=0.6');

  // Gallery animation
  if (galleryRef.value) {
    gsap.fromTo('.gallery-item',
      { opacity: 0, y: 30 },
      {
        opacity: 1,
        y: 0,
        duration: 0.6,
        stagger: 0.1,
        scrollTrigger: {
          trigger: galleryRef.value,
          start: 'top bottom-=100',
          toggleActions: 'play none none reverse'
        }
      }
    );
  }
});
</script>

<template>
  <Head :title="`${projectData.name} - Project Details`">
    <meta name="description" :content="projectData.short_description || projectData.description?.substring(0, 160)" />
    <meta property="og:title" :content="projectData.name" />
    <meta property="og:description" :content="projectData.short_description || projectData.description?.substring(0, 160)" />
    <meta property="og:image" :content="heroImage" />
    <meta property="og:type" content="article" />
  </Head>

  <article class="w-full max-w-4xl px-4 mx-auto mt-8 mb-16 sm:px-8 sm:mt-16">

    <!-- Back Navigation -->
    <div class="mb-8">
      <Link
        :href="route('projects.index')"
        class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 transition-colors hover:text-gray-900 dark:text-neutral-400 dark:hover:text-neutral-200 group"
      >
        <IconArrowLeft size="16" class="transition-transform group-hover:-translate-x-1" />
        <span>All projects</span>
      </Link>
    </div>

    <!-- Hero Section -->
    <div ref="heroRef" class="mb-12">
      <div class="relative overflow-hidden rounded-2xl bg-gray-100 dark:bg-neutral-800 aspect-video">
        <img
          :src="heroImage"
          :alt="`${projectData.name} hero image`"
          class="object-cover w-full h-full"
          loading="eager" />

        <!-- Featured badge -->
        <div v-if="projectData.is_featured"
             class="absolute top-4 right-4 flex items-center gap-1 px-3 py-1 bg-yellow-500/90 text-white text-sm font-medium rounded-full backdrop-blur-sm">
          <IconStar size="14" />
          <span>Featured</span>
        </div>
      </div>
    </div>

    <!-- Content Section -->
    <div ref="contentRef" class="grid gap-12 lg:grid-cols-3">

      <!-- Main Content -->
      <div class="lg:col-span-2">

        <!-- Project Header -->
        <header class="mb-8">
          <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl dark:text-white mb-4">
            {{ projectData.name }}
          </h1>

          <p v-if="projectData.short_description"
             class="text-xl text-gray-600 dark:text-neutral-300 leading-relaxed">
            {{ projectData.short_description }}
          </p>
        </header>

        <!-- Project Description -->
        <section v-if="projectData.description" class="mb-12">
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">About This Project</h2>
          <div class="prose prose-lg dark:prose-invert max-w-none">
            <div v-html="projectData.description"></div>
          </div>
        </section>

        <!-- Technologies -->
        <section v-if="projectData.technologies && projectData.technologies.length > 0" class="mb-12">
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Technologies Used</h2>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="tech in projectData.technologies"
              :key="tech"
              class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full dark:bg-blue-900/30 dark:text-blue-300">
              <IconCode size="14" />
              {{ tech }}
            </span>
          </div>
        </section>

        <!-- Features -->
        <section v-if="projectData.features && projectData.features.length > 0" class="mb-12">
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Key Features</h2>
          <ul class="space-y-2">
            <li v-for="feature in projectData.features" :key="feature"
                class="flex items-start gap-2 text-gray-700 dark:text-neutral-300">
              <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></span>
              <span>{{ feature }}</span>
            </li>
          </ul>
        </section>

        <!-- Challenges & Solutions -->
        <div v-if="projectData.challenges || projectData.solutions" class="grid gap-8 mb-12 md:grid-cols-2">
          <section v-if="projectData.challenges">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Challenges</h2>
            <p class="text-gray-700 dark:text-neutral-300">{{ projectData.challenges }}</p>
          </section>

          <section v-if="projectData.solutions">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Solutions</h2>
            <p class="text-gray-700 dark:text-neutral-300">{{ projectData.solutions }}</p>
          </section>
        </div>

        <!-- Results -->
        <section v-if="projectData.results" class="mb-12">
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Results</h2>
          <p class="text-gray-700 dark:text-neutral-300">{{ projectData.results }}</p>
        </section>

        <!-- Client Feedback -->
        <section v-if="projectData.client_feedback" class="mb-12">
          <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Client Feedback</h2>
          <blockquote class="border-l-4 border-blue-500 pl-6 italic text-gray-700 dark:text-neutral-300">
            "{{ projectData.client_feedback }}"
          </blockquote>
        </section>

      </div>

      <!-- Sidebar -->
      <div class="lg:col-span-1">
        <div class="sticky top-8 space-y-8">

          <!-- Project Info -->
          <div class="bg-gray-50 dark:bg-neutral-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Project Details</h3>

            <dl class="space-y-4">
              <!-- Client -->
              <div>
                <dt class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-neutral-400 mb-1">
                  <IconUser size="16" />
                  Client
                </dt>
                <dd class="text-gray-900 dark:text-white">{{ customerName }}</dd>
              </div>

              <!-- Year -->
              <div>
                <dt class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-neutral-400 mb-1">
                  <IconCalendar size="16" />
                  Year
                </dt>
                <dd class="text-gray-900 dark:text-white">{{ projectYear }}</dd>
              </div>

              <!-- Category -->
              <div v-if="projectData.category">
                <dt class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-neutral-400 mb-1">
                  <IconTag size="16" />
                  Category
                </dt>
                <dd class="text-gray-900 dark:text-white">{{ projectData.category }}</dd>
              </div>

              <!-- Production Type -->
              <div v-if="projectData.production_type">
                <dt class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-neutral-400 mb-1">
                  <IconCode size="16" />
                  Type
                </dt>
                <dd class="text-gray-900 dark:text-white">{{ projectData.production_type }}</dd>
              </div>

              <!-- Duration -->
              <div v-if="projectData.duration">
                <dt class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-neutral-400 mb-1">
                  <IconClock size="16" />
                  Duration
                </dt>
                <dd class="text-gray-900 dark:text-white">{{ projectData.duration }} days</dd>
              </div>

              <!-- Budget -->
              <div v-if="projectData.budget">
                <dt class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-neutral-400 mb-1">
                  <IconTag size="16" />
                  Budget
                </dt>
                <dd class="text-gray-900 dark:text-white">${{ Number(projectData.budget).toLocaleString() }}</dd>
              </div>
            </dl>
          </div>

          <!-- External Links -->
          <div v-if="externalLinks.length > 0" class="bg-gray-50 dark:bg-neutral-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">View Project</h3>
            <div class="space-y-3">
              <a
                v-for="link in externalLinks"
                :key="link.name"
                :href="link.url"
                target="_blank"
                rel="noopener noreferrer"
                :class="link.color"
                class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-white dark:hover:bg-neutral-700 transition-colors">
                <component :is="link.icon" size="20" />
                <span class="font-medium">{{ link.name }}</span>
              </a>
            </div>
          </div>

          <!-- Project Stats -->
          <div class="bg-gray-50 dark:bg-neutral-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Project Stats</h3>

            <dl class="space-y-3">
              <!-- Status -->
              <div class="flex items-center justify-between">
                <dt class="text-sm text-gray-500 dark:text-neutral-400">Status</dt>
                <dd>
                  <span :class="`bg-${projectData.status_color}-100 text-${projectData.status_color}-800 dark:bg-${projectData.status_color}-900/30 dark:text-${projectData.status_color}-300`"
                        class="px-2 py-1 text-xs font-medium rounded-full capitalize">
                    {{ projectData.status?.replace('_', ' ') }}
                  </span>
                </dd>
              </div>

              <!-- Hours -->
              <div v-if="projectData.estimated_hours || projectData.actual_hours" class="flex items-center justify-between">
                <dt class="text-sm text-gray-500 dark:text-neutral-400">Hours</dt>
                <dd class="text-sm text-gray-900 dark:text-white">
                  <span v-if="projectData.actual_hours">{{ projectData.actual_hours }}h</span>
                  <span v-else-if="projectData.estimated_hours">~{{ projectData.estimated_hours }}h</span>
                </dd>
              </div>

              <!-- Progress -->
              <div v-if="projectData.progress !== undefined" class="flex items-center justify-between">
                <dt class="text-sm text-gray-500 dark:text-neutral-400">Progress</dt>
                <dd class="text-sm text-gray-900 dark:text-white">{{ projectData.progress }}%</dd>
              </div>
            </dl>
          </div>

        </div>
      </div>

    </div>

    <!-- Project Gallery -->
    <section v-if="galleryImages.length > 1" ref="galleryRef" class="mt-16">
      <h2 class="text-3xl font-semibold text-gray-900 dark:text-white mb-8">Project Gallery</h2>

      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="(image, index) in galleryImages.slice(1)"
          :key="image.id || index"
          class="gallery-item group cursor-pointer">
          <div class="relative overflow-hidden rounded-lg bg-gray-100 dark:bg-neutral-800 aspect-square">
            <img
              v-fullscreen-img
              :src="image.medium_url || image.url"
              :alt="`${projectData.name} gallery image ${index + 2}`"
              class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105"
              loading="lazy" />
          </div>
        </div>
      </div>
    </section>

    <!-- Related Projects -->
    <section v-if="relatedProjectsData.length > 0" class="mt-16">
      <h2 class="text-3xl font-semibold text-gray-900 dark:text-white mb-8">Related Projects</h2>

      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <Link
          v-for="relatedProject in relatedProjectsData"
          :key="relatedProject.uuid"
          :href="route('projects.show', relatedProject.uuid)"
          class="group">
          <div class="relative overflow-hidden rounded-lg bg-gray-100 dark:bg-neutral-800 aspect-square mb-3">
            <img
              :src="relatedProject.poster_url || '/assets/placeholder-project.jpg'"
              :alt="relatedProject.name"
              class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105"
              loading="lazy" />
          </div>
          <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
            {{ relatedProject.name }}
          </h3>
          <p v-if="relatedProject.production_type" class="text-sm text-gray-600 dark:text-neutral-400">
            {{ relatedProject.production_type }}
          </p>
        </Link>
      </div>
    </section>

  </article>
</template>

<style scoped>
.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
  color: inherit;
}

.prose p {
  margin-bottom: 1rem;
}

.prose ul, .prose ol {
  margin-bottom: 1rem;
}

.prose li {
  margin-bottom: 0.5rem;
}
</style>
