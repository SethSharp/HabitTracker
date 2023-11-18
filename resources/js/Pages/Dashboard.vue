<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { onMounted } from 'vue'
import Card from '@/Components/Habits/Card.vue'
import {dayNameFromDate, getDateFromDate} from "@/helpers.js";
import HabitTickOff from "@/Components/Habits/HabitTickOff.vue";

const props = defineProps({
    weeklyHabits: Object,
    statistics: Object,
})

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

onMounted(() => {
    let element = document.getElementById((today.getDay() - 1).toString())
    if (!element) return

    element.scrollIntoView()
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div>
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
