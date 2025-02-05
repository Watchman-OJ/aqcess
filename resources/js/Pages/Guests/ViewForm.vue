<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h2 class="text-2xl font-bold mb-4">{{ form.title }}</h2>
            <form @submit.prevent="saveGuest">
                <div v-for="(field, index) in form.fields" :key="index" class="mb-4 guest-data">
                    <label :for="field.name" class="block mb-1 font-bold">{{ field.label }}</label>
                    <input 
                        v-if="field.type !== 'textarea' && field.type !== 'select'" 
                        :type="field.type" 
                        :placeholder="field.placeholder" 
                        :name="field.name" 
                        v-model="formData[field.name]"
                        class="guest-data-input" 
                    />
                    <textarea 
                        v-if="field.type === 'textarea'" 
                        :placeholder="field.placeholder" 
                        :name="field.name" 
                        v-model="formData[field.name]"
                        class="guest-data-textarea"
                    />
                    <select 
                        v-if="field.type === 'select'" 
                        :name="field.name" 
                        v-model="formData[field.name]"
                        class="guest-data-select"
                    >
                        <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
                    </select>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Add Guest</button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';

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
        console.log('Form data on create:', this.form);
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
        async saveGuest() {
            try {
                const response = await axios.post(`/events/${this.eventId}/guests`, {
                    ...this.formData,
                    event_id: this.eventId,
                    form_id: this.form.id
                });
                if (response.data.id) {
                    Inertia.get(route('events.guestList', { event: this.eventId }));
                }
            } catch (error) {
                console.error('Error saving guest:', error);
            }
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

.guest-data {
    position: relative;
    padding: 10px 0;
    border-bottom: 1px solid #ccc;
}

.guest-data:last-child {
    border-bottom: none;
}

.guest-data-input, .guest-data-textarea, .guest-data-select {
    width: 100%;
    padding: 0;
    border: none;
    border-bottom: 1px dotted #ccc;
    outline: none;
    background: transparent;
    font-family: 'Courier New', Courier, monospace;
    color: #333;
    margin-top: 10px;
}

.guest-data-input::placeholder, 
.guest-data-textarea::placeholder,
.guest-data-select::placeholder {
    color: #999;
}

.guest-data-textarea {
    resize: none;
    height: 100px;
}

.guest-data-select option {
    background-color: #f7fafc;
}
</style>
