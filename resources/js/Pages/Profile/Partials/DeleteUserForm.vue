<script setup>
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue'
import { useForm } from '@inertiajs/vue3'
import { nextTick, ref } from 'vue'
import { useSchema, FormBuilder } from '@codinglabsau/inertia-form-builder'
import { DangerButton, SecondaryButton, Password } from '@codinglabsau/ui'

const confirmingUserDeletion = ref(false)
const passwordInput = ref(null)

const schema = useSchema({
    password: {
        component: Password,
        label: 'password',
        value: '',
    },
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
        Once your account is deleted, all of its resources and data will be permanently deleted.
        Before deleting your account, please download any data or information that you wish to
        retain.
      </p>
    </header>

    <div class="flex justify-end">
        <DangerButton as="button" @click="confirmUserDeletion">Delete Account</DangerButton>
    </div>

    <Modal :show="confirmingUserDeletion" @close="closeModal">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
          Are you sure you want to delete your account?
        </h2>

        <p class="mt-1 text-sm text-gray-600">
          Once your account is deleted, all of its resources and data will be permanently deleted.
          Please enter your password to confirm you would like to permanently delete your account.
        </p>

          <FormBuilder :schema="schema" class="!w-full">
              <template #actions="{ form }">
                  <div class="space-x-2">
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
              </template>
          </FormBuilder>
      </div>
    </Modal>
  </section>
</template>
