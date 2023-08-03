<script setup>
import { useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue'
import Checkbox from '@/Components/Checkbox.vue'

const props = defineProps({
    preference: {
        type: Boolean,
    },
})

let form = useForm({
    daily_reminder: Boolean(props.preference),
})

const submit = () => form.patch(route('email-preferences.update'))
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
                        v-model="form.daily_reminder"
                        :value="form.daily_reminder"
                        label="Receive daily reminders (5:00pm) to complete habits"
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
