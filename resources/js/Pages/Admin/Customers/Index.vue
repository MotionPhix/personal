<script setup lang="ts">
import Navheader from "@/Components/Backend/Navheader.vue";
import ContactActionMenu from "@/Components/Contact/ContactActionMenu.vue";
import ContactGridCard from "@/Components/Contact/ContactGridCard.vue";
import NoContactFound from "@/Components/Contact/NoContactFound.vue";
import Footnote from "@/Components/Front/Footnote.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Customer } from "@/types";
import { Head, Link } from "@inertiajs/vue3";
import { IconPlus } from "@tabler/icons-vue";

const props = defineProps<{

  customers: Customer[];

}>();

defineOptions({

  layout: AuthLayout

});

</script>

<template>
  <Head title="Explore customers" />

  <Navheader>

    <ContactActionMenu :contacts="customers" v-if="customers.length" />


    <h2 class="text-xl font-semibold dark:text-gray-300 sm:inline-block" v-else>
      Explore customers
    </h2>

  </Navheader>

  <main class="max-w-2xl px-6 py-12 mx-auto">

    <section v-if="customers.length">

      <ul role="list" class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        <ContactGridCard
          v-for="contact in customers"
          :key="contact.cid"
          :contact="contact"
        />

      </ul>

    </section>

    <section v-else class="w-full py-12 mt-12">
      <NoContactFound>
        <div>
          <Link
            :href="route('auth.customer.create')"
            class="flex gap-2 items-center text-gray-500 border-gray-500 border hover:border-gray-900 rounded-lg dark:border-slate-600 dark:text-gray-500 font-semibold my-4 px-3 py-1.5 dark:hover:text-gray-400 dark:hover:border-gray-400 hover:text-gray-900 transition duration-300"
            as="button">
            <IconPlus class="w-5 h-5" stroke-width="3.5" />

            <span>Create customer</span>
          </Link>
        </div>
      </NoContactFound>
    </section>

  </main>

  <Footnote />
</template>
