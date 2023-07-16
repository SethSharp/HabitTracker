<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import {useSchema, FormBuilder, CheckboxGroup} from "@codinglabsau/inertia-form-builder";
import { Text, Select, Date, PrimaryButton } from "@codinglabsau/ui";
import {InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    frequencies: Array,
})

let schema = useSchema({
    name: Text,
    description: Text,
    frequency: {
        component: Select,
        props: {
            options: props.frequencies,
        },
    },
    daily_weekly_configuration: {
        component: CheckboxGroup,
        label: 'Days for daily or day for weekly habit',
        items: [
            { label: 'Monday', value: 1 },
            { label: 'Tuesday', value: 2 },
            { label: 'Wednesday', value: 3 },
            { label: 'Thursday', value: 4 },
            { label: 'Friday', value: 5 },
        ],
    },
    monthly_configuration: {
        component: Date,
        label: "Select the day for a monthly habit",
        props: {
            min: '2023-06-10',
            max: '2023-08-10'
        }
    },
})

const submit = () => schema.form.post(route('habit.store'))
</script>

<template>
    <Head title="Create Habit" />

    <AuthenticatedLayout>
        <div class="py-12">
            <form @submit.prevent="submit">
                <FormBuilder :schema="schema">
                    <template #actions="{ form }">
                        <div class="text-gray-400">
                            <div>
                                <p>
                                    Weekly: Only select one day</p>
                                <p> Daily: Select any day</p>
                            </div>
                        </div>
                        <PrimaryButton :loading="form.processing" type="submit">
                            Create Habit
                        </PrimaryButton>
                    </template>
                </FormBuilder>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
