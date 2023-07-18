<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import {ref} from "vue";
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/Habits/Card.vue";

const props = defineProps({
    habits: Array,
})

let selectedHabit = ref(0);
let habit = props.habits[selectedHabit.value]

let monthData = JSON.parse(habit.occurrence_days)

let week = [
    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
]
const createString = () => {
    let occurrences = JSON.parse(habit.occurrence_days)
    const daysOfWeek = occurrences.map(number => {
        const index = (number - 1) % 7; // Adjust the index to match the day names array
        return week[index];
    });

    return daysOfWeek.join(', ');
}

</script>

<template>
    <Head title="Habits" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="grid grid-cols-3 mx-12">
                <div class="p-4">
                    <Card>
                        <template #heading>
                            <span class="h-fit py-2 text-2xl"> Your Habits </span>
                        </template>
                        <template #content>
                            <div v-if="habits.length != 0">
                                <div class="mx-2 p-4" v-for="(habit, index) in habits">
                                    <div
                                        @click="selectedHabit = index"
                                        class="rounded-md border border-black px-2 py-4 cursor-pointer"
                                        :class="`${index==selectedHabit ? 'bg-indigo-400 hover:bg-indigo-500' : 'bg-gray-300 hover:bg-gray-400'}`"
                                    >
                                        {{ habit.name }}
                                    </div>
                                </div>
                            </div>
                            <div v-else class="mx-2 p-4 flex justify-center">
                                <a :href="route('habit.create')">
                                    <PlusCircleIcon class="w-12 h-12 flex hover:text-gray-500"/>
                                </a>
                            </div>
                        </template>
                    </Card>
                </div>
                <div class="p-4">
                    <Card>
                        <template #heading>
                            <div v-if="habit" class="sm:flex">
                                <span class="w-3/4 h-fit py-2 text-2xl"> {{ habit.name }} </span>
                                <div class="w-1/4 flex justify-end items-center">
                                    <a :href="route('habit.edit', habit)"
                                       class="rounded-lg font-medium border-2 border-gray-400 text-gray-500 p-2"
                                    >
                                        Edit
                                    </a>
                                </div>
                            </div>
                            <div v-else class="text-2xl">
                                No habit selected
                            </div>
                        </template>
                        <template #content>
                            <div  v-if="habit">
                                <div class="p-4">
                                    <span class="font-bold"> Name: </span>
                                    <span> {{ habit.name }} </span>
                                </div>
                                <div class="p-4">
                                    <span class="font-bold"> Description: </span>
                                    <span> {{ habit.description }} </span>
                                </div>
                                <div class="p-4">
                                    <span class="font-bold"> Frequency: </span>
                                    <span> {{ habit.frequency }} </span>
                                </div>
                                <div class="p-4 flex block">
                                    <span class="font-bold mr-2"> Occurrences: </span>
                                    <div v-if="habit.frequency === 'monthly'">
                                        <span> {{ monthData[0] }} </span>
                                    </div>
                                    <div v-else>
                                        <span>
                                            {{ createString() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                Content
                            </div>
                        </template>
                    </Card>
                </div>
                <div class="p-4">
                    <div class="rounded-xl border-2 border-black overflow-hidden h-full p-4">
                        History of habit:
                        - Dates you have checked off
                        - A general statistics sections (Times completed, start, missed days?, streak for the specific habit)
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
