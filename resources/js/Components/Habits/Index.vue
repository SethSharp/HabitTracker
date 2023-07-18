<template>
    <div class="rounded-xl border-2 border-black overflow-hidden">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-200 mx-4 my-4 py-2 rounded-xl border-2 border-gray-500">
            <span class="h-fit py-2 text-2xl"> Your Habits </span>
        </div>
        <div class="min-h-[650px] max-h-[950px] overflow-y-auto">
            <slot></slot>
        </div>
    </div>
</template>
