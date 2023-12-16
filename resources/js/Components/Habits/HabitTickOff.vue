<script setup>
import JSConfetti from 'js-confetti'
import { router } from '@inertiajs/vue3'
import { XCircleIcon, CheckCircleIcon } from '@heroicons/vue/24/outline/index.js'

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
        router.post(
            route('schedule.complete', habit.id),
            {},
            {
                onSuccess: (res) => {
                    habit.completed = true
                    isAllCompleted()
                },
                onError: (err) => {
                    console.error(err)
                    alert('Unable to complete habit')
                },
            }
        )
    }
}

const uncompleteHabit = (habit) => {
    if (habit.completed) {
        router.post(
            route('schedule.uncomplete', habit.id),
            {},
            {
                onSuccess: (res) => {
                    habit.completed = false
                    isAllCompleted()
                },
                onError: (err) => {
                    console.error(err)
                    alert('Unable to un-complete habit')
                },
            }
        )
    }
}

const jsConfetti = new JSConfetti()
</script>

<template>
    <div>
        <div>
            <div
                v-for="scheduledHabit in habits"
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
                        v-if="!scheduledHabit.cancelled"
                        class="rounded-2xl cursor-pointer bg-white border border-primary ml-2 flex overflow-hidden w-auto"
                    >
                        <div
                            class="w-fit p-2 active:bg-red-400"
                            :class="!scheduledHabit.completed ? 'bg-red-100' : 'bg-red-50'"
                        >
                            <XCircleIcon
                                @click="uncompleteHabit(scheduledHabit)"
                                class="w-8 h-8 my-auto text-red-500"
                            />
                        </div>
                        <div
                            class="w-fit p-2 hover:bg-green-200"
                            :class="scheduledHabit.completed ? 'bg-green-100' : ''"
                        >
                            <CheckCircleIcon
                                @click="completeHabit(scheduledHabit)"
                                class="w-8 h-8 my-auto text-green-500"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
