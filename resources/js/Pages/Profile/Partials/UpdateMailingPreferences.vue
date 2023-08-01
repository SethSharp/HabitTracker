<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import InputLabel from "@/Components/InputLabel.vue"
import InputError from "@/Components/InputError.vue"
import Checkbox from "@/Components/Checkbox.vue";

let form = useForm({
    daily_reminder: false,
})

const submit = () => form.patch(route('profile.update'))
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update Mailing Preferences</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's mailing preferences for events you want to be notified about.
            </p>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="submit">
            <div>
                <div class="py-2">
                    <Checkbox
                        id="daily_reminder"
                        ref="daily_reminder"
                        v-model="form.daily_reminder"
                        label="Daily Reminder (5:00pm)"
                    />

                    <InputError :error="form.errors.daily_reminder" class="mt-2" />
                </div>
            </div>

            <PrimaryButton as="button" type="submit"> Submit </PrimaryButton>

            <div class="flex items-center gap-4">
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
