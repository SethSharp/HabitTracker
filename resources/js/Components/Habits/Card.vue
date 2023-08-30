<script setup>
const props = defineProps({
    success: {
        type: Boolean,
        default: false,
    },
    danger: {
        type: Boolean,
        default: false,
    },
    warning: {
        type: Boolean,
        default: false,
    },
    heading: {
        type: Boolean,
        default: false,
    },
})
</script>

<template>
    <div
        class="rounded-xl border-2 border-black border-opacity-75 underline-h-4 overflow-hidden my-4"
        :class="`${
            success
                ? 'bg-green-300 bg-opacity-25'
                : danger
                ? 'bg-red-300 bg-opacity-25'
                : warning
                ? 'bg-yellow-300 bg-opacity-25'
                : ''
        }`"
    >
        <div
            class="px-4 rounded-lg mx-4 my-4 py-2 underline underline-offset-8"
            :class="`
                ${heading ? 'border-primary bg-primary bg-opacity-25' : 'border-gray-500'}
                ${danger ? 'border-red-300 bg-red-300 bg-opacity-25' : ''}
                ${success ? 'border-green-300 bg-green-300 bg-opacity-25' : ''}
                ${warning ? 'border-yellow-300 bg-yellow-300 bg-opacity-25' : ''}
            `"
        >
            <slot name="heading"></slot>
        </div>
        <div class="overflow-y-auto mx-4">
            <slot name="content"></slot>
        </div>
    </div>
</template>
