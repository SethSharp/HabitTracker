<script setup>
import { useSchema, FormBuilder } from '@codinglabsau/inertia-form-builder'
import { Password } from '@codinglabsau/ui'

const schema = useSchema({
  current_password: {
    component: Password,
    label: 'Current Password',
    value: '',
  },
  new_password: {
    component: Password,
    label: 'New Password',
    value: '',
  },
})

const submit = () => {
  schema.form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => schema.form.reset(),
    onError: () => {
      if (schema.form.errors.password) {
        schema.form.reset('password', 'password_confirmation')
      }
      if (schema.form.errors.current_password) {
        schema.form.reset('current_password')
      }
      schema.form.reset()
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

    <form class="mt-6 space-y-6" @submit.prevent="submit">
      <FormBuilder :schema="schema" class="!mx-0" />

      <div class="flex items-center gap-4">
        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p v-if="schema.form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
        </Transition>
      </div>
    </form>
  </section>
</template>
