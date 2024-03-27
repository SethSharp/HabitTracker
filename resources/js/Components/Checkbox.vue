<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
    label: {
        type: String,
    },
    id: {
        type: String,
    },
})

const emit = defineEmits(['update:modelValue'])

const proxyChecked = computed({
    get() {
        return props.modelValue
    },

    set(val) {
        emit('update:modelValue', val)
    },
})
</script>

<template>
    <div class="relative flex items-start">
        <div class="flex items-center">
            <input
                :id="id"
                v-model="proxyChecked"
                :value="value"
                type="checkbox"
                class="my-0.5 h-8 w-8 rounded-full border-2 border-primary-600 text-primary-600 hover:bg-primary-600 hover:bg-opacity-25 focus:ring-transparent"
                v-bind="$attrs"
            />

            <div class="ml-3">
                <label :for="id" class="block text-sm font-medium leading-5 text-gray-700">
                    {{ label }}
                </label>
            </div>
        </div>
    </div>
</template>
