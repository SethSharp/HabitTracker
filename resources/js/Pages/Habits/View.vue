<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import IndexHabits from "@/Components/Habits/IndexHabits.vue";
import ShowHabit from "@/Components/Habits/Show.vue";
import {ref} from "vue";

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
                    <IndexHabits>
                        <div class="mx-2 p-4" v-for="(habit, index) in habits">
                            <div
                                @click="selectedHabit = index"
                                class="rounded-md border border-black px-2 py-4 cursor-pointer"
                                :class="`${index==selectedHabit ? 'bg-indigo-400 hover:bg-indigo-500' : 'bg-gray-300 hover:bg-gray-400'}`"
                            >
                                {{ habit.name }}
                            </div>
                        </div>
                    </IndexHabits>
                </div>
                <div class="p-4">
                    <ShowHabit :habit="habits[selectedHabit]"/>
                </div>
                <div class="p-4">
                    <div class="rounded-xl border-2 border-black overflow-hidden h-full">
                        Activity Log...
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
