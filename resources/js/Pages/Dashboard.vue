<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import Card from "@/Components/Habits/Card.vue"
import {ChevronDownIcon, ChevronUpIcon} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    schedule: Array,
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
                        <div v-if="schedule.length > 0">
                            <div v-for="scheduledHabit in schedule" class="p-4 hover:bg-gray-300 rounded-lg">
                                <input
                                    class="w-10 h-10 text-green-500 rounded-full hover:bg-gray-200 focus:ring-green-500"
                                    type="checkbox"
                                />
                                <Menu>
                                    <MenuButton>
                                        <span class="pl-4 text-xl font-medium"> {{ scheduledHabit.habit.name }} </span>
                                    </MenuButton>
                                    <MenuItems>
                                        <MenuItem v-slot="{ active }">
                                            <div class="text-gray-500 pl-10 py-2">
                                                {{ scheduledHabit.habit.description}}
                                            </div>
                                        </MenuItem>
                                    </MenuItems>
                                </Menu>
                            </div>
                        </div>
                        <div v-else>
                            None for today
                        </div>
                    </template>
                </Card>
                <div>

                </div>
                <Card>
                    <template #heading>
                        <span class="h-fit py-2 text-2xl"> Habit Log  </span>
                    </template>
                </Card>
            </div>
            <div class="mx-12 mt-6">
                <Card>
                    <template #heading>
                        <span class="h-fit py-2 text-2xl"> The Current Week  </span>
                    </template>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
