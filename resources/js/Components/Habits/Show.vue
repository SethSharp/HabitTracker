<script setup>
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {onMounted} from "vue";

const props = defineProps({
    habit: Object,
})

let monthData = JSON.parse(props.habit.occurrence_days)

let week = [
    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
]
const createString = () => {
    let occurrences = JSON.parse(props.habit.occurrence_days)
    const daysOfWeek = occurrences.map(number => {
        const index = (number - 1) % 7; // Adjust the index to match the day names array
        return week[index];
    });

    return daysOfWeek.join(', ');
}
</script>

<template>
    <div class="rounded-xl border-2 border-black h-full overflow-hidden">
        <div v-if="habit" class="mx-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-200 my-2 py-2 rounded-xl border-2 border-gray-500 md:flex">
                <span class="w-3/4 h-fit py-2 text-2xl"> {{ habit.name }} </span>
                <div class="w-1/4 flex justify-end items-center">
                    <a :href="route('habit.edit', habit)"
                        class="rounded-lg font-medium border-2 border-gray-400 text-gray-500 p-2"
                    >
                        Edit
                    </a>
                </div>
            </div>
            <div class="">
                <div class="p-4">
                    <span class="font-bold"> Name: </span>
                    <span> {{ habit.name }} </span>
                </div>
                <div class="p-4">
                    <span class="font-bold"> Description: </span>
                    <span> {{ habit.description }} </span>
                </div>
                <div class="p-4">
                    <span class="font-bold"> Frequency: </span>
                    <span> {{ habit.frequency }} </span>
                </div>
                <div class="p-4 flex block">
                    <span class="font-bold mr-2"> Occurrences: </span>
                    <div v-if="habit.frequency === 'monthly'">
                        <span> {{ monthData[0] }} </span>
                    </div>
                    <div v-else>
                        <span>
                            {{ createString() }}
                        </span>
                    </div>
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
