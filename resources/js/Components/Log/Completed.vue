<script setup>
defineProps({
    log: String,
    styleClass: String
})
</script>

<template>
    <div
        class="rounded-md border border-black px-2 py-4 my-4 cursor-pointer bg-opacity-25 rounded-md p-4"
        :class="styleClass"
    >
        {{ log }}
    </div>
</template>
