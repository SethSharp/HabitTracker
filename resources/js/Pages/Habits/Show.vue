<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { PlusCircleIcon } from '@heroicons/vue/24/outline/index.js'
import Card from '@/Components/Habits/Card.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import Completed from "@/Components/Log/Completed.vue";

let props = defineProps({
    habits: Array,
    log: Object,
})

const getInitId = () => {
    if (Object.keys(props.log).length > 0) {
        return Object.keys(props.log)[0]
    }
    return null
}

let selectedHabit = ref(0)
let selectedId = getInitId()
let selectedLog = ref(props.log[selectedId])

let habit = ref(props.habits[selectedHabit.value])

let monthData = () => {
    return JSON.parse(habit.value.occurrence_days)
}

let week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']

const createString = () => {
    let occurrences = JSON.parse(habit.value.occurrence_days)
    const daysOfWeek = occurrences.map((number) => {
        const index = number % 7 // Adjust the index to match the day names array
        return week[index]
    })

    return daysOfWeek.join(', ')
}

const timeLeftHelper = (days) => {
    if (days <= 7) {
        return days + ' Day' + (days > 1 ? 's' : '') + ' left'
    }

    if (days > 7) {
        let count = days / 7

        if (count < 2) return '1 Week left'

        return Math.round(count) + ' Weeks left'
    }
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
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 sm:mx-12">
                <div class="p-4">
                    <Card class="min-h-[600px] max-h-[600px]">
                        <template #heading>
                            <div class="flex">
                                <span class="w-3/4 h-fit py-2 text-2xl"> Your Habits </span>
                                <div class="w-1/4 flex justify-end items-center">
                                    <SecondaryButton
                                        @click="this.$inertia.visit(route('habit.create'))"
                                        class="rounded-lg font-medium border-2 border-gray-400 text-gray-500 p-2 hover:bg-gray-400"
                                    >
                                        Create
                                    </SecondaryButton>
                                </div>
                            </div>
                        </template>
                        <template #content>
                            <div>
                                <div
                                    v-if="habits.length != 0"
                                    class="overflow-y-auto max-h-[500px]"
                                >
                                    <div v-for="(habit, index) in habits">
                                        <div
                                            @click="selectedUser(habit.id, index)"
                                            class="rounded-md border border-black px-2 py-4 my-4 cursor-pointer"
                                            :class="`${
                                                index == selectedHabit
                                                    ? 'bg-primary hover:bg-primaryOpacity'
                                                    : 'bg-gray-200 hover:bg-primary'
                                            }`"
                                        >
                                            {{ habit.name }}
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="mx-2 p-4 flex justify-center">
                                    <a :href="route('habit.create')">
                                        <PlusCircleIcon
                                            class="w-16 h-16 flex hover:text-gray-500"
                                        />
                                    </a>
                                </div>
                            </div>
                        </template>
                    </Card>
                </div>
                <div class="p-4">
                    <Card class="min-h-[600px] max-h-[600px]">
                        >
                        <template #heading>
                            <div v-if="habit" class="flex">
                                <span class="w-3/4 truncate h-fit py-2 text-2xl">
                                    {{ habit.name }}
                                </span>
                                <div class="w-fit flex justify-end items-center">
                                    <SecondaryButton
                                        v-if="habit.deleted_at === null"
                                        @click="this.$inertia.visit(route('habit.edit', habit))"
                                        class="rounded-lg font-medium border-2 bg-gray-200 border-gray-400 text-gray-500 p-2 hover:bg-gray-400"
                                    >
                                        Edit
                                    </SecondaryButton>
                                </div>
                            </div>
                            <div v-else class="text-2xl">No habit selected</div>
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
                                    <div class="p-4 flex">
                                        <span class="font-bold"> Colour: </span>
                                        <div
                                            class="ml-2 w-6 h-6 rounded-lg"
                                            :style="`background-color: ${habit.colour}`"
                                        ></div>
                                    </div>
                                    <div class="p-4">
                                        <span class="font-bold"> Days left of goal: </span>
                                        <span>
                                            {{
                                                habit.scheduled_to
                                                    ? timeLeftHelper(habit.days_left)
                                                    : 'No goal set'
                                            }}
                                        </span>
                                    </div>
                                </div>
                                <div v-else class="text-center">No habit selected</div>
                            </div>
                        </template>
                    </Card>
                </div>
                <div class="p-4">
                    <Card class="min-h-[600px] max-h-[600px]">
                        >
                        <template #heading>
                            <span class="h-fit py-2 text-2xl"> Habit Log </span>
                        </template>
                        <template #content>
                            <div class="mx-4 overflow-y-auto max-h-[525px]">
                                <div v-if="Object.keys(log).length !== 0">
                                    <div v-if="selectedLog.length !== 0">
                                        <div v-for="schedule in selectedLog">
                                            <Completed
                                                :log="schedule.log_description"

                                            />
                                        </div>
                                    </div>
                                    <div v-else>No habit log available for this habit</div>
                                </div>
                                <div v-else class="text-lg">No habit log available</div>
                            </div>
                        </template>
                    </Card>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
