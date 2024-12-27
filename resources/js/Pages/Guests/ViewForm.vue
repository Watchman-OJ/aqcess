<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h2 class="text-2xl font-bold mb-4">{{ formData.title }}</h2>
            <form @submit.prevent="handleSubmit">
                <div v-for="(field, index) in formData.fields" :key="index" class="mb-4">
                    <label :for="field.name" class="block mb-1 font-bold">{{ field.label }}</label>
                    <component 
                        :is="getComponentType(field)" 
                        v-model="formData[field.name]" 
                        :name="field.name" 
                        :placeholder="field.placeholder" 
                        :options="field.options || []" 
                        class="w-full p-2 border border-gray-300 rounded" 
                    />
                </div>
                <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Submit</button>
            </form>
        </div>
    </AuthenticatedLayout>
    
</template>

<script>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    },
    props: {
        event: {
            type: Object,
            required: true
        },
        formId: {
            type: Number,
            required: true
        },
        formData: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const formData = reactive({});

        onMounted(() => {
            // Initialize formData with the form fields and their default values
            props.formData.fields.forEach(field => {
                formData[field.name] = ''; // Initialize with empty or default values if available
            });
        });

        function getComponentType(field) {
            switch (field.type) {
                case 'text':
                case 'email':
                case 'url':
                case 'tel':
                case 'date':
                case 'time':
                    return 'input';
                case 'textarea':
                    return 'textarea';
                case 'select':
                    return 'select';
                default:
                    return 'input';
            }
        }

        async function handleSubmit() {
            try {
                const response = await axios.post(`/events/${props.event.id}/form-submit`, {
                    formId: props.formId,
                    formData: formData
                });
                if (response.data.message === 'Form submitted successfully') {
                    Inertia.get(`/events/${props.event.id}/thank-you`);
                }
            } catch (error) {
                console.error('Error submitting form:', error);
            }
        }

        return {
            formData,
            getComponentType,
            handleSubmit
        };
    }
};
</script>

<style scoped>
.form-card {
    background-color: #ffffff;
    border-radius: 0.375rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
    padding: 1.5rem;
}

.form-card h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1rem;
}
</style>
