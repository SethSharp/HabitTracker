<template>
    <div class="rounded-xl border-2 border-black overflow-hidden max-h-[650px]">
        <div class="px-6 lg:px-8 bg-gray-200 mx-4 my-4 py-2 rounded-xl border-2 border-gray-500">
            <slot name="heading"></slot>
        </div>
        <div class="min-h-[650px] max-h-[950px] overflow-y-auto mx-4">
            <slot name="content"></slot>
        </div>
    </div>
</template>
