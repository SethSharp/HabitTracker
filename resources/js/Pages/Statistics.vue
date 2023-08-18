<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline/index.js'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Calendar from '@/Components/Statistics/Calendar.vue'

const props = defineProps({
    habitsByDay: Object,
    habits: Array,
    month: String,
})

let habitFilters = []
let startId = props.habits.length

for (let i = 0; i < props.habits.length; i++) {
    habitFilters.push({
        id: props.habits[i].id,
        title: props.habits[i].name,
        attributePath: 'habit.id',
        filterBy: props.habits[i].id,
        colour: props.habits[i].colour,
    })
}

let calendarSchema = {
    month: props.month,
    days: props.habitsByDay,
    filters: [
        ...habitFilters,
        {
            id: startId,
            title: 'Filter by completed',
            attributePath: 'completed',
            filterBy: 1,
        },
    ],
}

const getAdjacentMonth = (offset) => {
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

    const currentIndex = months.findIndex((month) => month === props.month)
    const newIndex = (currentIndex + offset + 12) % 12

    return months[newIndex]
}
</script>

<template>
    <Head title="Statistics" />

    <AuthenticatedLayout>
        <div class="py-12 mx-12">
            <div class="flex">
                <Link :href="route('statistics', getAdjacentMonth(-1))">
                    <ChevronLeftIcon class="w-7 h-7 mt-0.5" />
                </Link>
                <button class="border border-black p-1 rounded-lg">{{ month }}</button>
                <Link :href="route('statistics', getAdjacentMonth(1))">
                    <ChevronRightIcon class="w-7 h-7 mt-0.5" />
                </Link>
            </div>
            <Calendar :calendarSchema="calendarSchema" />
        </div>
    </AuthenticatedLayout>
</template>
