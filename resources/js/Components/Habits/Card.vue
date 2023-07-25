<script setup>

const props = defineProps({
    success: {
        type: Boolean,
        default: false
    },
    danger: {
        type: Boolean,
        default: false
    },
    warning: {
        type: Boolean,
        default: false
    },
    heading: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <div
        class="rounded-xl border-2 border-black overflow-hidden max-h-[650px] my-6"
        :class="`${success ? 'bg-green-300 bg-opacity-25' : danger ? 'bg-red-300 bg-opacity-25' : warning ? 'bg-yellow-300 bg-opacity-25' : ''}`"
    >
        <div
            class="px-6 lg:px-8 bg-gray-200 mx-4 my-4 py-2 rounded-xl border-2"
            :class="`
                ${heading ? 'border-indigo-500 bg-indigo-500 bg-opacity-25' : 'border-gray-500'}
                ${danger ? 'border-red-300 bg-red-300 bg-opacity-25' : ''}
                ${success ? 'border-green-300 bg-green-300 bg-opacity-25' : ''}
            `"
        >
            <slot name="heading"></slot>
        </div>
        <div class="min-h-[650px] max-h-[950px] overflow-y-auto mx-4">
            <slot name="content"></slot>
        </div>
    </div>
</template>
