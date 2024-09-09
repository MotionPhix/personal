<script setup>
import ToastListItem from "@/Components/Backend/ToastListItem.vue";
import {onUnmounted, ref} from "vue";
import toast from "@/Stores/toast"
import {router, usePage} from "@inertiajs/vue3";

const page = usePage();

const open = ref(true)

let removeFinshEventListener = router.on("finish", () => {
  if (page.props.notify) {
    toast.add({
      type: page.props.notify.type,
      message: page.props.notify.message,
      title: page.props.notify.title,
    });
  }
});

onUnmounted(() => removeFinshEventListener());

function remove(index) {
  toast.remove(index);
}
</script>

<template>
  <TransitionGroup
    tag="div"
    enter-from-class="translate-x-full opacity-0"
    enter-active-class="duration-500"
    leave-active-class="duration-500"
    leave-to-class="translate-x-full opacity-0"
    class="fixed top-4 right-4 w-full max-w-xs space-y-4"
    style="z-index: 9999">
    <ToastListItem
      v-for="(item, index) in toast.items"
      :key="item.key"
      :intent="item.type"
      :title="item.title"
      :show="open"
      :duration="5000"
      :on-dismiss="remove">
      {{ item.message }}
    </ToastListItem>
  </TransitionGroup>
</template>
