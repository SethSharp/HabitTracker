<script setup>
const props = defineProps({
    habits: Array,
})
</script>

<template>
    <div class="rounded-xl border-2 border-black overflow-hidden">
        <div class="max-w-7xl text-2xl mx-auto sm:px-6 lg:px-8 bg-gray-200 mx-2 my-2 p-2 rounded-xl border-2 border-gray-500">
            Your Habits (5)
        </div>
        <div class="min-h-[650px] max-h-[950px] overflow-y-auto">
            <div class="mx-2 p-4" v-for="habit in habits">
                <div class="rounded-md border border-black px-2 py-4">
                    {{ habit.name }}
                </div>
            </div>
        </div>
    </div>
</template>
