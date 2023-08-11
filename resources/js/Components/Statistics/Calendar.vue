<script setup lang="ts">
import { ChevronRightIcon, ChevronLeftIcon } from "@heroicons/vue/24/outline/index.js";
import {calculateActiveIndex} from "@headlessui/vue/dist/utils/calculate-active-index";

type Habit = {
    name: string;
    colour: string;
    completed: number;
};

type Filter = {
    title: string;
    apply: (habits: Habit[]) => Habit[];
};

// TODO: Look into creating a type which matches what we need
type CalendarSchema = {
    days: [];
    filters: Filter[];
};

const props = defineProps({
    calendarSchema: Object as () => CalendarSchema
})

console.log(props.calendarSchema.days)

let selectedFilters: Filter[] = [];
let filteredHabits = props.calendarSchema.days

let year = 2023
let month = 8
let getFirstDayOfTheMonth = (year, month) => {
    const firstDayOfMonth = new Date(year, month - 1, 1);
    const dayOfWeek = firstDayOfMonth.getDay();
    return dayOfWeek-1;
}

const removeFilter = (filterIndex) => {
    console.log('removing filter')
    selectedFilters.splice(filterIndex, 1);
    applySelectedFilters()
}
const addFilter = (filterIndex) => {
    selectedFilters.push(props.calendarSchema.filters[filterIndex]);
    applySelectedFilters()
}

const applySelectedFilters = () => {
    console.log('applying')
    selectedFilters.forEach(filter => {
        filteredHabits.map(habit => {
            let x = filter.apply(habit)
        });
    });
    console.log(filteredHabits)
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
                <div v-for="filter in calendarSchema.filters" class="bg-gray-100 rounded-xl h-24 mx-4">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="w-11 h-6 bg-primaryOpacity peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-primary after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
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
