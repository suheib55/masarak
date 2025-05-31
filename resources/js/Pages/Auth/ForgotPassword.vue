<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineOptions({ layout: GuestLayout });

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />

    <form @submit.prevent="submit" class="max-w-md mx-auto bg-white p-6 rounded shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-4 text-center text-purple-800">Reset your password</h1>

        <p class="text-sm text-gray-600 mb-4 text-center">
            Enter your university email and we will send you a link to reset your password.
        </p>

        <div class="mb-4">
            <InputLabel for="email" value="Email" />
            <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autofocus />
            <InputError class="mt-2 text-red-600" :message="form.errors.email" />
        </div>

        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full bg-purple-700 hover:bg-purple-800">
            Send Password Reset Link
        </PrimaryButton>
    </form>
</template>