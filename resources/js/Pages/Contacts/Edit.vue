<script setup>

import {useForm} from '@inertiajs/vue3'


const props = defineProps({

contact:Object,

users:Array

})



const form = useForm({

business_name:
props.contact.business_name ?? '',

name:
props.contact.name,

mobile:
props.contact.mobile,

phone:
props.contact.phone ?? '',

email:
props.contact.email ?? '',

city:
props.contact.city ?? '',

category:
props.contact.category ?? '',

source:
props.contact.source ?? '',

status:
props.contact.status,

assigned_user_id:
props.contact.assigned_user_id ?? '',

address:
props.contact.address ?? '',

description:
props.contact.description ?? ''

})



function submit(){

form.put(
`/contacts/${props.contact.id}`
)

}


</script>


<template>

<div class="p-6">


<h1 class="text-xl mb-5">
ویرایش مخاطب
</h1>



<form
@submit.prevent="submit"
>


<input
v-model="form.business_name"
class="border p-2 block mb-3"
/>


<input
v-model="form.name"
class="border p-2 block mb-3"
/>


<input
v-model="form.mobile"
class="border p-2 block mb-3"
/>


<select
v-model="form.assigned_user_id"
class="border p-2 block mb-3"
>


<option value="">
بدون مسئول
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

<option value="inactive">
غیرفعال
</option>

<option value="customer">
مشتری
</option>


</select>



<textarea
v-model="form.description"
class="border p-2 block mb-3"
/>



<button
class="bg-blue-600 text-white px-5 py-2"
>
ویرایش
</button>


</form>


</div>

</template>