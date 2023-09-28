<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import About from '@/Pages/About.vue'
</script>

<template>
    <Head title="Help" />

    <AuthenticatedLayout>
        <About />
    </AuthenticatedLayout>
</template>
