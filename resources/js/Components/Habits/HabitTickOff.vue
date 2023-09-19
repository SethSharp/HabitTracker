<script setup>
import Card from "@/Components/Habits/Card.vue";
import {ChevronRightIcon, ChevronLeftIcon} from "@heroicons/vue/24/outline/index.js";
import {ref} from "vue";
import JSConfetti from 'js-confetti'

const props = defineProps({
    scheduledHabits: {
        type: Array,
    },
    date: {
        type: Number,
        default: new Date().getDate(),
        required: false,
    },
})

const currentDate = ref(props.date)
const jsConfetti = new JSConfetti()

const prevDay = () => {

}

const nextDay = () => {

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
