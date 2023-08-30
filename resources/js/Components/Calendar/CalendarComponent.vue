<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { ChevronDownIcon, ChevronUpIcon } from "@heroicons/vue/24/solid"
import SecondaryButton from "@/Components/Buttons/SecondaryButton.vue";

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
let closed = ref(true)
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
            <div>
                <div class="my-4 flex">
                    <SecondaryButton class="text-2xl font-medium" @click="closed = ! closed">
                        Filters:
                        <ChevronDownIcon v-if="closed" class="w-6 h-6 ml-2 my-auto" />
                        <ChevronUpIcon v-if="! closed" class="w-6 h-6 ml-2 my-auto" />
                    </SecondaryButton>
                </div>
                <div
                    class="text-gray-500"
                    :class="{'hidden' : closed}"
                >
                    <div class="my-5 grid grid-cols-2 sm:grid-cols-4 gap-y-2 sm:gap-x-6">
                        <div
                            v-for="(filter, index) in calendarSchema.filters"
                            class="bg-gray-100 rounded-xl sm:mx-4 flex items-center p-2"
                        >
                            <input
                                type="checkbox"
                                class="my-0.5 h-8 w-8 rounded-full border-2 border-primary text-primary hover:bg-primary hover:bg-opacity-25 focus:ring-transparent"
                                @change="
                                    appliedFilters[index]
                                        ? removeFilter(filter.id, index)
                                        : addFilter(index)
                                "
                            />

                            <div class="ml-3 flex">
                                <label class="block text-sm font-medium leading-5 text-gray-700">
                                    {{ filter.title }}
                                </label>
                                <div
                                    v-if="filter.colour"
                                    class="my-auto w-4 h-4 p-2 ml-1 rounded-full"
                                    :style="`background-color: ${filter.colour}`"
                                ></div>
                            </div>
                        </div>
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
                    {{ day }}
                </div>
                <div class="sm:hidden">
                    {{ day.slice(0, 3) }}
                </div>
            </div>
        </div>
        <div
            class="grid grid-cols-7 gap-2 gap-y-2 text-center bg-gray-200 p-2 border border-gray-300 rounded-xl"
        >
            <div v-for="_ in getFirstDayOfTheMonth()"></div>
            <div
                v-for="(day, index) in filteredHabits"
                @click="selectedDay = index"
                class="bg-gray-100 rounded-xl h-12 sm:h-32 overflow-hidden hover:bg-gray-200 cursor-pointer animation duration-300"
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
                <div>
                    <div class="hidden sm:block flex mx-0.5">
                        <div
                            v-for="scheduledHabit in day.slice(0, 5)"
                            class="w-4 h-4 rounded-full"
                            :style="`background-color: ${scheduledHabit.habit.colour}`"
                        ></div>
                    </div>
                    <div class="sm:hidden">
                        <div class="flex">
                            <div
                                v-for="scheduledHabit in day.slice(0, 2)"
                                class="mx-0.5 w-3 h-3 rounded-full"
                                :style="`background-color: ${scheduledHabit.habit.colour}`"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 mx-4" v-if="selectedDay && filteredHabits[selectedDay].length">
            <h1 class="text-2xl">Habits for the {{ selectedDay + getFirstDayOfTheMonth() - 1 }}</h1>
            <div
                v-for="scheduledHabit in filteredHabits[selectedDay]"
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
