<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextAreaInput from '@/Components/TextAreaInput.vue'
import InputError from '@/Components/InputError.vue'
import DateInput from '@/Components/DateInput.vue'
import Checkbox from '@/Components/Checkbox.vue'
import Select from '@/Components/Select.vue'
import PickColors from 'vue-pick-colors'
import CustomSelectLength from "@/Components/CustomSelectLength.vue";

const props = defineProps({
    frequencies: Array,
    min: String,
    max: String,
})

let frequenciesConfig = {
    options: props.frequencies,
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

let customSelectedConfig = {
    options: [
        { name: 'Day/s', id: 'd' },
        { name: 'Week/s', id: 'w' },
        { name: 'Month/s', id: 'm' },
    ]
}

const form = useForm({
    name: '',
    description: '',
    frequency: 0,
    daily_config: [],
    weekly_config: 0,
    monthly_config: '',
    scheduled_to: {
        length: 'm',
        time: 10,
    },
    start_next_week: false,
    colour: '#00cedf',
})

const submit = () => form.post(route('habit.store'))
</script>

<template>
    <Head title="Create Habit" />

    <AuthenticatedLayout>
        <div class="bg-gray-100 flex justify-center">
            <form @submit.prevent="submit" class="w-1/2 my-10">
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
                    <div class="py-2">
                        <InputLabel for="frequency"> Frequency </InputLabel>

                        <Select
                            v-model="form.frequency"
                            v-bind="frequenciesConfig"
                            class="mt-1 block w-full"
                        />

                        <InputError :error="form.errors.frequency" class="mt-2" />
                    </div>
                    <div class="py-2" v-if="form.frequency === 0">
                        <InputLabel for="daily_config">
                            Schedule at any days in a standard week
                        </InputLabel>

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
                        <InputLabel for="weekly_config">
                            Scheduled at a day in a standard week
                        </InputLabel>

                        <Select
                            v-model="form.weekly_config"
                            v-bind="weekConfig"
                            class="mt-1 block w-full"
                        />

                        <InputError :error="form.errors.weekly_config" class="mt-2" />
                    </div>
                    <div class="py-2" v-if="form.frequency === 2">
                        <InputLabel for="monthly_config">
                            Schedule at a point in this month
                        </InputLabel>

                        <DateInput
                            v-model="form.monthly_config"
                            v-model:min="props.min"
                            v-model:max="props.max"
                            class="mt-1 block w-full"
                        />

                        <InputError :error="form.errors.monthly_config" class="mt-2" />
                    </div>
                    <div class="py-2">
                        <InputLabel for="scheduled_to"> Set a goal for this habit </InputLabel>

                        <CustomSelectLength
                            v-model="form.scheduled_to"
                            v-bind="customSelectedConfig"
                            class="mt-1 block w-full"
                            label=""
                        />

                        <InputError :error="form.errors.scheduled_to" class="mt-2" />
                    </div>
                    <div class="py-2">
                        <InputLabel for="start_next_week"> Start time </InputLabel>

                        <Checkbox
                            v-model="form.start_next_week"
                            :value="form.start_next_week"
                            label="Schedule for next week"
                        />

                        <label class="text-gray-500">
                            If not selected and habit occurs on a day that is already passed it will
                            not be added for that day but will not affect your streak.
                        </label>

                        <InputError :error="form.errors.start_next_week" class="mt-2" />
                    </div>

                    <div class="py-2">
                        <InputLabel for="colour"> Colour </InputLabel>

                        <PickColors v-model:value="form.colour" />

                        <InputError :error="form.errors.colour" class="mt-2" />
                    </div>
                </div>
                <PrimaryButton as="button" :loading="form.processing" type="submit" class="mt-4">
                    Create Habit
                </PrimaryButton>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
