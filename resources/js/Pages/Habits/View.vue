<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import Index from "@/Components/Habits/Index.vue";
import Show from "@/Components/Habits/Show.vue";
import {ref} from "vue";
import {PlusCircleIcon} from "@heroicons/vue/24/outline/index.js";

let selectedHabit = ref(0);

const props = defineProps({
    habits: Array,
})
</script>

<template>
    <Head title="Habits" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="grid grid-cols-3 mx-12">
                <div class="p-4">
                    <Index>
                        <div v-if="habits.length != 0" class="mx-2 p-4" v-for="(habit, index) in habits">
                            <div
                                @click="selectedHabit = index"
                                class="rounded-md border border-black px-2 py-4 cursor-pointer"
                                :class="`${index==selectedHabit ? 'bg-indigo-400 hover:bg-indigo-500' : 'bg-gray-300 hover:bg-gray-400'}`"
                            >
                                {{ habit.name }}
                            </div>
                        </div>
                        <div v-else class="mx-2 p-4 flex justify-center">
                            <a :href="route('habit.create')">
                                <PlusCircleIcon class="w-12 h-12 flex hover:text-gray-500"/>
                            </a>
                        </div>
                    </Index>
                </div>
                <div class="p-4" v-if="habits.length != 0">
                    <Show :habit="habits[selectedHabit]"/>
                </div>
                <div class="p-4">
                    <div class="rounded-xl border-2 border-black overflow-hidden h-full p-4">
                        History of habit:
                        - Dates you have checked off
                        - A general statistics sections (Times completed, start, missed days?, streak for the specific habit)
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
