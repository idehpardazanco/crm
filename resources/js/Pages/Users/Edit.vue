<script setup>

import {useForm} from '@inertiajs/vue3'


const props = defineProps({

user:Object,
roles:Array

})



const form = useForm({

name:props.user.name,

mobile:props.user.mobile,

email:props.user.email,

password:'',

status:props.user.status,

role:props.user.roles?.[0]?.name ?? ''

})



function submit(){

form.put(
`/users/${props.user.id}`
)

}


</script>



<template>


<div class="p-6">


<h1 class="text-xl mb-5">
ویرایش کاربر
</h1>



<form
@submit.prevent="submit"
>


<input
v-model="form.name"
class="border p-2 block mb-3"
/>


<input
v-model="form.mobile"
class="border p-2 block mb-3"
/>


<input
v-model="form.email"
class="border p-2 block mb-3"
/>


<input
v-model="form.password"
type="password"
placeholder="خالی = بدون تغییر"
class="border p-2 block mb-3"
/>


<select
v-model="form.role"
class="border p-2 block mb-3"
>

<option
v-for="role in roles"
:value="role"
:key="role"
>
{{role}}
</option>

</select>


<select
v-model="form.status"
class="border p-2 block mb-3"
>

<option value="active">
فعال
</option>

<option value="inactive">
غیرفعال
</option>


</select>


<button
class="bg-blue-600 text-white px-5 py-2"
>
ویرایش
</button>


</form>


</div>


</template>