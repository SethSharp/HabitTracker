<script setup lang="ts">
import { ChevronRightIcon, ChevronLeftIcon } from "@heroicons/vue/24/outline/index.js";
import {calculateActiveIndex} from "@headlessui/vue/dist/utils/calculate-active-index";
import {ref} from "vue";

type Habit = {
    name: string;
    colour: string;
    completed: number;
};

type Filter = {
    title: string;
    apply: (habits: []) => [];
    applied: boolean
};

// TODO: Look into creating a type which matches what we need
type CalendarSchema = {
    days: [];
    filters: Filter[];
};

const props = defineProps({
    calendarSchema: Object as () => CalendarSchema
})

let appliedFilters = ref(props.calendarSchema.filters.map(filter => filter.applied))
let selectedFilters: Filter[] = [];
let filteredHabits = ref(props.calendarSchema.days)
let year = 2023
let month = 8
let getFirstDayOfTheMonth = (year, month) => {
    const firstDayOfMonth = new Date(year, month - 1, 1);
    const dayOfWeek = firstDayOfMonth.getDay();
    return dayOfWeek-1;
}

const removeFilter = (filterIndex) => {
    selectedFilters.splice(filterIndex, 1);
    appliedFilters.value[filterIndex] = false
    applySelectedFilters()
}
const addFilter = (filterIndex) => {
    selectedFilters.push(props.calendarSchema.filters[filterIndex]);
    appliedFilters.value[filterIndex] = true
    applySelectedFilters()
}

const applySelectedFilters = () => {
    if (selectedFilters.length === 0) {
        filteredHabits.value = props.calendarSchema.days
        return
    } else {
        selectedFilters.forEach(filter => {
            filteredHabits.value = filter.apply(props.calendarSchema.days);
        });
    }
}
</script>

<template>
    <div class="flex">
        <ChevronLeftIcon class="w-6 h-6 cursor-pointer" />
        <span> August </span>
        <ChevronRightIcon class="w-6 h-6 cursor-pointer" />
    </div>
    <button @click="addFilter(0)"> Add filter </button>
    <button @click="removeFilter(0)"> Remove filter </button>
    <div class="rounded-xl w-full h-screen shadow-xl p-4">
        <div>
            <h1 class="text-xl font-medium"> Filters: </h1>
            <div class="flex mt-5">
                <div v-for="(filter, index) in calendarSchema.filters" class="bg-gray-100 rounded-xl h-24 mx-4">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <button
                            @click="appliedFilters[index] ? removeFilter(index) : addFilter(index)"
                            class="p-2 rounded-md"
                            :class="{'bg-primary' : appliedFilters[index]}"
                        >
                            {{ appliedFilters[index] ? "Remove" : "Apply" }}
                        </button>
                        <span class="ml-3 text-sm font-medium text-gray-900"> {{ filter.title }} </span>
                    </label>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-7 mb-2 text-center">
            <div
                v-for="day in ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']"
                class="bg-red-300"
            >
                {{ day }}
            </div>
        </div>
        <div class="grid grid-cols-7 gap-2 gap-y-2 text-center bg-gray-300 p-2 border border-black">
            <div v-for="_ in getFirstDayOfTheMonth(year, month)"></div>
            <div v-for="(day, index) in filteredHabits" class="bg-gray-100 rounded-xl h-24">
                <div class="flex justify-end pr-2 pt-1"> {{ index+1 }} </div>
                <div v-for="scheduledHabit in day">
                    <div
                        class="ml-2 w-4 h-4 rounded-full"
                        :style="`background-color: ${scheduledHabit.habit.colour}`"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>
