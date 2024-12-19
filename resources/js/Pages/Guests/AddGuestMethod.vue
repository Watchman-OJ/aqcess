<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4"> 
            <h2 class="text-3xl font-bold mb-6 text-center">Select Method</h2> 
            <div class="flex flex-col space-y-4"> 
                <Link :href="route('events.formHandler', event.id)"> 
                    <button @click="selectMethod('manual')" class="bg-indigo-600 text-white py-3 px-6 rounded hover:bg-indigo-700 text-lg">Manually</button> 
                </Link>
                <Link :href="route('events.formHandler', event.id)"> 
                    <button @click="selectMethod('link')" class="bg-indigo-600 text-white py-3 px-6 rounded hover:bg-indigo-700 text-lg">Send Link</button> 
                </Link>             
            </div> 
        </div>
    </AuthenticatedLayout>
    
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';

export default {

    components: {
        Link,
        AuthenticatedLayout
    },
    props: { 
        eventId: { 
            type: Number, 
            required: true 
        },
        event: {
            type: Object,
            required: true
        }
    },
    methods: {
        selectMethod(method) {
            this.$emit('methodSelected', method);
        }
    }
}
</script>
