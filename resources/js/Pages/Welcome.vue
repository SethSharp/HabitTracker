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
                        <Link :href="route('dashboard')">
                            <ApplicationLogo class="block fill-current text-gray-600" />
                        </Link>
                        <div class="py-4 px-4">
                            <Link
                                :href="route('login')"
                                class="font-semibold text-gray-800 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="ml-4 font-semibold text-gray-800 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm"
                            >
                                Register
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <About />
        </div>
    </div>
</template>
