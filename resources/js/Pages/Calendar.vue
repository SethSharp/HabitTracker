<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline/index.js'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CalendarComponent from "@/Components/Calendar/CalendarComponent.vue";
import SecondaryButton from "@/Components/Buttons/SecondaryButton.vue";

const props = defineProps({
    habitsByDay: Object,
    habits: Array,
    month: String,
    habitFilters: Array,
})

let habitFilters = []
let startId = props.habits.length

for (let i = 0; i < props.habitFilters.length; i++) {
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
    <Head title="Calendar" />

    <AuthenticatedLayout>
        <div class="py-12 mx-2 sm:mx-6">
            <div class="flex justify-start sm:justify-end sm:mr-5 space-x-4">
                <Link class="border border-gray-200 rounded-lg hover:bg-gray-200 p-2" :href="route('calendar', getAdjacentMonth(-1))">
                    <ChevronLeftIcon class="w-7 h-7 mt-0.5" />
                </Link>
                <span class="text-xl my-auto">{{ month }}</span>
                <Link class="border border-gray-200 rounded-lg hover:bg-gray-200 p-2" :href="route('calendar', getAdjacentMonth(1))">
                    <ChevronRightIcon class="w-7 h-7 mt-0.5" />
                </Link>
            </div>
            <CalendarComponent :calendarSchema="calendarSchema" />
        </div>
    </AuthenticatedLayout>
</template>
