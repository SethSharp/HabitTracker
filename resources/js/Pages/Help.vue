<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import About from "@/Pages/About.vue";
</script>

<template>
    <Head title="Help" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="">
                <div class="w-full flex justify-center">
                    <div class="sm:w-1/2 px-6">
                        <h1 class="text-2xl font-bold"> How to use Habit Tracker </h1>
                        <div>
                            Before we start it is important to know some of the features of this app to prevent
                            any confusion.
                        </div>
                    </div>
                </div>
                <About />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
