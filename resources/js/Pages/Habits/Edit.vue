<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import PickColors from 'vue-pick-colors'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import DangerButton from '@/Components/Buttons/DangerButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextAreaInput from '@/Components/TextAreaInput.vue'
import InputError from '@/Components/InputError.vue'
import DateInput from '@/Components/DateInput.vue'
import Checkbox from '@/Components/Checkbox.vue'
import Select from '@/Components/Select.vue'
import CustomSelectLength from "@/Components/CustomSelectLength.vue";

const props = defineProps({
    habit: Object,
    frequencies: Array,
    min: String,
    max: String,
    goals: Array,
})

let customSelectedConfig = {
    options: [
        { name: 'None', id: props.goals[0] },
        { name: 'Week\\s', id: props.goals[1] },
        { name: 'Month\\s', id: props.goals[2] },
    ]
}

let weekConfig = {
    options: [
        { name: 'Sunday', id: 0 },
        { name: 'Monday', id: 1 },
        { name: 'Tuesday', id: 2 },
        { name: 'Wednesday', id: 3 },
        { name: 'Thursday', id: 4 },
        { name: 'Friday', id: 5 },
        { name: 'Saturday', id: 6 },
    ],
}

let occurrences = JSON.parse(props.habit.occurrence_days)

const getFrequency = () => {
    if (props.habit.frequency === 'weekly') {
        return 1
    } else if (props.habit.frequency === 'monthly') {
        return 2
    }
    return 0
}

const getDaily = () => {
    if (getFrequency() === 0) return occurrences.map(Number)
    return []
}

const getWeekly = () => {
    if (getFrequency() === 1) return occurrences.map(Number)[0]
    return 0
}

const getMonthly = () => {
    if (!isNaN(occurrences[0])) {
        return ''
    }
    return occurrences[0]
}

const form = useForm({
    name: props.habit.name,
    description: props.habit.description,
    frequency: getFrequency(),
    daily_config: getDaily(),
    weekly_config: getWeekly(),
    monthly_config: getMonthly(),
    colour: props.habit.colour,
    scheduled_to: {
        length: 0,
        time: 0,
    },
})

const submit = () => form.post(route('habit.update', props.habit))
const deleteHabit = () => {
    if (window.confirm('Are you sure you want to delete this habit?')) {
        form.delete(route('habit.delete', props.habit))
    }
}
</script>

<template>
    <Head title="Create Habit" />

    <AuthenticatedLayout>
        <div class="bg-gray-100 flex justify-center">
            <form @submit.prevent="submit" class="w-1/2 mt-10 h-screen">
                <div>
                    <div class="py-2">
                        <InputLabel for="name"> Name </InputLabel>

                        <TextInput
                            id="name"
                            ref="name"
                            v-model="form.name"
                            class="mt-1 block w-full"
                        />

                        <InputError :error="form.errors.name" class="mt-2" />
                    </div>
                    <div class="py-2">
                        <InputLabel for="description"> Description </InputLabel>

                        <TextAreaInput
                            id="description"
                            ref="description"
                            v-model="form.description"
                            class="mt-1 block w-full"
                        />

                        <InputError :error="form.errors.description" class="mt-2" />
                    </div>
                    <div class="py-2" v-if="form.frequency === 0">
                        <InputLabel for="daily_config"> Daily </InputLabel>

                        <Checkbox
                            v-for="(item, index) in weekConfig.options"
                            :id="item.name ?? item"
                            :key="index"
                            v-model="form.daily_config"
                            :value="item.id ?? item"
                            :label="item.name ?? item"
                        />

                        <InputError :error="form.errors.daily_config" class="mt-2" />
                    </div>
                    <div class="py-2" v-if="form.frequency === 1">
                        <InputLabel for="weekly_config"> Weekly </InputLabel>

                        <Select
                            v-model="form.weekly_config"
                            v-bind="weekConfig"
                            class="mt-1 block w-full"
                        />

                        <InputError :error="form.errors.weekly_config" class="mt-2" />
                    </div>
                    <div class="py-2" v-if="form.frequency === 2">
                        <InputLabel for="monthly_config"> Monthly </InputLabel>

                        <DateInput
                            v-model="form.monthly_config"
                            v-model:min="props.min"
                            v-model:max="props.max"
                            class="mt-1 block w-full"
                        />

                        <InputError :error="form.errors.monthly_config" class="mt-2" />
                    </div>
                    <div class="py-2">
                        <InputLabel for="colour"> Colour </InputLabel>

                        <PickColors v-model:value="form.colour" />

                        <InputError :error="form.errors.colour" class="mt-2" />
                    </div>
                    <div class="py-2" v-if="! habit.scheduled_to">
                        <InputLabel for="scheduled_to"> Set a goal for this habit </InputLabel>

                        <CustomSelectLength
                            v-model="form.scheduled_to"
                            v-bind="customSelectedConfig"
                            class="mt-1 block w-full"
                            label="Leaving this blank, will continuously schedule these habits at the start of each month (Will not be considered a goal)"
                        />

                        <InputError :error="form.errors.scheduled_to" class="mt-2" />
                    </div>
                </div>

                <div class="flex gap-2">
                    <PrimaryButton
                        as="button"
                        :loading="form.processing"
                        type="submit"
                        class="mt-4"
                    >
                        Save Changes
                    </PrimaryButton>
                    <DangerButton @click.prevent="deleteHabit" class="mt-4"> Delete </DangerButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
