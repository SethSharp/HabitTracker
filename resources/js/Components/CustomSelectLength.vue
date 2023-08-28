<script setup>
import { computed } from 'vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'

const Option = {
    id: '',
    name: '',
}

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
    id: String,
    options: Array,
    label: String,
})

const emit = defineEmits(['update:modelValue'])

const proxySelected = computed({
    get() {
        return props.modelValue.length
    },

    set(selected) {
        emit('update:modelValue', {
            length: selected,
            time: proxyTime.value,
        })
    },
})

const proxyTime = computed({
    get() {
        return props.modelValue.time
    },

    set(time) {
        emit('update:modelValue', {
            length: proxySelected.value,
            time: time,
        })
    },
})
</script>

<template>
    <div class="bg-red-50 flex w-3/4 sm:w-1/2">
        <TextInput id="name" ref="name" v-model="proxyTime" class="mt-1 block w-full" />
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
    </div>

    <label class="text-gray-500">
        {{ label }}
    </label>
</template>
