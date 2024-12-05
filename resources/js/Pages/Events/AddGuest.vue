<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4 rounded shadow-md bg-gray-100 dark:bg-gray-900">
            <h1 class="text-3xl font-bold mb-4">Add Guest to {{ event.title }}</h1>
            <form @submit.prevent="submit">
                <!-- Render form fields -->
                <div v-for="(field, index) in formFields" :key="index" class="mb-4">
                    <label :for="field.name" class="block text-sm font-medium text-gray-700">{{ field.label }}</label>
                    <input v-if="field.type !== 'select'" :id="field.name" v-model="formData[field.name]" :type="field.type" :placeholder="field.placeholder" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    <select v-if="field.type === 'select'" :id="field.name" v-model="formData[field.name]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option v-for="option in field.options" :value="option">{{ option }}</option>
                    </select>
                    <button type="button" @click="removeField(index)" class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-600 mt-2">Remove</button>
                </div>
                <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 mt-2">Submit</button>
            </form>

            <!-- Add field options -->
            <div class="mt-6">
                <h2 class="text-2xl font-bold mb-4">Add Additional Fields</h2>
                <div>
                    <select v-model="selectedFieldType" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="" disabled>Select Field Type</option>
                        <option v-for="(field, key) in allFields" :key="key" :value="key">{{ field.label }}</option>
                    </select>
                    <button type="button" @click="addField(selectedFieldType)" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 mt-2">Add Field</button>
                </div>
                <div v-for="(field, key) in removedFields" :key="key" class="mt-2">
                    <p>{{ field.label }}</p>
                    <button type="button" @click="reAddField(key)" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Re-add Field</button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    },
    props: {
        event: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const form = useForm({});
        const formFields = ref([]);
        const formData = ref({});
        const selectedFieldType = ref('');
        const removedFields = ref({});

        // Configuration for form fields based on event category
        const fieldConfigs = ref({
            meeting: [
                { label: 'Name', type: 'text', placeholder: 'Enter name', name: 'name' },
                { label: 'Sex', type: 'text', placeholder: 'Enter sex', name: 'sex' },
                { label: 'Phone', type: 'tel', placeholder: 'Enter phone number', name: 'phone' },
                { label: 'Email', type: 'email', placeholder: 'Enter email', name: 'email' },
                { label: 'Attendee Type', type: 'select', options: ['Speaker', 'Participant', 'Organizer'], name: 'attendeeType' }
            ],
            party: [
                { label: 'Name', type: 'text', placeholder: 'Enter name', name: 'name' },
                { label: 'Sex', type: 'text', placeholder: 'Enter sex', name: 'sex' },
                { label: 'Email', type: 'email', placeholder: 'Enter email', name: 'email' }
            ],
            appointment: [
                { label: 'Name', type: 'text', placeholder: 'Enter name', name: 'name' },
                { label: 'Sex', type: 'text', placeholder: 'Enter sex', name: 'sex' },
                { label: 'Email', type: 'email', placeholder: 'Enter email', name: 'email' }
            ],
            reservation: [
                { label: 'Name', type: 'text', placeholder: 'Enter name', name: 'name' },
                { label: 'Sex', type: 'text', placeholder: 'Enter sex', name: 'sex' },
                { label: 'Email', type: 'email', placeholder: 'Enter email', name: 'email' }
            ]
        });

        const allFields = ref({
            company: { label: 'Company', type: 'text', placeholder: 'Enter company name', name: 'company' },
            jobTitle: { label: 'Job Title', type: 'text', placeholder: 'Enter job title', name: 'jobTitle' },
            topicsOfInterest: { label: 'Topics of Interest', type: 'textarea', placeholder: 'Enter topics of interest', name: 'topicsOfInterest' },
            linkedIn: { label: 'LinkedIn Profile', type: 'url', placeholder: 'Enter LinkedIn profile URL', name: 'linkedIn' },
            dietaryRestrictions: { label: 'Dietary Restrictions', type: 'text', placeholder: 'Enter any dietary restrictions', name: 'dietaryRestrictions' },
            sessionPreferences: { label: 'Session Preferences', type: 'checkbox', options: ['Morning Session', 'Afternoon Session', 'Networking Event'], name: 'sessionPreferences' }
        });

        // Load form fields based on event category
        function loadCategoryForm() {
            const category = props.event.category.toLowerCase();
            console.log('Event Category:', category);
            if (category) {
                formFields.value = fieldConfigs.value[category] || [];
                console.log('Form Fields:', formFields.value);
                // Initialize formData with empty values for each field
                formData.value = formFields.value.reduce((acc, field) => {
                    acc[field.name] = '';
                    return acc;
                }, {});
                console.log('Form Data:', formData.value);
            }
        }

        // Add new custom field logic
        function addField(fieldType) {
            const newField = allFields.value[fieldType];
            if (newField) {
                formFields.value.push(newField);
                formData.value[newField.name] = '';
                delete allFields.value[fieldType];
            }
        }

        // Re-add removed field
        function reAddField(fieldKey) {
            const field = removedFields.value[fieldKey];
            if (field) {
                formFields.value.push(field);
                formData.value[field.name] = '';
                delete removedFields.value[fieldKey];
            }
        }

        // Remove an existing field 
        function removeField(index) { 
            const field = formFields.value[index];
            const fieldName = field.name; 
            formFields.value.splice(index, 1); 
            delete formData.value[fieldName]; 
            removedFields.value[fieldName] = field;
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

        return { 
            form, formFields, formData, selectedFieldType, removedFields, 
            addField, removeField, reAddField, submit, allFields // Ensure allFields is returned here 
        };
    }
};
</script>

