<script setup>
import Card from "@/Components/Habits/Card.vue";
import {
    ChevronRightIcon,
    ChevronLeftIcon,
    XCircleIcon,
    CheckCircleIcon,
    EllipsisHorizontalCircleIcon
} from "@heroicons/vue/24/outline/index.js";
import { onMounted, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import JSConfetti from 'js-confetti'
import axios from "axios";

let buildDate = (date) => {
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    return `${year}-${month}-${day}`
}

const previousDate = () => {
    const prevDate = new Date(currentDate.value)
    prevDate.setDate(prevDate.getDate() - 1)

    const newDate = buildDate(prevDate)
    currentDate.value = newDate

    return newDate
}

const nextDate = () => {
    const nextDate = new Date(currentDate.value)
    nextDate.setDate(nextDate.getDate() + 1)

    const newDate = buildDate(nextDate)
    currentDate.value = newDate

    return newDate
}

const today = () => {
    const date = buildDate(new Date())
    currentDate.value = date

    return date
}

const prevDay = () => {
    // change date to day before
    axios.get(route('schedule.day', { date: previousDate() }))
        .then(response => {
            scheduledHabits.value = response.data
        })
        .catch(error => {
            console.error(error);
            alert('An error occurred fetching data')
        });
}

const nextDay = () => {
    // change date to next day
    axios.get(route('schedule.day', { date: nextDate() }))
        .then(response => {
            console.log(response.data)
        })
        .catch(error => {
            console.error(error)
            alert('An error occurred fetching data')
        })
}

const completeHabit = (id, index) => {
    axios.post(route('schedule.complete', { habitSchedule: id }))
        .then(_ => {
            scheduledHabits.value[index].completed = true
        })
        .catch(err => {
            console.error(err)
            alert('Unable to complete habit')
        })
}

const uncompleteHabit = (id, index) => {
    axios.post(route('schedule.uncomplete', { habitSchedule: id }))
        .then(_ => {
            scheduledHabits.value[index].completed = false
        })
        .catch(err => {
            console.error(err)
            alert('Unable to complete habit')
        })
}

const cancelHabit = (id, index) => {
    let conf = confirm('Are you sure you want to cancel this habit? This action cannot be undone')
    if (conf) {
        axios.post(route('schedule.cancel', { habitSchedule: id }))
            .then(_ => {
                scheduledHabits.value[index].cancelled = true
            })
            .catch(err => {
                console.error(err)
                alert('Unable to complete habit')
            })
    }
}

onMounted(() => {
    axios.get(route('schedule.day', { date: today() }))
        .then(response => {
            console.log(response.data)
            scheduledHabits.value = response.data
        })
        .catch(error => {
            console.error(error)
            alert('An error occurred fetching data')
        });
})

let scheduledHabits = ref([])
const currentDate = ref(buildDate(new Date()))
const jsConfetti = new JSConfetti()

const confetti = () => {
    jsConfetti.addConfetti()
}
</script>

<template>
    <Card>
        <template #heading>
            <div class="flex gap-x-2">
                <ChevronLeftIcon
                    @click="prevDay()"
                    class="w-8 h-8 text-black border border-gray-300 rounded-lg p-2 hover:bg-gray-200 cursor-pointer"
                />
                <span class="h-fit text-2xl"> Habits for: {{ currentDate }} </span>
                <ChevronRightIcon
                    @click="nextDay()"
                    class="w-8 h-8 text-black border border-gray-300 rounded-lg p-2 hover:bg-gray-200 cursor-pointer"
                />
            </div>
        </template>
        <template #content>
            <div
                v-for="(scheduledHabit, index) in scheduledHabits"
                class="flex my-2 p-2 justify-between rounded border"
                :class="scheduledHabit.cancelled ? 'bg-red-200 border-red-400' : 'border-gray-400'"
            >
                <p class="text-xl my-auto">
                    {{ scheduledHabit.habit.name }}
                </p>
                <div v-if="! scheduledHabit.cancelled" class="rounded-2xl cursor-pointer bg-white border border-primary ml-2 flex overflow-hidden w-auto">
                    <div
                        class="w-fit p-2 hover:bg-gray-200"
                        :class="! scheduledHabit.completed ? 'bg-gray-200' : ''"
                    >
                        <EllipsisHorizontalCircleIcon
                            @click="uncompleteHabit(scheduledHabit.id, index)"
                            class="w-8 h-8 my-auto text-gray-500"
                        />
                    </div>
                    <div
                        class="w-fit p-2 hover:bg-green-200"
                        :class="scheduledHabit.completed ? 'bg-green-200' : ''"
                    >
                        <CheckCircleIcon
                            @click="completeHabit(scheduledHabit.id, index)"
                            class="w-8 h-8 my-auto text-green-500"
                        />
                    </div>
                </div>
                <XCircleIcon
                    @click="cancelHabit(scheduledHabit.id, index)"
                    class="w-8 h-8 text-red-500 my-auto cursor-pointer"
                />
            </div>
        </template>
    </Card>
</template>
