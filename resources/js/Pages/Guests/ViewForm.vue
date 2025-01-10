<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h2 class="text-2xl font-bold mb-4">{{ form.title }}</h2>
            <form @submit.prevent="saveForm">
                <div v-for="(field, index) in form.fields" :key="index" class="mb-4">
                    <label :for="field.name" class="block mb-1 font-bold">{{ field.label }}</label>
                    <input 
                        v-if="field.type !== 'textarea' && field.type !== 'select'" 
                        :type="field.type" 
                        :placeholder="field.placeholder" 
                        :name="field.name" 
                        v-model="formData[field.name]"
                        class="w-full p-2 border border-gray-300 rounded" 
                    />
                    <textarea 
                        v-if="field.type === 'textarea'" 
                        :placeholder="field.placeholder" 
                        :name="field.name" 
                        v-model="formData[field.name]"
                        class="w-full p-2 border border-gray-300 rounded"
                    />
                    <select 
                        v-if="field.type === 'select'" 
                        :name="field.name" 
                        v-model="formData[field.name]"
                        class="w-full p-2 border border-gray-300 rounded"
                    >
                        <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
                    </select>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Save Form</button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

export default {
    components: {
        AuthenticatedLayout
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
        form: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            formData: {}
        };
    },
    created() {
        console.log('Form data on create:', this.form)
        this.initializeFormData();
    },
    methods: {
        initializeFormData() {
            if (Array.isArray(this.form.fields)) {
                for (const field of this.form.fields) {
                    this.formData[field.name] = '';
                }
            } else {
                console.error('Form fields are not an array:', this.form.fields);
            }
        },
        async saveForm() {
            try {
                const response = await axios.post(`/events/${this.eventId}/guests`, {
                    ...this.formData,
                    event_id: this.eventId,
                    form_id: this.form.id
                });
                if (response.data.message === 'Guest saved successfully') {
                    alert('Guest saved successfully');
                }
            } catch (error) {
                console.error('Error saving guest:', error);
            }
        }
    }
};
</script>

