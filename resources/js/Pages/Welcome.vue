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
                <div class="flex-wrap sm:flex sm:flex-nowrap justify-start py-4 w-full">
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('welcome')">
                            <ApplicationLogo
                                class="block w-4 h-full sm:w-auto fill-current text-gray-800"
                            />
                        </Link>
                        <div class="text-4xl p-4">Habit Tracker</div>
                    </div>
                    <div class="my-6 w-full flex sm:justify-end">
                        <Link
                            :href="route('login')"
                            class="font-semibold bg-gray-300 rounded-lg p-2 hover:bg-opacity-50"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="ml-4 font-semibold bg-primary rounded-lg p-2 hover:bg-opacity-50"
                        >
                            Register
                        </Link>
                    </div>
                </div>
            </div>
            <div class="w-full flex mt-8 px-6 justify-center">
                <div class="sm:w-1/2">
                    <h1 class="text-2xl font-bold">Welcome to, Habit Tracker</h1>
                    Habit Tracker is a online application you can use to challenge yourself to
                    follow a daily, weekly or monthly routine. Holding yourself accountable to tick
                    off helpful and productive habits. Habit Tracker allows you to set daily habits
                    repeating on any day of the week. Weekly habits on any select day or a monthly
                    habit to remind you at a set date during the month.
                </div>
            </div>
            <About />
        </div>
    </div>

    <div class="w-full h-fit bg-gray-400 bg-opacity-25 text-black flex justify-center">
        <div class="my-6 text-center">
            <ApplicationLogo class="mx-auto" />
            <h1>
                Habit Tracker Demo
            </h1>
            <div class="mt-4">
                © 2023 Seth Sharp. All rights reserved.
            </div>
        </div>
    </div>
</template>
