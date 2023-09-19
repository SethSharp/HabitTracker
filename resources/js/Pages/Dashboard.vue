<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import {
    CheckCircleIcon,
    XCircleIcon,
    EllipsisHorizontalCircleIcon,
} from '@heroicons/vue/24/outline/index.js'
import Card from '@/Components/Habits/Card.vue'
import HabitTickOff from "@/Components/Habits/HabitTickOff.vue";

const props = defineProps({
    dailyHabits: Array,
    completedHabits: Array,
    weeklyHabits: Object,
    statistics: Object,
})

let today = new Date()
let week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']

const calculateX = (habit) => {
    let scheduled = new Date(habit.scheduled_completion)

    if (scheduled.getDate() < today.getDate()) {
        return habit.completed === 0
    }
}

const calculateCheck = (habit) => {
    let scheduled = new Date(habit.scheduled_completion)

    if (scheduled.getDate() < today.getDate()) {
        return habit.completed !== 0
    }

    if (scheduled.getDate() === today.getDate()) {
        if (habit.completed === 1) return true
    }
}

const calculateGray = (habit) => {
    let scheduled = new Date(habit.scheduled_completion)

    if (scheduled.getDate() < today.getDate()) return

    if (scheduled.getDate() === today.getDate()) {
        if (habit.completed === 0) return true
    }

    if (scheduled.getDate() > today.getDate()) return true
}

const isSuccess = (habits) => {
    if (habits.length === 0) return false

    for (const obj of Object.values(habits)) {
        if (obj.completed === 0) return false
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

const shouldShowDay = (habit) => {
    if (habit.deleted_at === null) return true

    let scheduledDate = new Date(habit.scheduled_completion)
    return today.getDate() > scheduledDate.getDate()
}

const getDaySuffix = (day) => {
    if (day >= 11 && day <= 13) {
        return "th";
    }
    switch (day % 10) {
        case 1:
            return "st";
        case 2:
            return "nd";
        case 3:
            return "rd";
        default:
            return "th";
    }
}

const dateHelper = (dateString) => {
    let date = new Date(dateString).getDate()
    return date + getDaySuffix(date);
}

onMounted(() => {
    let element = document.getElementById((today.getDay() - 1).toString())
    if (!element) return

    element.scrollIntoView()
})

const submit = () => form.post(route('schedule.update'))
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div>
            <div class="mx-4 sm:mx-12 sm:space-x-6 grid grid-cols-1 sm:grid-cols-2">
                <HabitTickOff />
                <Card>
                    <template #heading>
                        <span class="h-fit text-2xl"> Statistics </span>
                    </template>
                    <template #content>
                        <div class="mx-4 my-2">
                            <div class="flex">
                                <span class="font-bold"> Streak : {{ statistics.streak }} </span>
                                <CheckCircleIcon class="w-6 h-6 ml-1 text-yellow-500" />
                            </div>
                            <div class="flex">
                                <span class="font-bold"> Best Streak : {{ statistics.bestStreak }} </span>
                                <CheckCircleIcon class="w-6 h-6 ml-1 text-green-500" />
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
            <div class="mx-4 mt-4 sm:mx-12">
                <Card>
                    <template #heading>
                        <span class="h-fit py-2 text-2xl"> Your Week </span>
                    </template>
                    <template #content>
                        <div class="flex overflow-x-auto space-x-8 sm:mx-4">
                            <Card
                                v-for="(habits, index, i) in weeklyHabits"
                                class="min-w-[300px]"
                                :success="isSuccess(habits)"
                                :warning="isWarning(habits)"
                                :danger="isDanger(habits)"
                                :heading="today.getDay() === i"
                                :id="index"
                            >
                                <template #heading>
                                    <span> {{ week[i] }} - {{ dateHelper(index) }} </span>
                                </template>
                                <template #content>
                                    <div class="min-h-[450px]">
                                        <ul v-for="habit in habits" class="list-disc p-4">
                                            <li v-show="shouldShowDay(habit)" class="flex">
                                                <XCircleIcon
                                                    v-show="calculateX(habit)"
                                                    class="w-5 h-5 mr-1 mt-0.5 text-red-600"
                                                />
                                                <CheckCircleIcon
                                                    v-show="calculateCheck(habit)"
                                                    class="w-5 h-5 mr-1 mt-0.5 text-green-600"
                                                />
                                                <EllipsisHorizontalCircleIcon
                                                    v-show="calculateGray(habit)"
                                                    class="w-5 h-5 mr-1 mt-0.5 text-gray-600"
                                                />
                                                <span> {{ habit.habit.name }} </span>
                                            </li>
                                        </ul>
                                    </div>
                                </template>
                            </Card>
                        </div>
                    </template>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
