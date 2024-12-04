<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4 rounded shadow-md bg-gray-100 dark:bg-gray-900">
            <h1 class="text-3xl font-bold mb-4">Add Guest to {{ event.title }}</h1>
            <form @submit.prevent="submit">
                <div v-for="(field, index) in formFields" :key="index" class="mb-4">
                    <label :for="field.name" class="block text-sm font-medium text-gray-700">{{ field.label }}</label>
                    <input
                    :id="field.name"
                    v-model="formData[field.name]"
                    :type="field.type"
                    :placeholder="field.placeholder"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                    />
                    <button type="button" @click="removeField(index)" class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-600 mt-2">Remove</button>
                </div>
                <button type="button" @click="addField" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Add Field</button>
                <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Submit</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestQrCode from '@/Pages/Events/GuestQrCode.vue';

export default {
    components: {
        AuthenticatedLayout,
        GuestQrCode
    },
    props: {
        event: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const form = useForm({});

        // Configuration for form fields based on event category
        const fieldConfigs = {
            Meeting: [
                { label: 'Name', type: 'text', placeholder: 'Enter name', name: 'name' },
                { label: 'Email', type: 'email', placeholder: 'Enter email', name: 'email' },
                { label: 'Sex', type: 'sex', placeholder: 'Enter sex', name: 'sex' }
            ],
            Party: [
                { label: 'Name', type: 'text', placeholder: 'Enter name', name: 'name' },
                { label: 'Email', type: 'email', placeholder: 'Enter email', name: 'email' },
                { label: 'Sex', type: 'sex', placeholder: 'Enter sex', name: 'sex' }
            ],
            Appointment: [
                { label: 'Name', type: 'text', placeholder: 'Enter name', name: 'name' },
                { label: 'Email', type: 'email', placeholder: 'Enter email', name: 'email' },
                { label: 'Sex', type: 'sex', placeholder: 'Enter sex', name: 'sex' }
            ],
            Reservation: [
                { label: 'Name', type: 'text', placeholder: 'Enter name', name: 'name' },
                { label: 'Email', type: 'email', placeholder: 'Enter email', name: 'email' },
                { label: 'Sex', type: 'sex', placeholder: 'Enter sex', name: 'sex' }
            ]
        };

        const formFields = ref([]);
        const formData = ref({});

        // Load form fields based on event category
        function loadCategoryForm() {
            if (props.event.category) {
                formFields.value = fieldConfigs[props.event.category] || [];
                // Initialize formData with empty values for each field
                formData.value = formFields.value.reduce((acc, field) => {
                    acc[field.name] = '';
                    return acc;
                }, {});             
            }
        }

        // Add new custom field logic
        function addField() {
            const newField = { label: 'Custom Field', type: 'text', placeholder: 'Enter value', name: `custom_${formFields.value.length}` };
            formFields.value.push(newField);
            formData.value[newField.name] = '';
        }

        // Remove an existing field 
        function removeField(index) { 
            const fieldName = formFields.value[index].name; 
            formFields.value.splice(index, 1); 
            delete formData.value[fieldName]; 
        } 

        function submit() {
            form.data = { ...formData.value };
            form.post(route('events.storeGuest', props.event.id), {
                onSuccess: (page) => {
                    const guestId = page.props.guest.id;
                    Inertia.get(route('events.guestQrCode', { event: props.event.id, guest: guestId }));
                },
                onError: () => {
                    console.error('Error submitting form', form.errors);
                }
            });
        }

        // Load the initial form configuration when the component is mounted 
        onMounted(() => { 
            loadCategoryForm(); 
        });

        return { form, formFields, formData, addField, submit };
    }
};
</script>
