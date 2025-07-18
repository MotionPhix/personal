<script setup lang="ts">
import Navheader from "@/components/backend/Navheader.vue";
import ContactActionMenu from "@/components/contact/ContactActionMenu.vue";
import NoContactFound from "@/components/contact/NoContactFound.vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import { Customer } from "@/types";
import { Head, Link, router } from "@inertiajs/vue3";
import { IconPlus, IconSearch, IconArrowRight, IconFilter, IconGrid3x3, IconList, IconUsers, IconTrendingUp, IconBriefcase } from "@tabler/icons-vue";
import { ref, computed, onMounted, nextTick } from "vue";
import VueApexCharts from "vue3-apexcharts";
import { gsap } from "gsap";

// UI Components
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Badge } from "@/components/ui/badge";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { Switch } from "@/components/ui/switch";
import { Skeleton } from "@/components/ui/skeleton";
import { ScrollArea } from "@/components/ui/scroll-area";
import {useStorage} from "@vueuse/core";

// Props and options
// Update props interface to include growth_data
const props = defineProps<{
  customers: { data: Customer[]; links: any[] };
  filters: {
    search: string;
    status: string;
    sort_by: string;
    sort_direction: string;
  };
  stats: {
    total_customers: number;
    active_customers: number;
    customers_with_projects: number;
    customers_by_status: Record<string, number>;
    top_customers_by_projects: any[];
    growth_rate: string;
    growth_data: {
      labels: string[];
      data: number[];
    };
  };
}>();

defineOptions({
  layout: AuthLayout
});

// Reactive state
const searchQuery = ref("");
const viewMode = useStorage<"grid" | "list">("customers_view_mode", "grid");
const containerRef = ref<HTMLElement>();
const cardsRef = ref<HTMLElement[]>([]);
const loading = ref(false);
const selectedCustomers = ref<string[]>([]);

// Enhanced chart configuration with real data and better styling
const growthChartData = computed(() => ({
  chart: {
    type: 'area',
    toolbar: {
      show: false
    },
    animations: {
      enabled: true,
      easing: 'easeinout',
      speed: 800,
      animateGradually: {
        enabled: true,
        delay: 150
      }
    },
    fontFamily: 'inherit',
  },
  series: [{
    name: 'New Customers',
    data: props.stats.growth_data?.data || []
  }],
  stroke: {
    curve: 'smooth',
    width: 3,
    lineCap: 'round',
    colors: ['hsl(var(--primary))']
  },
  fill: {
    type: 'gradient',
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.45,
      opacityTo: 0.05,
      stops: [50, 100],
      colorStops: [
        {
          offset: 0,
          color: 'hsl(var(--primary))',
          opacity: 0.4
        },
        {
          offset: 100,
          color: 'hsl(var(--primary))',
          opacity: 0.1
        }
      ]
    }
  },
  grid: {
    borderColor: 'hsl(var(--border))',
    strokeDashArray: 4,
    xaxis: {
      lines: {
        show: true
      }
    },
    padding: {
      top: -20,
      right: 0,
      bottom: 0,
      left: 0
    }
  },
  xaxis: {
    categories: props.stats.growth_data?.labels || [],
    tooltip: {
      enabled: false
    },
    axisBorder: {
      show: false
    },
    labels: {
      style: {
        colors: 'hsl(var(--muted-foreground))',
        fontFamily: 'inherit'
      }
    }
  },
  yaxis: {
    labels: {
      style: {
        colors: 'hsl(var(--muted-foreground))',
        fontFamily: 'inherit'
      },
      formatter: (value) => Math.round(value)
    }
  },
  tooltip: {
    theme: 'dark',
    x: {
      show: false
    },
    y: {
      title: {
        formatter: () => 'New Customers:'
      }
    }
  }
}));

// Handle customer selection
const toggleCustomerSelection = (customerId: string) => {
  const index = selectedCustomers.value.indexOf(customerId);
  if (index === -1) {
    selectedCustomers.value.push(customerId);
  } else {
    selectedCustomers.value.splice(index, 1);
  }
};

// Delete selected customers
const deleteSelectedCustomers = async () => {
  if (!selectedCustomers.value.length) return;

  try {
    loading.value = true;
    // Implement delete logic here
    toast.success(`${selectedCustomers.value.length} customers deleted`, {
      description: "Selected customers have been deleted.",
    });
    selectedCustomers.value = [];
  } catch (error) {
    toast.error("Failed to delete customers", {
      description: "An error occurred while deleting customers.",
    });
  } finally {
    loading.value = false;
  }
};

