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
    }
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
        <div class="flex h-5 items-center">
            <input
                :id="id"
                v-model="proxyChecked"
                :value="value"
                type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-teal-500 focus:ring-teal-500"
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
