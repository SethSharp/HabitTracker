<script setup>
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";

</script>

<template>
    <div class="rounded-xl border-2 border-black overflow-hidden">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-200 mx-2 my-2 py-2 rounded-xl border-2 border-gray-500 flex block">
            <span class="h-fit py-2 text-2xl"> Your Habits </span>
            <div>
                <a :href="route('habit.create')">
                    <PlusCircleIcon class="w-12 h-12 flex justify-end h-fit py-2"/>
                </a>
            </div>
        </div>
        <div class="min-h-[650px] max-h-[950px] overflow-y-auto">
            <slot></slot>
        </div>
    </div>
</template>
