<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import About from "@/Pages/About.vue";
</script>

<template>
    <Head title="Help" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div>
                    <h1 class="text-2xl font-bold"> How to use Habit Tracker </h1>
                    <div>
                        Before we start it is important to know some of the features of this app to prevent
                        any confusion.
                    </div>
                </div>
                <About />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
