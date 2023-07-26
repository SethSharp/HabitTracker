<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import {onBeforeMount, onMounted, ref} from "vue";
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";
import Card from "@/Components/Habits/Card.vue";

const props = defineProps({
    habits: Array,
    log: Object,
})

const getInitId = () => {
    if (Object.keys(props.log).length > 0) {
        return Object.keys(props.log)[0]
    }
    return null
}

let selectedHabit = ref(0);
let selectedId = getInitId()
let selectedLog = ref(props.log[selectedId])

let habit = ref(props.habits[selectedHabit.value])

let monthData = () => {
    return JSON.parse(habit.value.occurrence_days)
}

let week = [
    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
]

const createString = () => {
    let occurrences = JSON.parse(habit.value.occurrence_days)
    const daysOfWeek = occurrences.map(number => {
        const index = (number - 1) % 7; // Adjust the index to match the day names array
        return week[index];
    });

    return daysOfWeek.join(', ');
}

const dateHelper = (date) => {
    let d = new Date(date)
    return `${d.getDate()}` + `/` + `${d.getMonth()}`
}

const selectedUser = (id, index) => {
    selectedHabit.value = index
    habit.value = props.habits[index]
    selectedId = id
    selectedLog.value = props.log[id]
}
</script>

<template>
    <Head title="Habits" />

    <AuthenticatedLayout>
        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 mx-12">
                <div class="p-4">
                    <Card
                        class="min-h-[600px] max-h-[600px]">
                    >
                        <template #heading>
                            <div class="sm:flex">
                                <span class="w-3/4 h-fit py-2 text-2xl"> Your Habits </span>
                                <div class="w-1/4 flex justify-end items-center">
                                    <a :href="route('habit.create')"
                                       class="rounded-lg font-medium border-2 border-gray-400 text-gray-500 p-2 hover:bg-gray-300"
                                    >
                                        Create
                                    </a>
                                </div>
                            </div>
                        </template>
                        <template #content>
                            <div>
                                <div v-if="habits.length != 0" class="overflow-y-auto max-h-[500px]">
                                    <div v-for="(habit, index) in habits">
                                        <div
                                            @click="selectedUser(habit.id, index)"
                                            class="rounded-md border border-black px-2 py-4 my-4 cursor-pointer"
                                            :class="`${index==selectedHabit ? 'bg-indigo-400 hover:bg-indigo-500' : 'bg-gray-300 hover:bg-gray-400'}`"
                                        >
                                            {{ habit.name }}
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="mx-2 p-4 flex justify-center">
                                    <a :href="route('habit.create')">
                                        <PlusCircleIcon class="w-16 h-16 flex hover:text-gray-500"/>
                                    </a>
                                </div>
                            </div>
                        </template>
                    </Card>
                </div>
                <div class="p-4">
                    <Card
                        class="min-h-[600px] max-h-[600px]">
                    >
                        <template #heading>
                            <div v-if="habit" class="sm:flex">
                                <span class="w-3/4 h-fit py-2 text-2xl"> {{ habit.name }} </span>
                                <div class="w-1/4 flex justify-end items-center">
                                    <a :href="route('habit.edit', habit)"
                                       class="rounded-lg font-medium border-2 border-gray-400 text-gray-500 p-2 hover:bg-gray-300"
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
                            <div>
                                <div v-if="habit" class="overflow-y-auto max-h-[525px]">
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
                                            <span> {{ monthData()[0] }} </span>
                                        </div>
                                        <div v-else>
                                        <span>
                                            {{ createString() }}
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-center">
                                    No habit selected
                                </div>
                            </div>

                        </template>
                    </Card>
                </div>
                <div class="p-4">
                    <Card
                        class="min-h-[600px] max-h-[600px]">
                    >
                        <template #heading>
                            <span class="h-fit py-2 text-2xl"> Habit Log </span>
                        </template>
                        <template #content>
                            <div class="mx-4 overflow-y-auto max-h-[525px]">
                                <div v-if="Object.keys(log).length !== 0">
                                    <div v-if="selectedLog.length !== 0">
                                        <div v-for="schedule in selectedLog">
                                            <div
                                                class="rounded-md border border-black px-2 py-4 my-4 cursor-pointer"
                                                :class="schedule.completed === 0
                                                    ? 'bg-red-300 border border-red-300 bg-opacity-25 rounded-md p-4 hover:bg-red-200'
                                                    : 'bg-green-300 border border-green-300 bg-opacity-25 rounded-md p-4 hover:bg-green-200'"
                                            >
                                                {{ schedule.habit.name }}
                                                {{ schedule.completed ? 'completed on' : 'was not completed on' }}
                                                {{ dateHelper(schedule.scheduled_completion) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else>
                                        No habit log available for this habit
                                    </div>
                                </div>
                                <div v-else class="text-lg">
                                    No habit log available
                                </div>
                            </div>
                        </template>
                    </Card>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
