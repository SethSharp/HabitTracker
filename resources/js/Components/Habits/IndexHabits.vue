<script setup>
const props = defineProps({
    habits: Array,
})
</script>

<template>
    <div class="rounded-xl border-2 border-black overflow-hidden">
        <div class="max-w-7xl text-2xl mx-auto sm:px-6 lg:px-8 bg-gray-200 mb-2 p-2">
            Your Habits (5)
        </div>
        <div v-for="habit in habits">
            {{ habit.name }}
        </div>
    </div>
</template>
