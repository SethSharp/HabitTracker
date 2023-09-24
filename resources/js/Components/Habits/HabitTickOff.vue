<script setup>
import Card from "@/Components/Habits/Card.vue";
import {
    ChevronRightIcon,
    ChevronLeftIcon,
    XCircleIcon,
    CheckCircleIcon,
    EllipsisHorizontalCircleIcon
} from "@heroicons/vue/24/outline/index.js";
import {onMounted, ref} from "vue";
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
            console.log(response.data)
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
            scheduledHabits.value = response.data
        })
        .catch(error => {
            console.error(error)
            alert('An error occurred fetching data')
        })
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
                v-for="scheduledHabit in scheduledHabits"
                class="flex my-2 justify-between"
            >
                <p class="text-xl my-auto">
                    {{ scheduledHabit.habit.name }}
                </p>
                <div class="rounded-2xl cursor-pointer border border-primary ml-2 flex overflow-hidden w-auto">
                    <div
                        class="w-fit p-2 hover:bg-gray-200"
                        :class="! scheduledHabit.completed ? 'bg-gray-200' : ''"
                    >
                        <EllipsisHorizontalCircleIcon
                            class="w-8 h-8 my-auto text-gray-500"
                        />
                    </div>
                    <div
                        class="w-fit p-2 hover:bg-green-200"
                        :class="scheduledHabit.completed ? 'bg-green-200' : ''"
                    >
                        <CheckCircleIcon
                            class="w-8 h-8 my-auto text-green-500"
                        />
                    </div>
                </div>
                <XCircleIcon class="w-8 h-8 text-red-500 my-auto" />
            </div>
        </template>
    </Card>
</template>
