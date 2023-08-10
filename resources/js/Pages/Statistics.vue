<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import Calendar from "@/Components/Statistics/Calendar.vue";

const props = defineProps({
    monthlyHabits: Object
})

const test = (habits) => {
    habits.filter(habit => habit.completed !== 0)
}

let calendarSchema = {
    // should have a name, colour property only, when sending to stats, should create toCalendarArray on the model which returns the name and colour of scheduled
    // habits for the habits
    days: props.monthlyHabits,
    filters: [
        {
            title: "Filter by completed",
            apply: (habits) => {
                console.log('data')
            }
        }
    ]
}

</script>

<template>
    <Head title="Statistics" />

    <AuthenticatedLayout>
        <div class="py-12 mx-12">
            <Calendar :calendarSchema="calendarSchema"/>
        </div>
    </AuthenticatedLayout>
</template>
