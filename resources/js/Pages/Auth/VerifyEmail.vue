<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow-md space-y-4 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Verify Your Email</h2>

            <p class="text-sm text-gray-600">
                Thanks for signing up! Please verify your email address by clicking the link we just emailed you. <br />
                Didn’t receive the email? We’ll send another.
            </p>

            <div
                v-if="verificationLinkSent"
                class="p-3 rounded bg-green-100 text-green-800 text-sm font-medium"
            >
                A new verification link has been sent to the email address you provided.
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <PrimaryButton
                    class="w-full justify-center"
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    Resend Verification Email
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-full text-sm text-red-600 hover:text-red-800 mt-2 underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
                >
                    Log Out
                </Link>
            </form>
        </div>
    </GuestLayout>
</template>