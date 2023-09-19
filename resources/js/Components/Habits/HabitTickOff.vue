<script setup>
import Card from "@/Components/Habits/Card.vue";
import {ChevronRightIcon, ChevronLeftIcon} from "@heroicons/vue/24/outline/index.js";
import {ref} from "vue";
import JSConfetti from 'js-confetti'
import axios from "axios";

let scheduledHabits = ref([])
const currentDate = ref(props.date)
const jsConfetti = new JSConfetti()

const prevDay = () => {
    // change date to day before
    axios.get(route('schedule.day', { date: "2023-09-19" }))
        .then(response => {
            console.log(response.data);
        })
        .catch(error => {
            console.error(error);
            alert('An error occurred fetching data')
        });
}

const nextDay = () => {
    // change date to next day
    axios.get(route('schedule.day', { date: "2023-09-19" }))
        .then(response => {
            console.log(response.data);
        })
        .catch(error => {
            console.error(error);
            alert('An error occurred fetching data')
        });
}

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
            <div v-for="scheduledHabit in scheduledHabits">
                <span> {{ scheduledHabit.habit.name }} </span>
            </div>
        </template>
    </Card>
</template>
