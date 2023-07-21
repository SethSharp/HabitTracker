<script setup>
import { computed } from "vue"
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import {Checkbox} from "@codinglabsau/ui"

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
})

const emit = defineEmits(['update:modelValue'])

const internalValue = computed({
  get: () => {
    return props.modelValue || [];
  },
  set: (value) => {
    emit('update:modelValue', value);
  },
})
</script>

<template>
    <div v-for="(item, index) in items" class="cursor-pointer flex py-2">
        <Checkbox
            :id="item.label ?? item"
            :key="index"
            v-model="internalValue"
            :value="item.value ?? item"
            class="!w-10 !h-10 !text-green-500 !rounded-full !hover:bg-gray-200 !focus:ring-green-500"
        />
        <div class="w-full">
            <Menu>
                <MenuButton class="px-2 flex justify-start text-sm w-full cursor-pointer">
                    <label class="py-2 cursor-pointer"> {{ item.label }}</label>
                </MenuButton>
                <MenuItems>
                    <MenuItem v-slot="{ active }">
                        <div class="text-gray-500 pl-6 py-2">
                            {{ item.description }}
                        </div>
                    </MenuItem>
                </MenuItems>
            </Menu>
        </div>

    </div>

    <div class="text-gray-500 text-xs mt-3"> {{ description }} </div>
</template>
