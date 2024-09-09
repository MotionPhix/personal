<script setup>
import {IconInfoCircle, IconX, IconCheck, IconExclamationMark} from "@tabler/icons-vue";
import {computed, onMounted} from "vue";
import {cva} from "class-variance-authority";

const props = defineProps({
  intent: {
    type: String,
    validator(value) {
      return ["info", "success", "danger", "warning"].includes(value);
    },
    default: "info",
  },
  title: String,
  show: {
    type: Boolean,
    default: true,
  },
  duration: {
    type: Number,
    default: 2000
  },
  onDismiss: Function,
});

const containerClass = computed(() => {
  return cva("flex p-4 rounded-md space-x-3", {
    variants: {
      intent: {
        info: "bg-blue-100",
        success: "bg-green-100",
        warning: "bg-orange-100",
        danger: "bg-red-100",
      },
    },
  })({
    intent: props.intent,
  });
});

const iconClass = computed(() => {
  return cva("w-6 h-6", {
    variants: {
      intent: {
        info: "text-blue-700",
        success: "text-green-600",
        warning: "text-orange-400",
        danger: "text-red-500",
      },
    },
  })({
    intent: props.intent,
  });
});

const titleClass = computed(() => {
  return cva("font-medium", {
    variants: {
      intent: {
        info: "text-blue-900",
        success: "text-green-900",
        warning: "text-orange-900",
        danger: "text-red-900"
      },
    },
  })({
    intent: props.intent,
  });
});

const contentClass = computed(() => {
  return cva("text-sm", {
    variants: {
      intent: {
        info: "text-blue-800",
        success: "text-green-800",
        warning: "text-orange-800",
        danger: "text-red-800",
      },
    },
  })({
    intent: props.intent,
  });
});

const closeButtonClass = computed(() => {
  return cva("p-0.5 rounded-md -m-1", {
    variants: {
      intent: {
        info: "text-blue-900/70 hover:text-blue-900 hover:bg-blue-200",
        success: "text-green-900/70 hover:text-green-900 hover:bg-green-200",
        warning: "text-orange-900/70 hover:text-orange-900 hover:bg-orange-200",
        danger: "text-red-900/70 hover:text-red-900 hover:bg-red-200",
      },
    },
  })({
    intent: props.intent,
  });
});

const iconComponent = computed(() => {
  const icons = {
    success: IconCheck,
    warning: IconExclamationMark,
    danger: IconX,
    info: IconInfoCircle,
  };

  return icons[props.intent];
});

function dismiss() {
  if (props.onDismiss) {
    props.onDismiss();
  }
}

onMounted(() => {
  setTimeout(() => dismiss(), props.duration);
});

</script>

<template>
  <div v-if="props.show" :class="containerClass">
    <div class="shrink-0">
      <component :is="iconComponent" :class="iconClass" />
    </div>

    <div class="flex-1 space-y-2">
      <h2 :class="titleClass">
        {{ props.title }}
      </h2>
      <div :class="contentClass"><slot /></div>
    </div>

<!--    <div class="shrink-0" v-if="props.onDismiss">-->
<!--      <button @click="dismiss()" :class="closeButtonClass">-->
<!--        <IconX class="w-6 h-6" />-->
<!--      </button>-->
<!--    </div>-->
  </div>
</template>
