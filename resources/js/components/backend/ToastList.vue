<script setup>
import ToastListItem from "@/components/backend/ToastListItem.vue";
import { ref, watch } from "vue";
import toast from "@/stores/toast";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const open = ref(true);

watch(() => page.props.notify, (newNotify) => {
  if (newNotify) {
    toast.add({
      type: newNotify.type,
      message: newNotify.message,
      title: newNotify.title,
    });
  }
});
</script>

<template>
  <TransitionGroup
    tag="div"
    enter-from-class="translate-x-full opacity-0"
    enter-active-class="duration-500"
    leave-active-class="duration-500"
    leave-to-class="translate-x-full opacity-0"
    class="fixed w-full max-w-xs space-y-4 top-4 right-4"
    style="z-index: 9999">
    <ToastListItem
      v-for="(item, index) in toast.items"
      :key="item.key"
      :intent="item.type"
      :title="item.title"
      :show="open"
      :dismiss="toast.remove(index)">
      {{ item.message }}
    </ToastListItem>
  </TransitionGroup>
</template>
