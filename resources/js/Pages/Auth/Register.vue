<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';//1
import InputLabel from '@/Components/InputLabel.vue';//2
import PrimaryButton from '@/Components/PrimaryButton.vue';//3
import TextInput from '@/Components/TextInput.vue';//4
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue'

defineOptions({ layout: GuestLayout });

const form = useForm({//1
    name: '',
    email: '',
    faculty: '',
    major: '',
    password: '',
    password_confirmation: '',
});

const success = ref(false)

const submit = () => {//2
    success.value = false
    form.post(route('register'), {
        onSuccess: () => {
            success.value = true
        },
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="min-h-screen bg-gradient-to-br from-purple-100 to-white flex items-center justify-center p-4">
        <form @submit.prevent="submit"
              class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-purple-200 animate-fade-in">
            <h1 class="text-3xl font-extrabold mb-6 text-center text-purple-800 tracking-wide">Create an Account</h1>

            <div v-if="success" class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm text-center border border-green-300">
              Registered successfully!
            </div>

            
            <div class="mb-5">
                <InputLabel for="name" value="Full Name" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full focus:ring-purple-400 focus:border-purple-500 transition-all" required autofocus />
                <InputError class="mt-2 text-red-600" :message="form.errors.name" />
            </div>

           
            <div class="mb-5">
                <InputLabel for="email" value="University Email" />
                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full focus:ring-purple-400 focus:border-purple-500 transition-all" required />
                <InputError class="mt-2 text-red-600" :message="form.errors.email" />
            </div>

           
            <div class="mb-5">
                <InputLabel for="faculty" value="College" />
                <select id="faculty" v-model="form.faculty" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-400 focus:border-purple-500 transition-all">
                    <option value="" disabled>Select your college</option>
                    
                    <option>كلية الطب</option>
                     <option>كلية العلوم الطبية التطبيقية</option>
                      <option>كلية الهندسة</option>
                       <option>كلية الصيدلة</option>
                        <option>كلية التمريض</option>
                        <option>كلية طب الأسنان</option>
                    <option>كلية تكنولوجيا معلومات الحاسوب</option>
                  <option>الطب البيطري</option>
                           <option>كلية الزراعة</option>
                           <option>شعبة العلوم العسكرية</option>
                             <option>كلية العلوم والآداب</option>
                              <option>مركز اللغات</option>
                               <option>كلية العمارة والتصميم</option>
                                <option>معهد النانوتكنولوجي</option>

                </select>
                <InputError class="mt-2 text-red-600" :message="form.errors.faculty" />
            </div>

           
            <div class="mb-5">
                <InputLabel for="major" value="Major" />
                <select id="major" v-model="form.major" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-400 focus:border-purple-500 transition-all">
                    <option>دكتور في الطب</option>
<option>الصحة العامة</option>
<option>الإدارة والسياسات الصحية</option>
<option>الإسعافات الأولية والطوارئ</option>
<option>تكنولوجيا الأشعة</option>
<option>العلوم الطبية المخبرية</option>
<option>تكنولوجيا صناعة الأسنان</option>
<option>علوم طب الأسنان والمساندة</option>
<option>البصريات</option>
<option>العلاج الطبيعي</option>
<option>العلاج الوظيفي</option>
<option>السمع والنطق</option>
<option>علوم التأهيل السريري</option>
<option>علاج الأسنان</option>
<option>تكنولوجيا التخدير</option>
<option>الهندسة الكيميائية</option>
<option>الهندسة المدنية</option>
<option>الهندسة الكهربائية</option>
<option>الهندسة الميكانيكية</option>
<option>الهندسة الطبية الحيوية</option>
<option>الهندسة الصناعية</option>
<option>هندسة الطيران</option>
<option>تكنولوجيا صيانة الطائرات</option>
<option>دبلوماسية المياه</option>
<option>الهندسة النووية</option>
<option>الصيدلة</option>
<option>دكتور صيدلة</option>
<option>التصنيع الدوائي والبيولوجي</option>
<option>التمريض</option>
<option>طب وجراحة الأسنان</option>
<option>هندسة الحاسوب</option>
<option>علوم الحاسوب</option>
<option>نظم المعلومات الحاسوبية</option>
<option>هندسة وأمن شبكات الحاسوب</option>
<option>هندسة البرمجيات</option>
<option>الأمن السيبراني</option>
<option>علم البيانات</option>
<option>الذكاء الاصطناعي</option>
<option>إنترنت الأشياء</option>
<option>تصميم وتطوير ألعاب الحاسوب</option>
<option>دكتور في الطب البيطري</option>
<option>الإنتاج الحيواني</option>
<option>الإنتاج النباتي</option>
<option>التغذية وتكنولوجيا الغذاء</option>
<option>الموارد الطبيعية والبيئية</option>
<option>علوم عسكرية</option>
<option>اللغة العربية</option>
<option>اللغة الإنجليزية واللغويات</option>
<option>الرياضيات</option>
<option>الكيمياء</option>
<option>الفيزياء</option>
<option>العلوم الحياتية التطبيقية</option>
<option>التقانات الحيوية والهندسة</option>
<option>مركز اللغات</option>
<option>التخطيط والدراسات الحضرية</option>
<option>العمارة</option>
<option>تخطيط وتصميم المدن</option>
<option>التصميم والتواصل البصري</option>
<option>هندسة التخطيط الحضري والبيئي</option>
<option>هندسة وعلوم النانو</option>
<option>علوم المياه والطاقة والغذاء</option>>
                </select>
                <InputError class="mt-2 text-red-600" :message="form.errors.major" />
            </div>

            <!-- Password -->
            <div class="mb-5">
                <InputLabel for="password" value="Password" />
                <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full focus:ring-purple-400 focus:border-purple-500 transition-all" required />
                <InputError class="mt-2 text-red-600" :message="form.errors.password" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full focus:ring-purple-400 focus:border-purple-500 transition-all" required />
                <InputError class="mt-2 text-red-600" :message="form.errors.password_confirmation" />
            </div>

            <!-- Already Registered -->
            <div class="flex items-center justify-between mb-5 text-sm">
                <Link href="/login" class="text-gray-500 hover:text-purple-600 underline">Already registered?</Link>
            </div>

            <!-- Submit -->
            <PrimaryButton :class="{ 'opacity-25': form.processing }"
                           :disabled="form.processing"
                           class="w-full bg-purple-700 hover:bg-purple-800 transition-all duration-200 flex items-center justify-center">
                <svg v-if="form.processing" class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                Register
            </PrimaryButton>
        </form>
    </div>
</template>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fade-in 0.4s ease-in-out;
}
</style>