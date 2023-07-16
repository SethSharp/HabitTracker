<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import IndexHabits from "@/Components/Habits/IndexHabits.vue";
import ShowHabit from "@/Components/Habits/ShowHabit.vue";

const props = defineProps({
    habits: Array,
})
</script>

<template>
    <Head title="Habits" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="grid grid-cols-3 mx-12">
                <div class="p-4">
                    <IndexHabits :habits="habits"/>
                </div>
                <div class="p-4">
                    <ShowHabit />
                </div>
                <div>
                    activity log?..
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
