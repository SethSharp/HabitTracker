<script setup>
import { BeakerIcon } from '@heroicons/vue/20/solid/index.js'
let user = { name: 'Seth' }
</script>

<template>
  <BeakerIcon
      class="w-10 h-10" />
  <h1>Welcome {{ user.name }}, this is your first inertia app</h1>
</template>
