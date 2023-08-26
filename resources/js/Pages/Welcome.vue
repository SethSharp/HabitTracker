<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import About from '@/Pages/About.vue'

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
})
</script>

<template>
    <Head title="Welcome" />

    <div class="relative min-h-screen bg-gray-100">
        <div v-if="canLogin">
            <AuthenticatedLayout>
                <About />
            </AuthenticatedLayout>
        </div>
        <div v-else>
            <div class="mx-auto px-4 sm:px-6 lg:px-8 bg-gray-200">
                <div class="flex justify-start py-4">
                    <div class="flex text-lg h-fit">
                        <Link :href="route('welcome')">
                            <ApplicationLogo class="block fill-current text-gray-800" />
                        </Link>
                    </div>
                </div>
            </div>
            <About />
        </div>
    </div>
</template>
