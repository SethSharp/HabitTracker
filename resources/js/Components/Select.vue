<script setup>
import { computed } from 'vue'

const Option = {
    id: '',
    name: '',
}

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    id: String,
    options: Array,
    label: String,
})

const emit = defineEmits(['update:modelValue'])

const proxySelected = computed({
    get() {
        return props.modelValue
    },

    set(val) {
        emit('update:modelValue', val)
    },
})
</script>

<template>
    <select
        :id="id"
        v-model="proxySelected"
        class="form-select block w-full rounded-md border px-3 py-2 shadow-sm transition duration-150 ease-in-out focus:border-primary-300 focus:ring-primary-200/50 sm:text-sm"
        v-bind="$attrs"
    >
        <slot>
            <option v-for="option in options" :key="option.id" :value="option.id">
                {{ option.name }}
            </option>
        </slot>
    </select>
</template>
