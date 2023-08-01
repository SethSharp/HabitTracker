<script setup>
import Modal from '@/Components/Modal.vue'
import { nextTick, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import DangerButton from '@/Components/Buttons/DangerButton.vue'
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue'
import Error from '@/Components/Error.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'

const confirmingUserDeletion = ref(false)
const passwordInput = ref(null)

const form = useForm({
    password: '',
})

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true
    nextTick(() => passwordInput.value.focus())
}

const submit = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    })
}

const closeModal = () => {
    confirmingUserDeletion.value = false
    form.reset()
}
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>

            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will be permanently
                deleted. Before deleting your account, please download any data or information that
                you wish to retain.
            </p>
        </header>

        <div class="flex justify-start">
            <DangerButton as="button" @click="confirmUserDeletion">Delete Account</DangerButton>
        </div>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data will be permanently
                    deleted. Please enter your password to confirm you would like to permanently
                    delete your account.
                </p>

                <form @submit.prevent="submit" class="w-1/2 mt-5">
                    <div class="py-2">
                        <InputLabel for="password"> Password </InputLabel>

                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                        />

                        <Error :error="form.errors.password" class="mt-2" />
                    </div>
                    <div class="space-x-2 mt-4">
                        <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="submit"
                        >
                            Delete Account
                        </DangerButton>
                    </div>
                </form>
            </div>
        </Modal>
    </section>
</template>
