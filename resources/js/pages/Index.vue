<script setup lang="ts">
import {Head, router} from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import {
  IconBrandBehance,
  IconBrandLinkedin,
  IconBrandX,
  IconMapPin,
  IconDownload,
  IconExternalLink,
  IconArrowRight,
  IconStar,
  IconUsers,
  IconBriefcase,
  IconMail,
  IconPhone,
  IconArrowDown
} from '@tabler/icons-vue';
import Projects from '@/components/front/Projects.vue';
import Skills from '@/components/front/Skills.vue';
import Expertise from '@/components/front/Expertise.vue';
import Subscription from '@/components/front/Subscription.vue';
import {Project, User} from '@/types';
import {onMounted, ref, computed, nextTick, onUnmounted} from 'vue';
import {gsap} from 'gsap';
import {ScrollTrigger} from 'gsap/ScrollTrigger';
import IconEmail from '@/components/icon/IconEmail.vue';
import {Play as IconPlay} from 'lucide-vue-next'

// Shadcn Vue Components
import {Card, CardContent} from '@/components/ui/card';
import {Button} from '@/components/ui/button';
import {Avatar, AvatarFallback, AvatarImage} from '@/components/ui/avatar';

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

const props = defineProps<{
  projects?: Project[];
  user?: User;
}>();

// Refs for elements to animate
const heroRef = ref<HTMLElement>();
const heroTextRef = ref<HTMLElement>();
const profileCardRef = ref<HTMLElement>();
const statsRef = ref<HTMLElement>();
const aboutRef = ref<HTMLElement>();
const contentSections = ref<HTMLElement[]>([]);

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

const userTitle = computed(() => {
  return props.user?.job_title || 'Graphic Designer, & Web Developer';
});

// Social media links with proper URL handling
const socialLinks = computed(() => {
  const socials = props.user?.socials || {};
  return [
    {
      name: 'LinkedIn',
      icon: IconBrandLinkedin,
      url: socials.linkedin?.startsWith('http') ? socials.linkedin : `https://linkedin.com/in/${socials.linkedin}`,
      show: !!socials.linkedin,
      color: 'hover:text-blue-600'
    },
    {
      name: 'Twitter/X',
      icon: IconBrandX,
      url: socials.twitter?.startsWith('http') ? socials.twitter : `https://x.com/${socials.twitter}`,
      show: !!socials.twitter,
      color: 'hover:text-gray-900 dark:hover:text-gray-100'
    },
    {
      name: 'Behance',
      icon: IconBrandBehance,
      url: socials.behance?.startsWith('http') ? socials.behance : `https://be.net/${socials.behance}`,
      show: !!socials.behance,
      color: 'hover:text-blue-500'
    },
    {
      name: 'Email',
      icon: IconEmail,
      url: `mailto:${props.user?.email}`,
      show: !!props.user?.email,
      color: 'hover:text-green-600'
    }
  ].filter(link => link.show);
});

// Avatar URL with fallback
const avatarUrl = computed(() => {
  return props.user?.avatar_url || '/assets/profile_400x400.jpg';
});

// Stats data
const stats = computed(() => [
  {
    label: 'Years Experience',
    value: '10+',
    icon: IconStar,
    color: 'text-amber-600'
  },
  {
    label: 'Happy Clients',
    value: '50+',
    icon: IconUsers,
    color: 'text-green-600'
  }
]);

// Add intersection observer for reliable animations
const isHeroVisible = ref(true);
const heroObserver = ref<IntersectionObserver | null>(null);

// New computed property for stats animation
const animatedStats = computed(() => stats.value.map(stat => ({
  ...stat,
  animatedValue: ref(0),
  formattedValue: typeof stat.value === 'string' ? stat.value : new Intl.NumberFormat().format(stat.value)
})));

