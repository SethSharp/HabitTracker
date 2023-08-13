<script setup>
import { onMounted, ref } from 'vue'

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

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus()
    }
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
    <input
        ref="input"
        type="date"
        :min="min"
        :max="max"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
    <br>
    <label class="text-gray-500">
        {{ label }}
    </label>
</template>
