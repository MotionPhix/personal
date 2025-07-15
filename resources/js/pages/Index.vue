<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { IconBrandBehance, IconBrandLinkedin, IconBrandX, IconMapPin } from '@tabler/icons-vue';
import Projects from '@/components/front/Projects.vue';
import Skills from '@/components/front/Skills.vue';
import Expertise from '@/components/front/Expertise.vue';
import Subscription from '@/components/front/Subscription.vue';
import { Project, User } from '@/types';
import { onMounted, ref, computed } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import IconEmail from '@/components/icon/IconEmail.vue';

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

const props = defineProps<{
  projects?: Project[];
  user?: User;
}>();

// Refs for elements to animate
const profileRef = ref(null);
const aboutRef = ref(null);
const headlineRef = ref(null);

// Computed properties for user data
const fullName = computed(() => {
  if (!props.user) return 'Portfolio Owner';
  return `${props.user.first_name || ''} ${props.user.last_name || ''}`.trim() || 'Portfolio Owner';
});

const firstName = computed(() => {
  return props.user?.first_name || 'there';
});

const userLocation = computed(() => {
  return props.user?.location || 'Lilongwe, Malawi';
});

const userBio = computed(() => {
  return props.user?.bio || `With more than a decade of experience, I have refined my abilities to produce visually appealing, user-centric designs that function seamlessly. I am enthusiastic about transforming creative concepts into digital realities, from the creation of intuitive user interface designs to the development of cohesive design systems and custom illustrations.`;
});

// Social media links with proper URL handling
const socialLinks = computed(() => {
  const socials = props.user?.socials || {};
  return [
    {
      name: 'LinkedIn',
      icon: IconBrandLinkedin,
      url: socials.linkedin?.startsWith('http') ? socials.linkedin : `https://linkedin.com/in/${socials.linkedin}`,
      show: !!socials.linkedin
    },
    {
      name: 'Twitter/X',
      icon: IconBrandX,
      url: socials.twitter?.startsWith('http') ? socials.twitter : `https://x.com/${socials.twitter}`,
      show: !!socials.twitter
    },
    {
      name: 'Behance',
      icon: IconBrandBehance,
      url: socials.behance?.startsWith('http') ? socials.behance : `https://be.net/${socials.behance}`,
      show: !!socials.behance
    },
    {
      name: 'Email',
      icon: IconEmail,
      url: `mailto:${props.user?.email}`,
      show: !!props.user?.email
    }
  ].filter(link => link.show);
});

// Avatar URL with fallback
const avatarUrl = computed(() => {
  return props.user?.avatar_url || '/assets/profile_400x400.jpg';
});

onMounted(() => {
  const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

  tl.from(profileRef.value, { opacity: 0, x: -50, duration: 1 })
    .from(headlineRef.value, { opacity: 0, y: 50, duration: 1.2 }, '-=0.5')
    .from(aboutRef.value, { opacity: 0, y: 20, duration: 1 }, '-=0.8');
});

defineOptions({ layout: AppLayout })
</script>

<template>
  <Head :title="`${fullName} - Portfolio`">
    <meta name="description" :content="`Portfolio of ${fullName}, a creative professional specializing in design and development.`" />
    <meta property="og:title" :content="`${fullName} - Portfolio`" />
    <meta property="og:description" :content="userBio.substring(0, 160)" />
    <meta property="og:type" content="website" />
  </Head>

  <div class="w-full max-w-2xl px-8 pt-10 mx-auto md:pt-16">

    <!-- Profile Section -->
    <div ref="profileRef" class="flex items-center gap-x-4">
      <div class="shrink-0">
        <img
          class="rounded-full shrink-0 size-16 object-cover ring-2 ring-gray-200 dark:ring-neutral-700"
          :src="avatarUrl"
          :alt="`${fullName} profile picture`"
          loading="eager" />
      </div>

      <div class="grow">
        <h1 class="text-lg font-medium text-gray-800 dark:text-neutral-200">
          {{ fullName }}
        </h1>

        <p class="text-sm text-gray-600 dark:text-neutral-400">
          Graphic Designer, Web Designer/Developer
        </p>

        <div class="flex items-center gap-1 mt-1 text-xs text-gray-500 dark:text-neutral-500" v-if="userLocation">
          <IconMapPin class="size-3" />
          <span>{{ userLocation }}</span>
        </div>
      </div>
    </div>
    <!-- End Profile Section -->

    <!-- Headline -->
    <h1
      ref="headlineRef"
      class="mt-8 text-3xl font-bold tracking-tight text-zinc-800 sm:text-4xl dark:text-zinc-100">
      Hi, I'm {{ firstName }}, a creative graphic designer and front-end web developer based in {{ userLocation }}.
    </h1>

    <!-- About Section -->
    <div ref="aboutRef" class="mt-8 text-base text-gray-600 dark:text-neutral-300">
      <div v-if="user?.bio" v-html="user.bio.replace(/\n/g, '</p><p class=&quot;mt-3&quot;>')"></div>
      <div v-else>
        <p>
          With more than a decade of experience, I have refined my abilities to produce visually appealing, user-centric designs that function seamlessly. I am enthusiastic about transforming creative concepts into digital realities, from the creation of intuitive user interface designs to the development of cohesive design systems and custom illustrations. My objective is to consistently generate designs that are not only visually compelling but also functional, thereby assisting clients in enhancing their brand and providing exceptional user experiences.
        </p>

        <p class="mt-3">
          I have worked with a variety of clients to revitalize their products and services over the course of my career. I am of the opinion that exceptional design is a combination of strategy and creativity, and I approach each assignment with this perspective. Regardless of whether I am developing websites from the ground up or improving existing platforms, my primary objective is to produce tangible outcomes.
        </p>

        <p class="mt-3">
          I am perpetually in the process of learning, adapting to new trends, and expanding the boundaries of what is feasible in both front-end development and design. Join me in the creation of something truly remarkable!
        </p>
      </div>

      <!-- Social Links -->
      <ul class="flex gap-3 mt-10 sm:gap-x-4" v-if="socialLinks.length > 0">
        <li v-for="link in socialLinks" :key="link.name">
          <a
            class="flex items-center justify-center p-2 text-gray-500 transition-colors rounded-lg hover:text-gray-800 hover:bg-gray-100 dark:text-neutral-500 dark:hover:text-neutral-400 dark:hover:bg-neutral-800"
            :href="link.url"
            target="_blank"
            rel="noopener noreferrer"
            :title="link.name">
            <component :is="link.icon" size="24" />
          </a>
        </li>
      </ul>
    </div>
    <!-- End About Section -->

    <!-- Featured Projects -->
    <Projects
      :projects="projects"
      :small-columns="false"
    />
    <!-- End Featured Projects -->

    <!-- Skills -->
    <Skills />
    <!-- End Skills -->

    <!-- Work Experience -->
    <Expertise />
    <!-- End Work Experience -->

    <!-- Newsletter Subscription -->
    <Subscription />
    <!-- End Newsletter Subscription -->
  </div>
</template>
