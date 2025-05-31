<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineOptions({ layout: GuestLayout });

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />

    <form @submit.prevent="submit" class="max-w-md mx-auto bg-white p-6 rounded shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-4 text-center text-purple-800">Welcome Back</h1>

        <div class="mb-4">
            <InputLabel for="email" value="Email" />
            <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autofocus />
            <InputError class="mt-2 text-red-600" :message="form.errors.email" />
        </div>

        <div class="mb-4">
            <InputLabel for="password" value="Password" />
            <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required autocomplete="current-password" />
            <InputError class="mt-2 text-red-600" :message="form.errors.password" />
        </div>

        <div class="flex items-center justify-between mb-4 text-sm">
            <label class="flex items-center">
                <input type="checkbox" v-model="form.remember" class="mr-2" />
                Remember me
            </label>
            <Link href="/forgot-password" class="text-gray-600 hover:text-purple-700 underline">Forgot your password?</Link>
        </div>

        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full bg-purple-700 hover:bg-purple-800">
            Log in
        </PrimaryButton>
    </form>
</template>