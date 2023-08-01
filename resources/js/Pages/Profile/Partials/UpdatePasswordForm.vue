<script setup>
import { useForm } from '@inertiajs/vue3'
import DangerButton from '@/Components/Buttons/DangerButton.vue'
import Error from '@/Components/Error.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'

const form = useForm({
    current_password: '',
    password: '',
})

const submit = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password')
            }
            if (form.errors.current_password) {
                form.reset('current_password')
            }
            console.log('error')
        },
    })
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update Password</h2>

            <p class="mt-1 text-sm text-gray-600">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="submit" class="w-1/2 mt-5">
            <div class="py-2">
                <InputLabel for="current_password"> Current Password </InputLabel>

                <TextInput
                    id="current_password"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full"
                />

                <Error :error="form.errors.current_password" class="mt-2" />
            </div>
            <div class="py-2">
                <InputLabel for="password"> New Password </InputLabel>

                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                />

                <Error :error="form.errors.password" class="mt-2" />
            </div>
            <DangerButton as="button" class="mt-5" type="submit" :disabled="form.processing">
                Change Password
            </DangerButton>
        </form>
        <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
        >
            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
        </Transition>
    </section>
</template>
