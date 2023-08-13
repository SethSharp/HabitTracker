<script setup>
import { ref } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline/index.js'

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    label: String,
    min: {
        type: String,
    },
    max: {
        type: String,
    },
})

defineEmits(['update:modelValue'])

const input = ref(null)

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
    <div class="flex items-center">
        <input
            ref="input"
            type="date"
            :min="min"
            :max="max"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <XMarkIcon
            @click="$emit('update:modelValue', null)"
            class="ml-2 w-5 h-5 cursor-pointer hover:scale-125"
        />
    </div>

    <br />
    <label class="text-gray-500">
        {{ label }}
    </label>
</template>
