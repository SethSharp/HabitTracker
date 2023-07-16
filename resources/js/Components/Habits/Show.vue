<script setup>
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
const props = defineProps({
    habit: Object,
})
</script>

<template>
    <div class="rounded-xl border-2 border-black h-full overflow-hidden">
        <div v-if="habit">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-200 mx-2 my-2 py-2 rounded-xl border-2 border-gray-500 md:flex">
                <span class="w-3/4 h-fit py-2 text-2xl"> {{ habit.name }} </span>
                <div class="w-1/4 flex justify-end items-center">
                    <span class="rounded-lg border-2 border-gray-400 text-gray-500 p-2"> edit </span>
                </div>
            </div>
        </div>
        <div v-else class="flex items-center justify-center h-full w-full hover:bg-gray-300 cursor-pointer">
            <div class="text-center">
                <div class="mx-auto my-auto text-xl text-gray-500 flex">
                    <span class="my-auto"> Create a habit </span>
                    <a :href="route('habit.create')">
                        <PlusCircleIcon class="w-12 h-12 flex justify-end h-fit py-2"/>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
