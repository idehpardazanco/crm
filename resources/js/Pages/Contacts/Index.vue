<script setup>

import { router } from '@inertiajs/vue3'
import { ref } from 'vue'


const props = defineProps({

    contacts:Object,
    users:Array

})


const search = ref('')



function doSearch(){

    router.get(
        '/contacts',
        {
            search: search.value
        },
        {
            preserveState:true
        }
    )

}



function remove(id){

    if(confirm('حذف شود؟')){

        router.delete(
            `/contacts/${id}`
        )

    }

}

</script>


<template>

<div class="p-6">


<h1 class="text-xl font-bold mb-5">
مخاطبین
</h1>



<div class="mb-5">

<input
v-model="search"
@keyup.enter="doSearch"
class="border p-2"
placeholder="جستجو نام یا موبایل"
/>


<a
href="/contacts/create"
class="bg-blue-600 text-white px-4 py-2 rounded mr-3"
>
مخاطب جدید
</a>

</div>




<table class="w-full border">


<thead>

<tr>

<th class="border p-2">
نام
</th>


<th class="border p-2">
موبایل
</th>


<th class="border p-2">
کسب و کار
</th>


<th class="border p-2">
وضعیت
</th>


<th class="border p-2">
مسئول
</th>


<th class="border p-2">
عملیات
</th>

</tr>

</thead>



<tbody>


<tr
v-for="contact in contacts.data"
:key="contact.id"
>


<td class="border p-2">
{{contact.name}}
</td>


<td class="border p-2">
{{contact.mobile}}
</td>


<td class="border p-2">
{{contact.business_name}}
</td>


<td class="border p-2">
{{contact.status}}
</td>


<td class="border p-2">

{{contact.assigned_user?.name ?? '-'}}

</td>


<td class="border p-2">


<a
:href="`/contacts/${contact.id}/edit`"
class="text-blue-600"
>
ویرایش
</a>


<button
@click="remove(contact.id)"
class="text-red-600 mr-3"
>
حذف
</button>


</td>


</tr>


</tbody>


</table>



</div>

</template>