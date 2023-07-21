<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { useSchema, FormBuilder } from "@codinglabsau/inertia-form-builder"
import Card from "@/Components/Habits/Card.vue"
import CheckboxGroup from "@/Components/CheckboxGroup.vue"

const props = defineProps({
    schedule: Array,
})

const getHabitConfig = () => {
    return props.schedule.map(h => {
        return {
            value: h.habit.id,
            label: h.habit.name,
            description: h.habit.description,
        }
    })
}

const schema = useSchema({
    habits: {
        component: CheckboxGroup,
        label: 'Check off habits',
        items: getHabitConfig(),
        description: "This can be changed through the day"
    }
})
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
                        <div v-if="schedule.length > 0" class="pl-2">
                            <FormBuilder :schema="schema" />
                        </div>
                        <div v-else>
                            None for today
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
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
