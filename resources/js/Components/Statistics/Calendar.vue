<script setup>
import {ChevronRightIcon, ChevronLeftIcon} from "@heroicons/vue/24/outline/index.js";
</script>

<template>
    <div class="flex">
        <ChevronLeftIcon class="w-6 h-6 cursor-pointer" />
        <span> August </span>
        <ChevronRightIcon class="w-6 h-6 cursor-pointer" />
    </div>
    <div class="rounded-xl w-full h-screen shadow-xl p-4">
        <div>
            <h1 class="text-xl font-medium"> Filters: </h1>
            <div class="flex mt-5">
                <div v-for="_ in 5" class="bg-gray-100 rounded-xl h-24 mx-4">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900"> Filter </span>
                    </label>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-7 h-16 text-center">
            <div> Monday </div>
            <div> Tuesday </div>
            <div> Wednesday </div>
            <div> Thursday </div>
            <div> Friday </div>
            <div> Saturday </div>
            <div> Sunday </div>
        </div>
        <div class="grid grid-cols-7 gap-2 gap-y-2 text-center bg-gray-300">
            <div v-for="_ in 30" class="bg-gray-100 rounded-xl h-24"> Day </div>
        </div>
    </div>
</template>
