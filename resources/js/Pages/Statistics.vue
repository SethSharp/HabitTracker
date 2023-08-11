<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import Calendar from "@/Components/Statistics/Calendar.vue";

const props = defineProps({
    habitsByDay: Object
})

let calendarSchema = {
    // should have a name, colour property only, when sending to stats, should create toCalendarArray on the model which returns the name and colour of scheduled
    // habits for the habits
    days: props.habitsByDay,
    filters: [
        {
            id: 0,
            title: "Filter by completed",
            apply: (habitsByDay) => {
                return habitsByDay.map(dayHabits => dayHabits.filter(scheduledHabit => scheduledHabit.completed === 1));
            },
            applied: true
        },
        {
            id: 1,
            title: "Filter by colour", //currently hardcoded but will be made dynamic somehow
            apply: (habitsByDay) => {
                return habitsByDay.map(dayHabits => dayHabits.filter(scheduledHabit => scheduledHabit.habit.colour === "#00cedf"));
            },
            applied: false
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
