<script setup lang="ts">
import { ChevronRightIcon, ChevronLeftIcon } from '@heroicons/vue/24/outline/index.js'
import { onMounted, ref } from 'vue'

type Habit = {
    name: string
    colour: string
    completed: number
}

type Filter = {
    id: number
    title: string
    apply: (habits: []) => []
    applied: {
        type: boolean
        default: false
    }
    colour: {
        type: string
        default: ''
    }
    attributePath: string
    filterBy: number
}

// TODO: Look into creating a type which matches what we need
type CalendarSchema = {
    days: []
    filters: Filter[]
}

const props = defineProps({
    calendarSchema: Object as () => CalendarSchema,
})

let appliedFilters = ref(props.calendarSchema.filters.map((filter) => filter.applied))
let selectedFilters: Filter[] = []
let filteredHabits = ref(props.calendarSchema.days)
let year = new Date().getFullYear()
let month = new Date().getMonth() + 1

let getFirstDayOfTheMonth = (year, month) => {
    const firstDayOfMonth = new Date(year, month - 1, 1)
    return firstDayOfMonth.getDay() - 1
}

const removeFilter = (filterId, index) => {
    selectedFilters = selectedFilters.filter((filter) => filter.id !== filterId)
    appliedFilters.value[index] = false
    applySelectedFilters()
}
const addFilter = (filterId, index) => {
    selectedFilters.push(props.calendarSchema.filters[index])
    appliedFilters.value[index] = true
    applySelectedFilters()
}

const applySelectedFilters = () => {
    if (selectedFilters.length === 0) {
        filteredHabits.value = props.calendarSchema.days
        return
    } else {
        let filteredData = [...props.calendarSchema.days]
        selectedFilters.forEach((filterItem) => {
            filteredData = filteredData.map((dayHabits) =>
                dayHabits.filter((scheduledHabit) => {
                    const attributeSegments = filterItem.attributePath.split('.')
                    let habit = scheduledHabit
                    for (const segment of attributeSegments) {
                        habit = habit[segment]
                        if (habit === undefined) {
                            return false
                        }
                    }
                    return habit === filterItem.filterBy
                })
            )
        })
        filteredHabits.value = filteredData
    }
}

onMounted(() => {
    props.calendarSchema.filters.forEach((filter, index) => {
        if (filter.applied) {
            addFilter(filter.id, index)
        }
    })
})
</script>

<template>
    <div class="flex">
        <ChevronLeftIcon class="w-6 h-6 cursor-pointer" />
        <span> August </span>
        <ChevronRightIcon class="w-6 h-6 cursor-pointer" />
    </div>
    <div class="rounded-xl w-full h-screen shadow-xl p-4">
        <div>
            <h1 class="text-xl font-medium">Filters:</h1>
            <div class="flex mt-5">
                <div
                    v-for="(filter, index) in calendarSchema.filters"
                    class="bg-gray-100 rounded-xl h-24 mx-4"
                >
                    <label class="relative inline-flex items-center">
                        <input type="checkbox" value="" class="sr-only peer" />
                        <button
                            @click="
                                appliedFilters[index]
                                    ? removeFilter(filter.id, index)
                                    : addFilter(filter.id, index)
                            "
                            class="p-2 rounded-md border border-black hover:bg-gray-200"
                            :class="{ 'bg-primary': appliedFilters[index] }"
                        >
                            {{ appliedFilters[index] ? 'Remove' : 'Apply' }}
                        </button>
                        <span class="ml-3 text-sm font-medium text-gray-900">
                            {{ filter.title }}
                        </span>
                        <span
                            v-if="filter.colour"
                            class="ml-1 w-4 h-4 rounded-full"
                            :style="`background-color: ${filter.colour}`"
                        ></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-7 mb-2 text-center">
            <div
                v-for="day in [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                    'Sunday',
                ]"
                class=""
            >
                {{ day }}
            </div>
        </div>
        <div
            class="grid grid-cols-7 gap-2 gap-y-2 text-center bg-gray-300 p-2 border border-gray-300 rounded-xl"
        >
            <div v-for="_ in getFirstDayOfTheMonth(year, month)"></div>
            <div v-for="(day, index) in filteredHabits" class="bg-gray-100 rounded-xl h-32">
                <div class="flex justify-end pr-2 pt-1">{{ index + 1 }}</div>
                <div v-for="scheduledHabit in day.slice(0, 5)">
                    <div
                        class="ml-2 w-4 h-4 rounded-full"
                        :style="`background-color: ${scheduledHabit.habit.colour}`"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>
