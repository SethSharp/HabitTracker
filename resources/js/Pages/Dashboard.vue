<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import {
    CheckCircleIcon,
    XCircleIcon,
    EllipsisHorizontalCircleIcon,
} from '@heroicons/vue/24/outline/index.js'
import JSConfetti from 'js-confetti'
import Card from '@/Components/Habits/Card.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'
import ScheduledHabitCheckboxGroup from '@/Components/ScheduledHabitCheckboxGroup.vue'

const props = defineProps({
    dailyHabits: Array,
    completedHabits: Array,
    weeklyHabits: Object,
    statistics: Object,
})

const jsConfetti = new JSConfetti()

let today = new Date()
let week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
let isCompleted = ref(false)

let habitConfig = props.dailyHabits.map((h) => {
    return {
        value: h.id,
        label: h.habit.name,
        description: h.habit.description,
        completed: h.completed,
        isGoal: h.habit.scheduled_to,
    }
})

const getCompleted = () => {
    let arr = []
    for (let i = 0; i < habitConfig.length; i++) {
        if (habitConfig[i].completed) {
            arr.push(habitConfig[i].value)
        }
    }
    return arr
}

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

let completed = getCompleted()

let disabled = habitConfig.map((h) => {
    return h.completed ? true : false
})

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

const confetti = () => {
    jsConfetti.addConfetti()
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
    if (props.dailyHabits.length === props.completedHabits.length && props.dailyHabits.length > 0) {
        isCompleted.value = true
        confetti()
    }

    let element = document.getElementById((today.getDay() - 1).toString())
    if (!element) return

    element.scrollIntoView()
})

const form = useForm({
    habits: completed,
})

const submit = () => form.post(route('schedule.update'))
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div>
            <div class="mx-4 sm:mx-12 sm:space-x-6 grid grid-cols-1 sm:grid-cols-2">
                <Card>
                    <template #heading>
                        <span class="h-fit text-2xl"> Today's Habits </span>
                    </template>
                    <template #content>
                        <div class="mx-2">
                            <div>
                                <div v-if="isCompleted"
                                     class="bg-green-300 bg-opacity-25 rounded-md border-2 border-green-200 text-green-600 p-6">
                                    You have ticked off all of your habits for today! Keep it up!
                                </div>
                                <div v-else class="mx-4">
                                    No habits for today, click
                                    <a
                                        class="text-primary text-md font-bold underline pointer-cursor"
                                        :href="route('habit')"
                                    >
                                        here
                                    </a>
                                    to add a habit.
                                </div>
                            </div>
                            <div v-if="dailyHabits.length > 0" class="pl-2 mb-4">
                                <form @submit="submit">
                                    <div class="mb-4">

                                        <ScheduledHabitCheckboxGroup
                                            id="habits"
                                            ref="habits"
                                            v-model="form.habits"
                                            :items="habitConfig"
                                            :disabled="disabled"
                                        />

                                        <InputError :error="form.errors.habits" class="mt-2" />
                                    </div>
                                    <PrimaryButton type="submit"> Save </PrimaryButton>
                                </form>
                            </div>
                        </div>
                    </template>
                </Card>
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
