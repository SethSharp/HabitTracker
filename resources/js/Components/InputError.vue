<script setup>
defineProps({
    error: {
        type: String,
    },
})
</script>

<template>
    <div v-show="error">
        <p class="text-sm text-red-600">
            {{ error }}
        </p>
    </div>
</template>
