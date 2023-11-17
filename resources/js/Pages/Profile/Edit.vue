<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import DeleteUserForm from './Partials/DeleteUserForm.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'
import { Head } from '@inertiajs/vue3'
import UpdateMailingPreferences from '@/Pages/Profile/Partials/UpdateMailingPreferences.vue'

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    emailPreferences: {
        type: Boolean,
    },
})
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="py-12 mx-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!--                <div class="p-4 sm:p-8 bg-white shadow rounded-lg">-->
                <!--                    <UpdateProfileInformationForm-->
                <!--                        :must-verify-email="mustVerifyEmail"-->
                <!--                        :status="status"-->
                <!--                        class="max-w-xl"-->
                <!--                    />-->
                <!--                </div>-->

                <!--                <div class="p-4 sm:p-8 bg-white shadow rounded-lg">-->
                <!--                    <UpdateMailingPreferences :preferences="emailPreferences" />-->
                <!--                </div>-->

                <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
                    <UpdatePasswordForm />
                </div>

                <!--                <div class="p-4 sm:p-8 bg-white shadow rounded-lg">-->
                <!--                    <DeleteUserForm />-->
                <!--                </div>-->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
