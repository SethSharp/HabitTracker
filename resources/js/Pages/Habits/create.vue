<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

</script>

<template>
    <Head title="Create Habit" />

    <AuthenticatedLayout>
        <div class="py-12">
            This is creating a habit
        </div>
    </AuthenticatedLayout>
</template>
