<script setup>
defineProps({
    error: {
        type: String,
    },
})
</script>

<template>
  <div v-if="error" class="mt-1 text-sm text-red-400">
    <slot>
      {{ error }}
    </slot>
  </div>
</template>
