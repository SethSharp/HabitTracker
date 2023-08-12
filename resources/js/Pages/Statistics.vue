<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import Calendar from '@/Components/Statistics/Calendar.vue'

const props = defineProps({
    habitsByDay: Object,
    habits: Array,
})

let habitFilters = []
let startId = props.habits.length

for (let i = 0; i < props.habits.length; i++) {
    habitFilters.push({
        id: i,
        title: 'Habit: ' + props.habits[i].name,
        attributePath: 'habit.id',
        filterBy: props.habits[i].id
    })
}

let calendarSchema = {
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
</script>

<template>
    <Head title="Statistics" />

    <AuthenticatedLayout>
        <div class="py-12 mx-12">
            <Calendar :calendarSchema="calendarSchema" />
        </div>
    </AuthenticatedLayout>
</template>
