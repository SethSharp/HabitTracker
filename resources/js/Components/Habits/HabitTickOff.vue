<script setup>
import JSConfetti from 'js-confetti'
import { router } from '@inertiajs/vue3'
import { XMarkIcon, CheckIcon } from '@heroicons/vue/24/outline/index.js'

const props = defineProps({
    habits: Object,
})

const isAllCompleted = () => {
    for (let i = 0; i < props.habits.length; i++) {
        if (!props.habits[i].completed) {
            if (!props.habits[i].cancelled) {
                return
            }
        }
    }
    jsConfetti.addConfetti()
}

const completeHabit = (habit) => {
    if (!habit.completed) {
        axios
            .post(route('schedule.complete', habit.id))
            .then((res) => {
                habit.completed = true
                isAllCompleted()
            })
            .catch((err) => {
                alert('There was an error completing your habit!')
            })
    }
}

const jsConfetti = new JSConfetti()
</script>

<template>
    <div class="flex-wrap w-full">
        <div v-for="scheduledHabit in habits">
            <div
                v-if="!scheduledHabit.completed || scheduledHabit.cancelled"
                class="flex my-2 p-2 justify-between rounded border"
            >
                <p class="text-md my-auto">
                    {{ scheduledHabit.habit.name }}
                </p>

                <div class="justify-end flex gap-x-2">
                    <div
                        v-if="!scheduledHabit.cancelled"
                        class="cursor-pointer ml-2 flex overflow-hidden w-auto"
                    >
                        <div
                            @click="completeHabit(scheduledHabit)"
                            class="w-fit p-2 !text-green-500 transition"
                        >
                            complete
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-for="scheduledHabit in habits">
            <div
                v-if="scheduledHabit.completed"
                class="flex my-2 p-2 justify-between rounded border bg-green-200 border-green-400"
            >
                <p class="text-md my-auto">
                    {{ scheduledHabit.habit.name }}
                </p>
            </div>
        </div>
    </div>
</template>
