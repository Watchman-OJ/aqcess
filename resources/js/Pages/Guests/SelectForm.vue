<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h2 class="text-2xl font-bold mb-4">Select a Form</h2>
            <div v-if="forms.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link 
                    v-for="form in forms" 
                    :key="form.id" 
                    :href="route('viewForm', { event: eventId, form: form.id })" 
                    class="form-card p-4 shadow-lg rounded-lg cursor-pointer"
                >
                    <h3 class="text-xl font-bold mb-2">{{ form.title }}</h3>
                </Link>
                <Link :href="route('events.formHandler', { event: eventId })" class="create-form-link">
                    <button class="absolute top-12 right-2 bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 mt-4">
                        Create Form
                    </button>
                </Link> 
            </div>
            <div v-else class="flex flex-col items-center justify-center h-full">
                <p class="mt-4 text-lg text-gray-500">You do not have any form created</p>
                <Link :href="route('events.formHandler', { event: eventId })">
                    <button class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 mt-4">
                        Create Form
                    </button>
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
        AuthenticatedLayout,
        Link
    },
    props: {
        event: { 
            type: Object, 
            required: true 
        }, 
        eventId: {
            type: Number,
            required: true
        },
        forms: {
            type: Array,
            required: true
        }
    }
};
</script>


<style>
.form-card {
    position: relative;
    overflow: hidden;
}
.create-form-link { 
    position: absolute; 
    top: 12px; 
    right: 2px; 
}
.form-preview {
    height: 100px;
    overflow: hidden;
}
.fade-effect {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 20px;
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
}
</style>
