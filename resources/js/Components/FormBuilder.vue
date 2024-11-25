<template>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Form Builder</h1>
        <div class="mb-4">
            <button @click="addField('text')" class="bg-indigo-600 text-white py-2 px-4 rounded mr-2 hover:bg-700">
                Add Title Field
            </button>
            <button @click="addField('date')" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                Add Date Field
            </button>
        </div>
        <div v-for="(field, index) in fields" :key="index" class="mb-4 p-4 border rounded-lg shadow-sm bg-gray-100">
            <component 
            :is="field.component" 
            :id="field.id" 
            :label="field.label" 
            :value="field.value" 
            @update:value="field.value = $event"
            />
            <button @click="removeField(index)" class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700">
                Remove
            </button>
        </div>
        <button @click="saveForm" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 mt-4">
            Save Form
        </button>
    </div>
</template>

<script>
import axios from 'axios';
import DatePicker from './DatePicker.vue';
import TitleInput from './TitleInput.vue';

export default {
    components: {
        TitleInput,
        DatePicker
    },
    data() {
        return {
            fields: [],
            nextId: 1,
        };
    },
    methods: {
        addField(type) {
            const id = `field-${this.nextId++}`;
            const field = {
                id,
                label: `Label ${this.nextId}`,
                value: '',
                component: type === 'text' ? 'TitleInput' : 'DatePicker',
            };
            this.fields.push(field);
        },
        removeField(index) {
            this.fields.splice(index, 1);
        },
        saveForm() {
            // Send form configuration to the backend to save
            axios.post('/api/forms', { fields: this.fields })
            .then(response => {
                console.log('Form saved successfully');
            })
            .catch(error => {
                console.error('Error saving form:', error);
            });
        },
    },
};
</script>