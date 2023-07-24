<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import {onMounted, ref} from "vue";
import { useSchema, FormBuilder } from "@codinglabsau/inertia-form-builder"
import Card from "@/Components/Habits/Card.vue"
import CheckboxGroup from "@/Components/CheckboxGroup.vue"
import { ArrowRightIcon } from "@heroicons/vue/24/solid/index.js"
import { CheckCircleIcon, XCircleIcon } from "@heroicons/vue/24/outline/index.js"
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import { PrimaryButton } from "@codinglabsau/ui"

const props = defineProps({
    dailyHabits: Array,
    weeklyHabits: Array,
})

let today = new Date()
let week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

// Dialog logic
const isOpen = ref(false)
const isCompleted = ref(false)

function closeModal() {
    isOpen.value = false
}
function openModal() {
    isOpen.value = true
}

let habitConfig = props.dailyHabits.map(h => {
    return {
        value: h.id,
        label: h.habit.name,
        description: h.habit.description,
        completed: h.completed
    }
});

const getCompleted = () => {
    let arr = []
    for(let i = 0; i < habitConfig.length; i++) {
        if (habitConfig[i].completed) {
            arr.push(habitConfig[i].value)
        }
    }
    return arr
}

const test = (habit, scheduled) => {
    if (scheduled > today) {
        return true
    }
    return habit.completed
}

const calculateX = (habit) => {
    let scheduled = new Date(habit.scheduled_completion)

    if (scheduled.getDate() < today.getDate()) {
        return habit.completed === 0
    }
}

const calculateCheck = (habit) => {
    let scheduled = new Date(habit.scheduled_completion)

    if (scheduled.getDate() < today.getDate()) {
        return habit.completed !== 0
    }

    if (scheduled.getDate() === today.getDate()) {
        if (habit.completed === 1) return true
    }
}

const calculateGray = (habit) => {
    let scheduled = new Date(habit.scheduled_completion)

    if (scheduled.getDate() < today.getDate()) return

    if (scheduled.getDate() === today.getDate()) {
        if (habit.completed === 0) return true
    }

    if (scheduled.getDate() > today.getDate()) return true
}

let completed = getCompleted()

let disabled = habitConfig.map(h => {
    return h.completed ? true : false
})

const isSuccess = (habits) => {
    for (const obj of Object.values(habits)) {
        if (obj.completed === 0) return false
    }
    return true
}

const isWarning = (habits) => {
    let successCount = 0
    let failCount = 0

    for (const obj of Object.values(habits)) {
        if (obj.completed === 0) {
            failCount++
        } else {
            successCount++
        }
    }

    return failCount !== successCount || failCount === successCount
}

const isDanger = (habits) => {
    for (const obj of Object.values(habits)) {
        if (obj.completed === 0) {
            let scheduled = new Date(obj.scheduled_completion)
            return scheduled.getDate() < today.getDate()
        }
    }
    return true
}

onMounted(() => {
    for (const habit of props.dailyHabits) {
        if ( !habit.completed) return;
    }
    isCompleted.value = true
    openModal()
})

const schema = useSchema({
    habits: {
        component: CheckboxGroup,
        label: 'Check off habits',
        items: habitConfig,
        value: completed,
        disabled: disabled,
    }
})

const submit = () => schema.form.post(route('schedule.update'));
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 mx-12">
                <Card>
                    <template #heading>
                        <span class="h-fit pt-2 text-2xl"> Today's Habits  </span>
                    </template>
                    <template #content>
                        <div v-if="dailyHabits.length > 0 && ! isCompleted" class="pl-2 mt-4">
                            <form @submit="submit">
                                <FormBuilder :schema="schema" />
                            </form>
                        </div>
                        <div v-else class="mx-2">
                            <div v-if="isCompleted">
                                You are all done for today!
                            </div>
                            <div v-else>
                                No habits for today, click
                                <a class="text-indigo-500 text-md underline pointer-cursor" :href="route('habits')"> here </a>
                                to add a habit.
                            </div>
                        </div>
                    </template>
                </Card>
                <div>

                </div>
                <Card>
                    <template #heading>
                        <span class="h-fit py-2 text-2xl"> Habit Log  </span>
                    </template>
                </Card>
            </div>
            <div class="mx-12 mt-6">
                <Card>
                    <template #heading>
                        <span class="h-fit py-2 text-2xl"> The Current Week  </span>
                    </template>
                    <template #content>
                        <div class="flex overflow-x-auto space-x-8 mx-4">
                            <Card
                                v-for="(habits, index) in weeklyHabits"
                                class="min-w-[300px] min-h-[500px]"
                                :success="isSuccess(habits)"
                                :warning="isWarning(habits)"
                                :danger="isDanger(habits)"
                            >
                                <template #heading>
                                    <span> {{ week[index] }} </span>
                                </template>
                                <template #content>
                                    <ul v-for="habit in habits" class="list-disc p-4">
                                        <li class="flex">
                                            <XCircleIcon v-show="calculateX(habit)" class="w-5 h-5 mr-1 mt-0.5 text-red-600"/>
                                            <CheckCircleIcon v-show="calculateCheck(habit)" class="w-5 h-5 mr-1 mt-0.5 text-green-600"/>
                                            <XCircleIcon v-show="calculateGray(habit)" class="w-5 h-5 mr-1 mt-0.5 text-gray-600"/>
                                            {{ habit.habit.name }} - {{ habit.id }}
                                        </li>
                                    </ul>
                                </template>
                            </Card>
                        </div>
                        <div class="flex justify-end mx-4">
                            <ArrowRightIcon class="w-8 h-8 text-gray-500"/>
                        </div>
                    </template>
                </Card>
            </div>
        </div>

        <TransitionRoot appear :show="isOpen" as="template">
            <Dialog as="div" @close="closeModal" class="relative z-10">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black bg-opacity-25" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="flex min-h-full items-center justify-center p-4 text-center"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                            >
                                <DialogTitle
                                    as="h3"
                                    class="text-lg font-medium leading-6 text-gray-900"
                                >
                                    Congratulations!!
                                </DialogTitle>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        You have ticked off all of your habits for today!
                                        Now you can relax knowing your achievement, keep it up!
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <PrimaryButton
                                        type="button"
                                        @click="closeModal"
                                    >
                                        Close
                                    </PrimaryButton>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </AuthenticatedLayout>
</template>