// Enhanced animation timeline
const initHeroAnimations = () => {
  if (!heroTextRef.value || !profileCardRef.value || !statsRef.value) return;

  const tl = gsap.timeline({
    defaults: {ease: 'expo.out'},
    scrollTrigger: {
      trigger: heroRef.value,
      start: 'top center',
      end: 'bottom center',
      toggleActions: 'play none none reverse'
    }
  });

  // Text reveal animations with better timing
  tl.fromTo(heroTextRef.value.querySelectorAll('.hero-line'),
    {
      y: 100,
      opacity: 0,
      rotateX: -45,
    },
    {
      y: 0,
      opacity: 1,
      rotateX: 0,
      duration: 1.5,
      stagger: 0.15,
    }
  )
    .fromTo(profileCardRef.value,
      {
        y: 50,
        opacity: 0,
        scale: 0.9,
      },
      {
        y: 0,
        opacity: 1,
        scale: 1,
        duration: 1,
        ease: 'elastic.out(1, 0.8)',
      },
      '-=0.8'
    )
    .fromTo(statsRef.value.querySelectorAll('.stat-item'),
      {
        y: 30,
        opacity: 0,
        scale: 0.8,
      },
      {
        y: 0,
        opacity: 1,
        scale: 1,
        duration: 0.8,
        stagger: 0.1,
        ease: 'back.out(1.7)',
      },
      '-=0.6'
    );
};

// Setup intersection observer for reliable animation triggering
onMounted(() => {
  nextTick(() => {
    heroObserver.value = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            isHeroVisible.value = true;
            initHeroAnimations();
            heroObserver.value?.disconnect();
          }
        });
      },
      {
        threshold: 0.1
      }
    );

    if (heroRef.value) {
      heroObserver.value.observe(heroRef.value);
    }
  });
});

// Cleanup
onUnmounted(() => {
  heroObserver.value?.disconnect();
});

// Enhanced animations
onMounted(() => {
  // Hero section animation
  const heroTl = gsap.timeline({defaults: {ease: 'power3.out'}});

  heroTl
    .from(heroTextRef.value?.querySelectorAll('.hero-line'), {
      y: 100,
      opacity: 0,
      duration: 1.2,
      stagger: 0.2,
      ease: 'power2.out'
    })
    .from(profileCardRef.value, {
      y: 50,
      opacity: 0,
      duration: 1,
      ease: 'power2.out'
    }, '-=0.8')
    .from(statsRef.value?.querySelectorAll('.stat-item'), {
      y: 30,
      opacity: 0,
      duration: 0.8,
      stagger: 0.1,
      ease: 'power2.out'
    }, '-=0.6');

  // About section animation
  gsap.fromTo(aboutRef.value,
    {
      opacity: 0,
      y: 100,
      scale: 0.95
    },
    {
      opacity: 1,
      y: 0,
      scale: 1,
      duration: 1.4,
      ease: 'power2.out',
      scrollTrigger: {
        trigger: aboutRef.value,
        start: 'top bottom-=100',
        toggleActions: 'play none none reverse',
      }
    }
  );

  // Content sections scroll animations
  contentSections.value.forEach((section, index) => {
    if (section) {
      gsap.fromTo(section,
        {
          opacity: 0,
          y: 80,
          scale: 0.95
        },
        {
          opacity: 1,
          y: 0,
          scale: 1,
          duration: 1.2,
          ease: 'power2.out',
          scrollTrigger: {
            trigger: section,
            start: 'top bottom-=150',
            toggleActions: 'play none none reverse',
          },
          delay: index * 0.1
        }
      );
    }
  });

  // Add number counter animation for stats
  animatedStats.value.forEach((stat, index) => {
    const targetValue = parseInt(stat.value) || 0;
    gsap.to(stat.animatedValue, {
      value: targetValue,
      duration: 2,
      delay: index * 0.2,
      ease: 'power2.out',
      scrollTrigger: {
        trigger: statsRef.value,
        start: 'top bottom-=100',
        toggleActions: 'play none none reverse'
      }
    });
  });
});

