<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import Card from '@/Components/Habits/Card.vue'
import {dayNameFromDate, getDateFromDate} from "@/helpers.js";
import HabitTickOff from "@/Components/Habits/HabitTickOff.vue";

const props = defineProps({
    weeklyHabits: Object,
    date: {
        type: String,
        default: null
    }
})

const currentDate = ref(null)
let today = new Date()
let week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']

const isSuccess = (habits) => {
    if (habits.length === 0) return false

    for (const obj of Object.values(habits)) {
        if (obj.completed === 0) {
            if (!obj.cancelled) {
                return false
            }
        }
    }
    return true
}

const isWarning = (habits) => {
    if (habits.length === 0) return false

    let successCount = 0
    let failCount = 0

    for (const habit of Object.values(habits)) {
        if (habit.completed === 0) {
            failCount++
        } else {
            successCount++
        }

        if (failCount !== 0 || successCount !== 0) {
            let scheduled = new Date(habit.scheduled_completion)
            if (today.getDate() <= scheduled.getDate()) return false
        }
    }

    if (successCount > 0 && failCount === 0) return false

    if (successCount === 0 && failCount > 0) return false

    return failCount !== successCount || failCount === successCount
}

const isDanger = (habits) => {
    if (habits.length === 0) return false

    let successCount = 0
    let failCount = 0

    for (const habit of Object.values(habits)) {
        if (habit.completed === 0) {
            failCount++
        } else {
            successCount++
        }

        if (failCount !== 0 || successCount !== 0) {
            let scheduled = new Date(habit.scheduled_completion)
            if (today.getDate() <= scheduled.getDate()) return false
        }
    }
    return successCount === 0 && failCount > 0
}

let buildDate = (date) => {
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')

    return `${year}-${month}-${day}`
}

const previousMonday = () => {
    let prevDate = new Date(currentDate.value);

    // Calculate days until previous Monday
    const daysUntilMonday = (prevDate.getDay() - 1 + 7) % 7;

    // If the current day is already Monday, subtract 7 days to get the previous Monday
    const daysToSubtract = daysUntilMonday === 0 ? 7 : daysUntilMonday;

    prevDate.setDate(prevDate.getDate() - daysToSubtract);

    const newDate = buildDate(prevDate);
    currentDate.value = newDate;

    return newDate;
};

const nextMonday = () => {
    let nextDate = new Date(currentDate.value);

    // Calculate days until next Monday
    const daysUntilMonday = (1 - nextDate.getDay() + 7) % 7;

    // If the current day is already Monday, add 7 days to get the next Monday
    const daysToAdd = daysUntilMonday === 0 ? 7 : daysUntilMonday;

    nextDate.setDate(nextDate.getDate() + daysToAdd);

    const newDate = buildDate(nextDate);
    currentDate.value = newDate;

    return newDate;
};

const nextWeek = () => {
    router.visit(route('dashboard', { week: nextMonday() }))
}

const previousWeek = () => {
    router.visit(route('dashboard', { week: previousMonday() }))
}

onMounted(() => {
    if (props.date) {
        currentDate.value = props.date
    } else {
        currentDate.value = buildDate(new Date())
    }

    let element = document.getElementById((today.getDay() - 1).toString())
    if (!element) return

    element.scrollIntoView()
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div>
            <div class="mx-4 mt-4 sm:mx-6">
                <Card>
                    <template #heading>
                        <span class="h-fit py-2 text-2xl"> Your Week </span>
                        <span @click="nextWeek" class="h-fit py-2 text-2xl justify-end"> Some data range adjuster </span>
                    </template>
                    <template #content>
                        <div class="flex overflow-x-auto space-x-4">
                            <Card
                                v-for="(habits, index, i) in weeklyHabits"
                                class="min-w-[200px]"
                                :success="isSuccess(habits)"
                                :warning="isWarning(habits)"
                                :danger="isDanger(habits)"
                                :heading="today.getDay() === i"
                                :id="index"
                            >
                                <template #heading>
                                    <span> {{ dayNameFromDate(index) }} - {{ getDateFromDate(index) }} </span>
                                </template>
                                <template #content>
                                   <HabitTickOff :habits="habits"/>
                                </template>
                            </Card>
                        </div>
                    </template>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
