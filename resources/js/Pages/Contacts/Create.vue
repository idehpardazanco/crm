<script setup>

import {useForm} from '@inertiajs/vue3'


const props = defineProps({

users:Array

})


const form = useForm({

business_name:'',

name:'',

mobile:'',

phone:'',

email:'',

city:'',

category:'',

source:'',

status:'new',

assigned_user_id:'',

address:'',

description:''

})



function submit(){

form.post('/contacts')

}

</script>


<template>

<div class="p-6">


<h1 class="text-xl mb-5">
ایجاد مخاطب
</h1>


<form
@submit.prevent="submit"
>


<input
v-model="form.business_name"
placeholder="نام کسب و کار"
class="border p-2 block mb-3"
/>


<input
v-model="form.name"
placeholder="نام مخاطب"
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



<select
v-model="form.assigned_user_id"
class="border p-2 block mb-3"
>

<option value="">
انتخاب مسئول
</option>


<option
v-for="user in users"
:key="user.id"
:value="user.id"
>
{{user.name}}
</option>


</select>



<select
v-model="form.status"
class="border p-2 block mb-3"
>


<option value="new">
جدید
</option>


<option value="active">
فعال
</option>


<option value="customer">
مشتری
</option>


</select>



<textarea
v-model="form.description"
placeholder="توضیحات"
class="border p-2 block mb-3"
/>



<button
class="bg-green-600 text-white px-5 py-2"
>
ذخیره
</button>


</form>


</div>

</template>