<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import {useSchema, FormBuilder, CheckboxGroup} from "@codinglabsau/inertia-form-builder";
import { Text, Select, Date, PrimaryButton, Container } from "@codinglabsau/ui";

const props = defineProps({
    frequencies: Array,
    min: String,
    max: String
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
        label: 'Day/s: Daily, Day: Weekly habits',
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
            min: props.min,
            max: props.max
        },
        description: "This field is only used when making a monthly habit, the same goes for the list of days above (Only being used with daily or weekly habits)"
    },
})

const submit = () => schema.form.post(route('habit.store'))
</script>

<template>
    <Head title="Create Habit" />

    <AuthenticatedLayout>
        <Container class="py-12">
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
                        <PrimaryButton as="button" :loading="form.processing" type="submit">
                            Create Habit
                        </PrimaryButton>
                    </template>
                </FormBuilder>
            </form>
        </Container>
    </AuthenticatedLayout>
</template>
