<script setup>

import { router } from '@inertiajs/vue3'
import { ref } from 'vue'


const props = defineProps({

    users:Object,

})


const search = ref('')



function doSearch(){

    router.get(
        '/users',
        {
            search:search.value
        },
        {
            preserveState:true
        }
    )

}



function remove(id){

    if(confirm('حذف شود؟')){

        router.delete(
            `/users/${id}`
        )

    }

}

</script>


<template>

<div class="p-6">


<h1 class="text-xl font-bold mb-5">
مدیریت کاربران
</h1>



<div class="mb-5">

<input
v-model="search"
@keyup.enter="doSearch"
class="border p-2"
placeholder="جستجو..."
/>


<a
href="/users/create"
class="bg-blue-600 text-white px-4 py-2 rounded mr-3"
>
کاربر جدید
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
نقش
</th>


<th class="border p-2">
وضعیت
</th>


<th>
عملیات
</th>


</tr>

</thead>



<tbody>


<tr
v-for="user in users.data"
:key="user.id"
>


<td class="border p-2">
{{user.name}}
</td>


<td class="border p-2">
{{user.mobile}}
</td>


<td class="border p-2">
{{user.roles?.[0]?.name}}
</td>


<td class="border p-2">
{{user.status}}
</td>


<td class="border p-2">


<a
:href="`/users/${user.id}/edit`"
class="text-blue-600"
>
ویرایش
</a>


<button
@click="remove(user.id)"
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