<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps({
    habits: Array,
})
</script>

<template>
    <Head title="Habits" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">This is everything for habits</div>
            <div v-for="habit in habits">
                {{ habit.name }}
            </div>
        </div>
    </AuthenticatedLayout>
</template>
