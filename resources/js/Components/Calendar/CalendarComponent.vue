<script setup lang="ts">
import { onMounted, ref } from 'vue'
import Checkbox from "@/Components/Checkbox.vue";

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

// TODO: Look into creating a type which matches what we need for days
type CalendarSchema = {
    month: String
    days: []
    filters: Filter[]
}

const props = defineProps({
    calendarSchema: Object as () => CalendarSchema,
})

let getDate = (): Date => {
    return new Date(
        new Date().getFullYear(),
        months.indexOf(props.calendarSchema.month),
        new Date().getDate()
    )
}

let getFirstDayOfTheMonth = () => {
    const firstDayOfMonth = new Date(date.getFullYear(), date.getMonth(), 1)
    return firstDayOfMonth.getDay()
}

const removeFilter = (filterId, index) => {
    selectedFilters = selectedFilters.filter((filter) => filter.id !== filterId)
    appliedFilters.value[index] = false
    applySelectedFilters()
}
const addFilter = (index) => {
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

const months = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
]
let appliedFilters = ref(props.calendarSchema.filters.map((filter) => filter.applied))
let selectedFilters: Filter[] = []
let filteredHabits = ref(props.calendarSchema.days)
let selectedDay = ref(0)
const date = getDate()

onMounted(() => {
    props.calendarSchema.filters.forEach((filter, index) => {
        if (filter.applied) {
            addFilter(index)
        }
    })
})
</script>

<template>
    <div class="rounded-xl w-full shadow-xl p-2 sm:p-6">
        <div>
            <h1 class="text-xl font-medium">Filters:</h1>
            <!-- Make this section hide/show -->
            <div class="my-5 grid grid-cols-4 gap-y-2 gap-x-2">
                <div
                    v-for="(filter, index) in calendarSchema.filters"
                    class="bg-gray-100 rounded-xl mx-4 flex items-center p-2"
                >
                    <input
                        type="checkbox"
                        class="my-0.5 h-8 w-8 rounded-full border-2 border-primary text-primary hover:bg-primary hover:bg-opacity-25 focus:ring-transparent"
                        @change="appliedFilters[index]
                                    ? removeFilter(filter.id, index)
                                    : addFilter(index)"
                    />

                    <div class="ml-3 flex">
                        <label class="block text-sm font-medium leading-5 text-gray-700">
                            {{ filter.title }}
                        </label>
                        <div
                            v-if="filter.colour"
                            class="my-auto w-4 h-4 p-2 rounded-full"
                            :style="`background-color: ${filter.colour}`"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-7 mb-2 text-center">
            <div
                v-for="day in [
                    'Sunday',
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                ]"
            >
                <div class="hidden sm:block">
                    {{ day}}
                </div>
                <div class="sm:hidden">
                    {{ day.slice(0, 3)}}
                </div>
            </div>
        </div>
        <div class="grid grid-cols-7 gap-2 gap-y-2 text-center bg-gray-300 p-2 border border-gray-300 rounded-xl">
            <div v-for="_ in getFirstDayOfTheMonth()"></div>
            <div
                v-for="(day, index) in filteredHabits"
                @click="selectedDay = index"
                class="bg-gray-100 rounded-xl h-12 sm:h-32 overflow-hidden hover:bg-gray-200 cursor-pointer"
            >
                <div
                    class="flex justify-end pr-2 pt-1 mb-1 text-xs sm:text-md"
                    :class="{
                        'bg-gray-200':
                            date.getDate() === index + 1 &&
                            months[new Date().getMonth()] === calendarSchema.month,
                    }"
                >
                    {{ index + 1 }}
                </div>
                <div class="flex space-x-1">
                    <div
                        v-for="scheduledHabit in day.slice(0, 5)"
                        class="ml-2 w-4 h-4 rounded-full hidden sm:block"
                        :style="`background-color: ${scheduledHabit.habit.colour}`"
                    ></div>
                    <div
                        v-for="scheduledHabit in day.slice(0, 2)"
                        class="ml-2 w-3 h-3 rounded-full sm:hidden"
                        :style="`background-color: ${scheduledHabit.habit.colour}`"
                    ></div>
                </div>
            </div>
        </div>
        <div class="mt-6 mx-4" v-if="selectedDay && filteredHabits[selectedDay].length">
            <h1 class="text-2xl"> Habits for the {{ selectedDay + getFirstDayOfTheMonth() - 1 }} </h1>
            <div
                v-for="(scheduledHabit, index) in filteredHabits[selectedDay]"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 my-2"
            >
                <div class="rounded-lg border border-gray-300 p-4">
                    <div class="flex">
                        {{ scheduledHabit.habit.name }}
                        <div
                            class="ml-2 w-4 h-4 rounded-full my-auto"
                            :style="`background-color: ${scheduledHabit.habit.colour}`"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