// Handle status change
const updateCustomerStatus = async (customer: Customer, newStatus: string) => {
  try {
    loading.value = true;
    // Implement status update logic here
    toast.success(`Customer status updated to ${newStatus}`);
  } catch (error) {
    toast.error("Failed to update customer status");
  } finally {
    loading.value = false;
  }
};

// Animation functions
const animateCards = () => {
  if (cardsRef.value.length === 0) return;

  gsap.fromTo(cardsRef.value,
    {
      opacity: 0,
      y: 30,
      scale: 0.95
    },
    {
      opacity: 1,
      y: 0,
      scale: 1,
      duration: 0.6,
      stagger: 0.1,
      ease: "power2.out"
    }
  );
};

const animateHeader = () => {
  gsap.fromTo(".header-content",
    { opacity: 0, y: -20 },
    { opacity: 1, y: 0, duration: 0.8, ease: "power2.out" }
  );
};

// Lifecycle
onMounted(() => {
  nextTick(() => {
    animateHeader();
    animateCards();
  });
});

// Watch for view mode changes and re-animate
const handleViewModeChange = () => {
  console.log(`View mode changed to: ${viewMode.value}`);
  nextTick(() => {
    animateCards();
  });
};

// Helper functions
const getInitials = (firstName: string, lastName: string) => {
  return `${firstName[0]}${lastName[0]}`.toUpperCase();
};

const getCustomerStatus = (customer: Customer) => {
  return customer.status || 'inactive';
};
</script>

