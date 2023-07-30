<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import InputLabel from "@/Components/InputLabel.vue"
import InputError from "@/Components/InputError.vue"

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
})

const user = usePage().props.auth.user

let form = useForm({
    name: user.name,
    email: user.email,
})

const submit = () => form.patch(route('profile.update'))
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="submit">
            <div>
                <div class="py-2">
                    <InputLabel for="name"> Username </InputLabel>

                    <TextInput id="name" ref="name" v-model="form.name" class="mt-1 block w-full" />

                    <InputError :error="form.errors.name" class="mt-2" />
                </div>
                <div class="py-2">
                    <InputLabel for="email"> Email </InputLabel>

                    <TextInput
                        id="email"
                        ref="email"
                        v-model="form.email"
                        class="mt-1 block w-full"
                    />

                    <InputError :error="form.errors.email" class="mt-2" />
                </div>
            </div>

            <PrimaryButton as="button" type="submit"> Submit </PrimaryButton>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

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
