<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import About from "@/Pages/About.vue";
</script>

<template>
    <Head title="Help" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <About />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