<template>
  <Head title="Customer Management" />

  <div class="min-h-screen bg-background">
    <!-- Enhanced Header -->
    <Navheader>
      <div class="header-content flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 w-full">
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-3 flex-1">
            <div class="p-2 bg-primary/10 rounded-lg">
              <IconUsers class="w-6 h-6 text-primary" />
            </div>

            <div class="flex-1">
              <section class="flex items-start justify-between w-full">
                <h1 class="text-2xl font-bold text-foreground">
                  Customer Management
                </h1>

                <!-- Add Customer Button -->
                <Link :href="route('admin.customers.create')" as="button">
                  <Button class="bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg hover:shadow-xl transition-all duration-300">
                    <IconPlus class="w-4 h-4 mr-2" />
                    Add Customer
                  </Button>
                </Link>
              </section>

              <p class="text-sm text-muted-foreground">
                Manage and organize your customer relationships
              </p>
            </div>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="flex gap-4 overflow-x-auto pb-2 lg:pb-0">
          <Card class="min-w-[140px] bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950 dark:to-blue-900 border-blue-200 dark:border-blue-800">
            <CardContent class="p-4">
              <div class="flex items-center gap-2">
                <IconUsers class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                <div>
                  <p class="text-sm font-medium text-blue-900 dark:text-blue-100">Total</p>
                  <p class="text-xl font-bold text-blue-900 dark:text-blue-100">
                    {{ stats.total_customers }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card class="min-w-[140px] bg-gradient-to-br from-green-50 to-green-100 dark:from-green-950 dark:to-green-900 border-green-200 dark:border-green-800">
            <CardContent class="p-4">
              <div class="flex items-center gap-2">
                <IconUsers class="w-4 h-4 text-green-600 dark:text-green-400" />
                <div>
                  <p class="text-sm font-medium text-green-900 dark:text-green-100">Active</p>
                  <p class="text-xl font-bold text-green-900 dark:text-green-100">
                    {{ stats.active_customers }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card class="min-w-[140px] bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-950 dark:to-purple-900 border-purple-200 dark:border-purple-800">
            <CardContent class="p-4">
              <div class="flex items-center gap-2">
                <IconBriefcase class="w-4 h-4 text-purple-600 dark:text-purple-400" />
                <div>
                  <p class="text-sm font-medium text-purple-900 dark:text-purple-100">
                    With Projects
                  </p>

                  <p class="text-xl font-bold text-purple-900 dark:text-purple-100">
                    {{ stats.customers_with_projects }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card class="min-w-[140px] bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-950 dark:to-amber-900 border-amber-200 dark:border-amber-800">
            <CardContent class="p-4">
              <div class="flex items-center gap-2">
                <IconTrendingUp class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                <div>
                  <p class="text-sm font-medium text-amber-900 dark:text-amber-100">Growth</p>
                  <p class="text-xl font-bold text-amber-900 dark:text-amber-100">
                    {{ stats.growth_rate }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </Navheader>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 max-w-7xl">
      <!-- Search and Controls -->
      <div class="mb-8 space-y-4">
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
          <!-- Search -->
          <div class="relative flex-1 max-w-md">
            <IconSearch class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <Input
              v-model="searchQuery"
              placeholder="Search customers..."
              class="pl-10 bg-background/50 backdrop-blur-sm border-border/50 focus:border-primary/50 transition-all duration-300"
            />
          </div>

          <!-- Controls -->
          <div class="flex items-center gap-3">
            <!-- View Mode Toggle -->
            <div class="flex items-center gap-2 p-1 bg-muted rounded-lg">
              <Button
                variant="ghost"
                size="sm"
                :class="viewMode === 'grid' ? 'bg-background shadow-sm' : ''"
                @click="viewMode = 'grid'; handleViewModeChange()">
                <IconGrid3x3 class="w-4 h-4" />
              </Button>

              <Button
                variant="ghost"
                size="sm"
                :class="viewMode === 'list' ? 'bg-background shadow-sm' : ''"
                @click="viewMode = 'list'; handleViewModeChange()">
                <IconList class="w-4 h-4" />
              </Button>
            </div>

            <!-- Filter Dropdown -->
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="outline" size="sm">
                  <IconFilter class="w-4 h-4 mr-2" />
                  Filter
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end" class="w-48">
                <DropdownMenuItem>All Customers</DropdownMenuItem>
                <DropdownMenuItem>Active Only</DropdownMenuItem>
                <DropdownMenuItem>Inactive Only</DropdownMenuItem>
                <DropdownMenuItem>Recent</DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>
        </div>

        <!-- Action Menu for Selected Customers -->
        <ContactActionMenu :contacts="customers" v-if="customers.length" />
      </div>

      <!-- Customer Stats Overview -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Growth Chart -->
        <Card class="lg:col-span-2">
          <CardHeader class="flex flex-row items-center justify-between pb-2">
            <CardTitle>Customer Growth</CardTitle>
            <Badge variant="outline" class="font-mono">
              {{ stats.growth_rate }}
            </Badge>
          </CardHeader>
          <CardContent>
            <VueApexCharts
              width="100%"
              height="280"
              :options="growthChartData"
              :series="[{
                name: 'New Customers',
                data: stats.growth_data?.data || []
              }]"
            />
          </CardContent>
        </Card>

        <!-- Status Distribution -->
        <ScrollArea class="h-[230px]">
          <Card>
            <CardHeader>
              <CardTitle>Status Distribution</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-4">
                <div v-for="(count, status) in stats.customers_by_status" :key="status">
                  <div class="flex items-center justify-between mb-1">
                    <Badge :variant="status === 'active' ? 'default' : 'secondary'" class="capitalize">
                      {{ status }}
                    </Badge>
                    <span class="text-sm text-muted-foreground">
                      {{ Math.round((count / stats.total_customers) * 100) }}%
                    </span>
                  </div>
                  <div class="h-2 bg-muted rounded-full overflow-hidden">
                    <div
                      class="h-full bg-primary transition-all duration-500"
                      :style="{
                        width: `${(count / stats.total_customers) * 100}%`,
                        backgroundColor: status === 'active' ? 'hsl(var(--primary))' :
                          status === 'inactive' ? 'hsl(var(--muted-foreground))' :
                          'hsl(var(--secondary))'
                      }"
                    ></div>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </ScrollArea>
      </div>

      <!-- Top Customers -->
      <Card class="mb-8">
        <CardHeader>
          <CardTitle>Top Performing Customers</CardTitle>
          <CardDescription>Customers with the most projects</CardDescription>
        </CardHeader>
        <CardContent>
          <ScrollArea class="h-[360px]">
            <div class="space-y-6">
              <div
                v-for="(customer, index) in stats.top_customers_by_projects"
                :key="customer.uuid"
                class="flex items-center justify-between group"
              >
                <div class="flex items-center gap-4">
                  <div class="relative">
                    <Avatar class="w-12 h-12">
                      <AvatarFallback class="bg-primary/10 text-primary">
                        {{ getInitials(customer.first_name, customer.last_name) }}
                      </AvatarFallback>
                    </Avatar>
                    <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-muted flex items-center justify-center text-xs font-medium">
                      {{ index + 1 }}
                    </div>
                  </div>
                  <div>
                    <h4 class="font-medium group-hover:text-primary transition-colors">
                      {{ customer.first_name }} {{ customer.last_name }}
                    </h4>
                    <p class="text-sm text-muted-foreground">{{ customer.company_name || 'No company' }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-4">
                  <div class="text-right">
                    <p class="font-medium">{{ customer.projects_count }}</p>
                    <p class="text-xs text-muted-foreground">Projects</p>
                  </div>
                  <Button variant="ghost" size="icon">
                    <IconArrowRight class="w-4 h-4" />
                  </Button>
                </div>
              </div>
            </div>
          </ScrollArea>
        </CardContent>
      </Card>

      <!-- Loading State -->
      <div v-if="loading" class="space-y-4">
        <Skeleton class="h-12 w-full" />
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <Skeleton v-for="n in 8" :key="n" class="h-[200px]" />
        </div>
      </div>

      <article
        v-if="customers.data.length && !loading"
        class="grid gap-8 mb-6 lg:mb-16 md:grid-cols-2">
        <div
          v-for="(customer, index) in customers.data"
          :key="customer.uuid"
          class="items-center bg-gray-50 rounded-lg shadow sm:flex dark:bg-gray-800 dark:border-gray-700">
          <Link href="#" class="h-full">
            <img
              class="w-full rounded-lg"
              src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png" alt="Bonnie Avatar">
          </Link>

          <div class="p-5 sm:flex-1">
            <h3 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
              <a href="#">{{ customer.first_name }} {{ customer.last_name }}</a>
            </h3>

            <span class="text-gray-500 dark:text-gray-400">
              {{ customer.job_title || 'No company' }}
            </span>

            <p class="mt-3 mb-4 font-light text-gray-500 dark:text-gray-400">
              {{ customer.company_name || 'No company.' }}
            </p>

            <ul class="flex space-x-4 sm:mt-0">
              <li>
                <Link :href="route('admin.customers.edit', customer.uuid)" as="button" class="flex-1">
                  <Button variant="outline" size="sm" class="w-full">
                    Edit
                  </Button>
                </Link>
              </li>

              <li>
                <Link :href="route('admin.projects.create', customer.uuid)" as="button">
                  <Button variant="outline" size="sm" class="w-full">
                    Project
                  </Button>
                </Link>
              </li>
            </ul>
          </div>
        </div>
      </article>

        <!-- Customer Grid/List -->
      <div v-if="customers.data.length && !loading" ref="containerRef" class="transition-all duration-300">
        <!-- Grid View -->
        <div
          v-if="viewMode === 'grid'"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <Card
            v-for="(customer, index) in customers.data"
            :key="customer.uuid"
            :ref="el => cardsRef[index] = el as HTMLElement"
            class="group hover:shadow-xl transition-all duration-300 cursor-pointer border-border/50 hover:border-primary/50 bg-card/50 backdrop-blur-sm"
            @click="router.visit(route('admin.customers.edit', customer.uuid))">
            <CardHeader class="pb-3">
              <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                  <Avatar class="w-12 h-12 ring-2 ring-primary/20 group-hover:ring-primary/40 transition-all duration-300">
                    <AvatarImage :src="customer.avatar" />
                    <AvatarFallback class="bg-gradient-to-br from-primary/20 to-primary/10 text-primary font-semibold">
                      {{ getInitials(customer.first_name, customer.last_name) }}
                    </AvatarFallback>
                  </Avatar>

                  <div class="flex-1 min-w-0">
                    <CardTitle class="text-lg truncate group-hover:text-primary transition-colors duration-300">
                      {{ customer.first_name }} {{ customer.last_name }}
                    </CardTitle>

                    <CardDescription class="truncate">
                      {{ customer.job_title || 'Customer' }}
                    </CardDescription>
                  </div>
                </div>

                <Badge
                  :variant="getCustomerStatus(customer) === 'active' ? 'default' : 'secondary'"
                  class="text-xs">
                  {{ getCustomerStatus(customer) }}
                </Badge>
              </div>
            </CardHeader>

            <CardContent class="pt-0">
              <div class="space-y-2">
                <p class="text-sm text-muted-foreground truncate" v-if="customer.email">
                  {{ customer.email }}
                </p>

                <p class="text-sm text-muted-foreground truncate" v-if="customer.phone">
                  {{ customer.phone }}
                </p>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-2 mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <Link :href="route('admin.customers.edit', customer.uuid)" as="button" class="flex-1">
                  <Button variant="outline" size="sm" class="w-full">
                    Edit
                  </Button>
                </Link>

                <Link :href="route('admin.projects.create', customer.uuid)" as="button">
                  <Button variant="outline" size="sm">
                    Project
                  </Button>
                </Link>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- List View -->
        <Card v-else class="bg-card/50 backdrop-blur-sm border-border/50">
          <CardContent class="p-0">
            <div class="divide-y divide-border/50">
              <div
                v-for="(customer, index) in customers.data"
                :key="customer.uuid"
                :ref="el => cardsRef[index] = el as HTMLElement"
                class="p-4 hover:bg-muted/50 transition-colors duration-200 cursor-pointer group"
                @click="router.visit(route('admin.customers.edit', customer.uuid))"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-4 flex-1 min-w-0">
                    <Avatar class="w-10 h-10 ring-2 ring-primary/20 group-hover:ring-primary/40 transition-all duration-300">
                      <AvatarImage :src="customer.avatar" />
                      <AvatarFallback class="bg-gradient-to-br from-primary/20 to-primary/10 text-primary font-semibold">
                        {{ getInitials(customer.first_name, customer.last_name) }}
                      </AvatarFallback>
                    </Avatar>

                    <div class="flex-1 min-w-0">
                      <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors duration-300 truncate">
                          {{ customer.first_name }} {{ customer.last_name }}
                        </h3>
                        <Badge
                          :variant="getCustomerStatus(customer) === 'active' ? 'default' : 'secondary'"
                          class="text-xs"
                        >
                          {{ getCustomerStatus(customer) }}
                        </Badge>
                      </div>
                      <p class="text-sm text-muted-foreground truncate">
                        {{ customer.job_title || 'Customer' }}
                      </p>
                      <div class="flex gap-4 mt-1">
                        <p class="text-xs text-muted-foreground truncate" v-if="customer.email">
                          {{ customer.email }}
                        </p>
                        <p class="text-xs text-muted-foreground" v-if="customer.phone">
                          {{ customer.phone }}
                        </p>
                      </div>
                    </div>
                  </div>

                  <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <Link :href="route('admin.customers.edit', customer.uuid)" as="button">
                      <Button variant="outline" size="sm">
                        Edit
                      </Button>
                    </Link>
                    <Link :href="route('admin.projects.create', customer.uuid)" as="button">
                      <Button variant="outline" size="sm">
                        Project
                      </Button>
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16">
        <NoContactFound>
          <div class="space-y-4">
            <div class="mx-auto w-24 h-24 bg-muted rounded-full flex items-center justify-center mb-6">
              <IconUsers class="w-12 h-12 text-muted-foreground" />
            </div>
            <h3 class="text-xl font-semibold text-foreground">
              {{ searchQuery ? 'No customers found' : 'No customers yet' }}
            </h3>
            <p class="text-muted-foreground max-w-md mx-auto">
              {{ searchQuery
                ? `No customers match "${searchQuery}". Try adjusting your search.`
                : 'Get started by adding your first customer to begin managing relationships.'
              }}
            </p>
            <div class="flex gap-3 justify-center mt-6">
              <Button v-if="searchQuery" variant="outline" @click="searchQuery = ''">
                Clear Search
              </Button>
              <Link :href="route('admin.customers.create')" as="button">
                <Button class="bg-primary hover:bg-primary/90 text-primary-foreground">
                  <IconPlus class="w-4 h-4 mr-2" />
                  Add Your First Customer
                </Button>
              </Link>
            </div>
          </div>
        </NoContactFound>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Custom scrollbar for horizontal scroll */
.overflow-x-auto::-webkit-scrollbar {
  height: 4px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: hsl(var(--muted));
  border-radius: 2px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: hsl(var(--muted-foreground) / 0.3);
  border-radius: 2px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: hsl(var(--muted-foreground) / 0.5);
}
</style>
