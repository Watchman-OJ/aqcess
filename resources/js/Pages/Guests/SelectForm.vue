<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-center">Select a Form</h2>
                <Link v-if="forms.length" :href="route('events.formHandler', { event: eventId })">
                    <button class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                        Create Form
                    </button>
                </Link>
            </div>

            <div v-if="forms.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link 
                    v-for="form in forms" 
                    :key="form.id" 
                    :href="route('viewForm', { event: eventId, form: form.id })" 
                    class="form-card p-6 shadow-lg rounded-lg cursor-pointer"
                >
                    <h3 class="text-xl font-bold mb-2 text-center">{{ form.title }}</h3>
                    <div class="separator-lines">
                        <div v-for="n in 10" :key="n" class="separator-line"></div>
                    </div>
                </Link>
            </div>

            <div v-else class="flex flex-col items-center justify-center h-full">
                <p class="mt-4 text-lg text-gray-500">You do not have any forms created</p>
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

<style scoped>
.container {
    background-color: #f7fafc;
    padding: 32px;
    margin: 70px auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.form-card {
    position: relative;
    overflow: hidden;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    height: 350px; 
}

.separator-lines {
    width: 100%;
}

.separator-line {
    width: 100%;
    height: 1px;
    background-color: #ccc;
    margin: 20px 0; 
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