defineOptions({layout: AppLayout})
</script>

<template>
  <Head :title="`${fullName} - Portfolio`">
    <meta name="description"
          :content="`Portfolio of ${fullName}, a creative professional specializing in design and development.`"/>
    <meta property="og:title" :content="`${fullName} - Portfolio`"/>
    <meta property="og:description" :content="userBio.substring(0, 160)"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" :content="avatarUrl"/>
  </Head>

  <!-- Hero Section -->
  <section
    ref="heroRef"
    class="relative min-h-[100svh] flex items-center justify-center bg-background/80 backdrop-blur-sm overflow-hidden"
  >
    <!-- Animated gradient background -->
    <div class="absolute inset-0 bg-grid-pattern opacity-[0.03]"></div>
    <div class="absolute inset-0">
      <div class="absolute inset-0 bg-gradient-to-tr from-primary/10 via-transparent to-secondary/10"></div>
      <div class="absolute inset-0 bg-gradient-to-br from-transparent via-accent/5 to-transparent"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
      <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
        <!-- Left Column: Text Content -->
        <div ref="heroTextRef" class="flex-1 space-y-6 text-center lg:text-left">
          <div class="space-y-4">
            <h1 class="hero-line text-4xl md:text-5xl xl:text-6xl font-bold tracking-tight">
              Hi, I'm <span class="text-primary">
              {{ firstName }}!
            </span>
            </h1>

            <p class="hero-line text-xl md:text-2xl text-muted-foreground font-mono">
              {{ userTitle }}
            </p>
          </div>

          <p class="hero-line text-base md:text-lg text-muted-foreground/90 max-w-2xl font-mono">
            Passionate about creating stunning visuals and seamless digital experiences. With over a decade of
            experience in digital arts and web development.
          </p>

          <div class="hero-line flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
            <Button size="lg" class="gap-2 w-full lg:w-auto">
              View Portfolio
              <IconArrowRight class="w-4 h-4"/>
            </Button>

            <Button variant="outline" size="lg" class="gap-2 w-full lg:w-auto">
              Download Resume
              <IconDownload class="w-4 h-4"/>
            </Button>
          </div>

          <!-- Social Links -->
          <div class="hero-line flex items-center justify-center lg:justify-start gap-4 pt-2">
            <Button
              v-for="link in socialLinks"
              :key="link.name"
              variant="ghost"
              size="icon"
              as-child
              :class="[
                  'transition-all duration-300 hover:scale-110',
                  link.color
                ]"
            >
              <a
                :href="link.url"
                target="_blank"
                rel="noopener noreferrer"
                :title="link.name"
              >
                <component :is="link.icon" class="w-5 h-5"/>
              </a>
            </Button>
          </div>
        </div>

        <!-- Right Column: Profile & Stats -->
        <div class="flex-1 w-full max-w-md lg:max-w-none">
          <!-- Profile Card -->
          <div ref="profileCardRef" class="relative">
            <!-- Main Image -->
            <div
              class="relative aspect-square rounded-3xl overflow-hidden bg-gradient-to-br from-primary/20 via-transparent to-secondary/20 p-1">
              <img
                :src="avatarUrl"
                :alt="fullName"
                class="w-full h-full object-cover rounded-[22px]"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent rounded-[22px]"></div>
            </div>

            <!-- Floating Stats Cards -->
            <div ref="statsRef" class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-full max-w-sm">
              <div class="grid grid-cols-2 gap-4 px-4">
                <div
                  v-for="(stat, index) in animatedStats"
                  :key="index"
                  class="stat-item group relative bg-background/80 backdrop-blur-md border rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all duration-500 hover:-translate-y-1"
                >
                  <div class="flex items-center gap-3">
                    <div :class="['p-2 rounded-xl bg-primary/10', stat.color]">
                      <component :is="stat.icon" class="w-5 h-5"/>
                    </div>
                    <div>
                      <p class="font-bold text-xl tabular-nums">
                        {{
                          typeof stat.value === 'string' ? stat.value : Math.round(stat.animatedValue).toLocaleString()
                        }}
                      </p>
                      <p class="text-xs text-muted-foreground font-mono">{{ stat.label }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
      <Button
        variant="ghost"
        size="icon"
        @click="document.getElementById('about')?.scrollIntoView({ behavior: 'smooth' })"
      >
        <IconArrowDown class="w-5 h-5"/>
      </Button>
    </div>
  </section>

  <!-- Content Sections -->
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-32 py-20">

    <!-- Featured Projects -->
    <section ref="el => contentSections[0] = el as HTMLElement">
      <div class="text-center mb-16">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-sans font-bold text-foreground mb-6">
          Featured Work
        </h2>
        <p class="text-xl text-muted-foreground max-w-3xl mx-auto font-mono">
          A showcase of my recent projects and creative solutions
        </p>
      </div>
      <Projects
        :projects="projects"
        :small-columns="false"
      />
    </section>

    <!-- Skills -->
    <section ref="el => contentSections[1] = el as HTMLElement">
      <div class="text-center mb-16">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-sans font-bold text-foreground mb-6">
          Skills & Expertise
        </h2>
        <p class="text-xl text-muted-foreground max-w-3xl mx-auto font-mono">
          The tools and technologies I use to bring ideas to life
        </p>
      </div>
      <Skills/>
    </section>

    <!-- Experience -->
    <section ref="el => contentSections[2] = el as HTMLElement">
      <div class="text-center mb-12">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-sans font-bold text-foreground mb-6">
          Experience
        </h2>
        <p class="text-xl text-muted-foreground max-w-3xl mx-auto font-mono">
          My journey and areas of expertise in design and development
        </p>
      </div>
      <Expertise/>
    </section>

    <!-- Newsletter -->
    <section ref="el => contentSections[3] = el as HTMLElement">
      <Subscription/>
    </section>
  </div>
</template>

<style scoped>
/* Dot pattern background */
.bg-dot-pattern {
  background-image: radial-gradient(circle, rgba(0, 0, 0, 0.1) 1px, transparent 1px);
  background-size: 20px 20px;
}

/* Smooth transitions for all interactive elements */
* {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

/* Enhanced hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

.group:hover .group-hover\:translate-x-1 {
  transform: translateX(0.25rem);
}

.group:hover .group-hover\:animate-bounce {
  animation: bounce 1s infinite;
}

/* Backdrop blur support */
@supports (backdrop-filter: blur(10px)) {
  .backdrop-blur-sm {
    backdrop-filter: blur(8px);
  }
}

/* Enhanced focus states for accessibility */
.focus\:ring-primary\/20:focus {
  --tw-ring-color: hsl(var(--primary) / 0.2);
}

/* Hardware acceleration for better performance */
.group {
  transform: translateZ(0);
  will-change: transform;
}

/* Prose styling improvements */
.prose {
  color: hsl(var(--muted-foreground));
}

.prose p {
  margin-bottom: 1rem;
  line-height: 1.8;
}

.prose p:last-child {
  margin-bottom: 0;
}

/* Dark mode adjustments */
@media (prefers-color-scheme: dark) {
  .bg-dot-pattern {
    background-image: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
  }
}

/* Keyframes for animations */
@keyframes blob {
  0%, 100% {
    transform: scale(1) translateY(0);
  }
  50% {
    transform: scale(1.05) translateY(-10px);
  }
}

@keyframes scroll {
  0% {
    transform: translateY(0);
  }
  100% {
    transform: translateY(10px);
  }
}

/* Gradient animation for hero text */
@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  100% {
    background-position: 100% 50%;
  }
}

/* Typewriter effect for subtitle */
@keyframes typewriter {
  from {
    width: 0;
  }
  to {
    width: 100%;
  }
}
</style>
