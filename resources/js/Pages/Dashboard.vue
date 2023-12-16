<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import Card from '@/Components/Habits/Card.vue'
import { dayNameFromDate, getDateFromDate } from '@/helpers.js'
import HabitTickOff from '@/Components/Habits/HabitTickOff.vue'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline/index.js'

const props = defineProps({
    weeklyHabits: Object,
    date: {
        type: String,
        default: null,
    },
})

const currentDate = ref(null)
const startOfTheWeek = ref(null)
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

const getSunday = (theD) => {
    const today = new Date(theD)

    if (today.getDay() === 0) {
        return theD
    }

    const dayOfWeek = today.getDay()
    const difference = today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 0) // Adjust for Sunday
    const sunday = new Date(today.setDate(difference))

    return buildDate(sunday)
}

const previousSunday = () => {
    let prevDate = new Date(currentDate.value)

    // Calculate days until previous Sunday
    const daysUntilSunday = (prevDate.getDay() - 0 + 7) % 7

    // If the current day is already Sunday, subtract 7 days to get the previous Sunday
    const daysToSubtract = daysUntilSunday === 0 ? 7 : daysUntilSunday

    prevDate.setDate(prevDate.getDate() - daysToSubtract)

    return buildDate(prevDate)
}

const nextSunday = () => {
    let nextDate = new Date(currentDate.value)

    // Calculate days until next Sunday
    const daysUntilSunday = (7 - nextDate.getDay()) % 7

    // If the current day is already Sunday, add 7 days to get the next Sunday
    const daysToAdd = daysUntilSunday === 0 ? 7 : daysUntilSunday

    nextDate.setDate(nextDate.getDate() + daysToAdd)

    return buildDate(nextDate)
}

const nextWeek = () => {
    router.visit(route('dashboard', { week: nextSunday() }))
}

const previousWeek = () => {
    router.visit(route('dashboard', { week: previousSunday() }))
}

onMounted(() => {
    if (props.date) {
        currentDate.value = props.date
    } else {
        currentDate.value = buildDate(new Date())
    }

    startOfTheWeek.value = getSunday(currentDate.value)

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
                    </template>

                    <div class="flex w-full mx-8">
                        <div class="w-1/2">
                            Today's date: {{ dayNameFromDate(currentDate) }}
                            {{ getDateFromDate(currentDate) }}
                        </div>

                        <div class="w-1/2 flex justify-end mr-14">
                            <div class="flex items-center space-x-4">
                                <!-- Previous Week Button -->
                                <button @click="previousWeek">
                                    <ChevronLeftIcon class="w-6 h-6" />
                                </button>

                                <span class="font-semibold"
                                    >{{ dayNameFromDate(startOfTheWeek) }} the
                                    {{ getDateFromDate(startOfTheWeek) }}
                                </span>

                                <!-- Next Week Button -->
                                <button @click="nextWeek">
                                    <ChevronRightIcon class="w-6 h-6" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <template #content>
                        <div>
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
                                        <span>
                                            {{ dayNameFromDate(index) }} -
                                            {{ getDateFromDate(index) }}
                                        </span>
                                    </template>
                                    <template #content>
                                        <HabitTickOff :habits="habits" />
                                    </template>
                                </Card>
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
