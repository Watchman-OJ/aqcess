<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-3xl text-center font-bold mb-4">Events</h1>
            <div v-if="!hasEvents" class="flex flex-col items-center justify-center h-full">
                <p class="mt-4 text-lg text-gray-500">No events found. Click below to create your first event!</p>
                <button @click="openFormBuilder" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 mt-4">
                    Create Event
                </button>
            </div>
              
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <button @click="openFormBuilder" class="absolute top-12 right-2 bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 mt-4">
                    Create Event
                </button>
                <div v-for="event in events" :key="event.id" @click="openEvent(event)" 
                class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 cursor-pointer">
                    <div v-for="(value, key) in event.fields" :key="key">
                        <h2 class="text-xl font-semibold mb-2"> {{ key }} </h2>
                        <p class="text-gray-600"> {{ value }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- FormBuilder Component -->
         <FormBuilder v-if="showFormBuilder" @save-form="handleFormSave" />

         <!-- Display dynamic form when an event is selected -->
          <div v-if="selectedEvent">
            <DynamicForm :form="selectedEvent" />
          </div>
    </AuthenticatedLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FormBuilder from '@/Components/FormBuilder.vue';
import DynamicForm from '@/Components/DynamicForm.vue';
import axios from 'axios';

export default {
    components: {
        Link,
        AuthenticatedLayout,
        FormBuilder,
        DynamicForm,
    },
    props: {
        events: {
            type: Array,
            required: true
        },
        hasEvents: {
            type: Boolean,
            required: true
        }
    },
    data() {
        return {
            showFormBuilder: false,
            selectedEvent: null,
        };
    },
    methods: {
        openFormBuilder() {
            this.showFormBuilder = true;
        },
        handleFormSave(fields) {
            axios.post('/api/forms', { fields })
            .then(response => {
                this.selectedEvent = response.data.form;
                this.showFormBuilder = false;
                // Reload Events to include the new one
                this.$inertia.reload();
            })
            .catch(error => {
                console.error('Error saving form:', error);
            });
        },
        openEvent(event) {
            axios.get(`/api/forms/${event.id}`)
            .then(response => {
                this.selectedEvent = response.data;
            })
            .catch(error => {
                console.error('Error loading event:', error);
            });
        }
    }
}
</script>
