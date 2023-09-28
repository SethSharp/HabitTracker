<script setup>
import Card from '@/Components/Habits/Card.vue'
import {
    ChevronRightIcon,
    ChevronLeftIcon,
    XCircleIcon,
    CheckCircleIcon,
} from '@heroicons/vue/24/outline/index.js'
import { onMounted, ref } from 'vue'
import JSConfetti from 'js-confetti'
import axios from 'axios'

let buildDate = (date) => {
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    return `${year}-${month}-${day}`
}

const getDaySuffix = (day) => {
    if (day >= 11 && day <= 13) {
        return 'th'
    }
    switch (day % 10) {
        case 1:
            return 'st'
        case 2:
            return 'nd'
        case 3:
            return 'rd'
        default:
            return 'th'
    }
}

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

const getReadableDate = (date) => {
    return (
        date.getDate() +
        '' +
        getDaySuffix(date.getDate()) +
        ' of ' +
        months[date.getMonth()].substring(0, 3)
    )
}

const previousDate = () => {
    const prevDate = new Date(currentDate.value)
    prevDate.setDate(prevDate.getDate() - 1)

    displayDate.value = getReadableDate(prevDate)

    const newDate = buildDate(prevDate)
    currentDate.value = newDate

    return newDate
}

const nextDate = () => {
    const nextDate = new Date(currentDate.value)
    nextDate.setDate(nextDate.getDate() + 1)

    displayDate.value = getReadableDate(nextDate)

    const newDate = buildDate(nextDate)
    currentDate.value = newDate

    return newDate
}

const today = () => {
    const date = buildDate(new Date())
    currentDate.value = date

    return date
}

const isAllCompleted = () => {
    for (let i = 0; i < scheduledHabits.value.length; i++) {
        if (!scheduledHabits.value[i].completed) {
            if (!scheduledHabits.value[i].cancelled) {
                return
            }
        }
    }
    jsConfetti.addConfetti()
}

const canEdit = () => {
    let today = new Date()
    let selectedDate = new Date(currentDate.value)

    if (today.getDate() < selectedDate.getDate()) {
        canAdjust.value = false
    } else {
        canAdjust.value = true
    }
}

const prevDay = () => {
    axios
        .get(route('schedule.day', { date: previousDate() }))
        .then((response) => {
            scheduledHabits.value = response.data
            canEdit()
        })
        .catch((error) => {
            console.error(error)
            alert('An error occurred fetching data')
        })
}

const nextDay = () => {
    axios
        .get(route('schedule.day', { date: nextDate() }))
        .then((response) => {
            scheduledHabits.value = response.data
            canEdit()
        })
        .catch((error) => {
            console.error(error)
            alert('An error occurred fetching data')
        })
}

const completeHabit = (id, index) => {
    if (!scheduledHabits.value[index].completed) {
        axios
            .post(route('schedule.complete', { habitSchedule: id }))
            .then((_) => {
                scheduledHabits.value[index].completed = true
                isAllCompleted()
            })
            .catch((err) => {
                console.error(err)
                alert('Unable to complete habit')
            })
    }
}

const uncompleteHabit = (id, index) => {
    if (scheduledHabits.value[index].completed) {
        axios
            .post(route('schedule.uncomplete', { habitSchedule: id }))
            .then((_) => {
                scheduledHabits.value[index].completed = false
            })
            .catch((err) => {
                console.error(err)
                alert('Unable to complete habit')
            })
    }
}

const cancelHabit = (id, index) => {
    if (!scheduledHabits.value[index].cancelled) {
        let conf = confirm(
            'Are you sure you want to cancel this habit? This action cannot be undone'
        )
        if (conf) {
            axios
                .post(route('schedule.cancel', { habitSchedule: id }))
                .then((_) => {
                    scheduledHabits.value[index].cancelled = true
                })
                .catch((err) => {
                    console.error(err)
                    alert('Unable to complete habit')
                })
        }
    }
}

onMounted(() => {
    canEdit()
    displayDate.value = getReadableDate(new Date())
    axios
        .get(route('schedule.day', { date: today() }))
        .then((response) => {
            scheduledHabits.value = response.data
        })
        .catch((error) => {
            console.error(error)
            alert('An error occurred fetching data')
        })
})

let scheduledHabits = ref([])
let currentDate = ref(buildDate(new Date()))
let displayDate = ref('')
const jsConfetti = new JSConfetti()
let canAdjust = ref(true)
</script>

<template>
    <Card>
        <template #heading>
            <div class="flex justify-center">
                <ChevronLeftIcon
                    @click="prevDay()"
                    class="w-8 h-8 text-black border border-gray-300 rounded-lg p-2 hover:bg-gray-200 cursor-pointer"
                />
                <span class="h-fit text-2xl mx-4"> {{ displayDate }} </span>
                <ChevronRightIcon
                    @click="nextDay()"
                    class="w-8 h-8 text-black border border-gray-300 rounded-lg p-2 hover:bg-gray-200 cursor-pointer"
                />
            </div>
        </template>
        <template #content>
            <div
                v-if="scheduledHabits.length"
                v-for="(scheduledHabit, index) in scheduledHabits"
                class="flex my-2 p-2 justify-between rounded border"
                :class="[
                    scheduledHabit.cancelled ? 'bg-red-200 border-red-400' : 'border-gray-400',
                    scheduledHabit.completed ? 'bg-green-200 border-green-400' : '',
                ]"
            >
                <p class="text-xl my-auto">
                    {{ scheduledHabit.habit.name }}
                </p>
                <div class="justify-end flex gap-x-2">
                    <div
                        v-if="!scheduledHabit.cancelled && canAdjust"
                        class="rounded-2xl cursor-pointer bg-white border border-primary ml-2 flex overflow-hidden w-auto"
                    >
                        <div
                            class="w-fit p-2 hover:bg-gray-200"
                            :class="!scheduledHabit.completed ? 'bg-gray-100' : ''"
                        >
                            <XCircleIcon
                                @click="uncompleteHabit(scheduledHabit.id, index)"
                                class="w-8 h-8 my-auto text-gray-500"
                            />
                        </div>
                        <div
                            class="w-fit p-2 hover:bg-green-200"
                            :class="scheduledHabit.completed ? 'bg-green-100' : ''"
                        >
                            <CheckCircleIcon
                                @click="completeHabit(scheduledHabit.id, index)"
                                class="w-8 h-8 my-auto text-green-500"
                            />
                        </div>
                    </div>
                    <!--                    <XCircleIcon-->
                    <!--                        @click="cancelHabit(scheduledHabit.id, index)"-->
                    <!--                        class="w-8 h-8 text-red-500 my-auto cursor-pointer"-->
                    <!--                    />-->
                </div>
            </div>
            <div v-else>
                <div class="mx-2 my-2 sm:mx-8">
                    Hey there! You have no habits for today. Click
                    <a class="text-primary underline" :href="route('habit')"> here </a> to start
                    completing now!
                </div>
            </div>
        </template>
    </Card>
</template>
