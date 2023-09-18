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
        <div class="w-full">
            <Menu>
                <MenuButton class="px-2 flex justify-start text-sm w-full cursor-pointer">
                    <label class="py-2 cursor-pointer"> {{ item.label }}</label>
                </MenuButton>
            </Menu>
        </div>
    </div>
</template>
