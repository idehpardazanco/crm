<script setup>

import {useForm} from '@inertiajs/vue3'


const props = defineProps({

roles:Array

})


const form = useForm({

name:'',
mobile:'',
email:'',
password:'',
status:'active',
role:'employee'

})



function submit(){

form.post('/users')

}

</script>



<template>

<div class="p-6">


<h1 class="text-xl mb-5">
ایجاد کاربر
</h1>


<form
@submit.prevent="submit"
>


<input
v-model="form.name"
placeholder="نام"
class="border p-2 block mb-3"
/>


<input
v-model="form.mobile"
placeholder="موبایل"
class="border p-2 block mb-3"
/>


<input
v-model="form.email"
placeholder="ایمیل"
class="border p-2 block mb-3"
/>


<input
v-model="form.password"
type="password"
placeholder="رمز"
class="border p-2 block mb-3"
/>


<select
v-model="form.role"
class="border p-2 block mb-3"
>


<option
v-for="role in roles"
:key="role"
:value="role"
>
{{role}}
</option>


</select>



<button
class="bg-green-600 text-white px-5 py-2"
>
ذخیره
</button>


</form>


</div>

</template>