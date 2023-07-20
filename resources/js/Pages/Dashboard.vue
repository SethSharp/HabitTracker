<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import Card from "@/Components/Habits/Card.vue"
import {ChevronDownIcon} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    habits: Array,
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 mx-12">
                <Card>
                    <template #heading>
                        <span class="h-fit py-2 text-2xl"> Today's Habits  </span>
                    </template>
                    <template #content>
                        <div v-if="habits.length > 0">
                            <div v-for="habit in habits">
                                <input
                                    class="w-10 h-10 text-green-500 rounded-full hover:bg-gray-200 focus:ring-green-500"
                                    type="checkbox"
                                />
                                <span class="pl-2 text-xl"> {{ habit.name }} </span>
                                <button>
                                    <ChevronDownIcon class="w-8 h-8"/>
                                </button>
                            </div>
                        </div>
                        <div v-else>
                            None for today
                        </div>
                    </template>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
