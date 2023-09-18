<script setup>
import { computed } from 'vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import Checkbox from '@/Components/Checkbox.vue'

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
    items: {
        type: Array,
        required: true,
    },
    description: {
        type: String,
    },
    disabled: {
        type: Array,
    },
})

const emit = defineEmits(['update:modelValue'])

const internalValue = computed({
    get: () => {
        return props.modelValue || []
    },
    set: (value) => {
        emit('update:modelValue', value)
    },
})
</script>

<template>
    <div v-for="(item, index) in items" class="cursor-pointer flex mt-4">
        <Checkbox
            :id="item.label ?? item"
            :key="index"
            v-model="internalValue"
            :value="item.value ?? item"
        />
    </div>

    <div class="text-gray-500 text-xs mt-3">{{ description }}</div>
</template>
