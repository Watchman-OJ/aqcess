<template>
    <div class="container mx-auto p-4">
        <div class="selected-fields-container mb-6">
            <h2 class="text-2xl font-bold mb-4">Selected Fields</h2>
            <ul class="drag-area">
                <li 
                    v-for="(field, index) in fields" 
                    :key="index" 
                    class="drag-item flex justify-between items-center mb-2" 
                    draggable="true"
                    @dragstart="dragStart(index)"
                    @dragover.prevent
                    @dragenter="dragEnter(index)"
                    @dragend="dragEnd"
                >
                    <span>{{ field.label }}</span>
                    <button type="button" @click="removeField(index)" class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">-</button>
                </li>
            </ul>
        </div>

        <div class="customize-form-container">
            <div @click="toggleDropdown" class="flex justify-between items-center cursor-pointer">
                <h2 class="text-2xl font-bold">Customize Form</h2>
                <svg v-if="dropdownOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div v-if="dropdownOpen" class="mt-4">
                <ul class="field-selection">
                    <li v-for="(field, key, index) in allFields" :key="key" class="flex justify-between items-center mb-2">
                        <span>{{ field.label }}</span>
                        <button type="button" @click="addField(key)" class="bg-green-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-green-600">+</button>
                    </li>
                    <hr v-if="index !== Object.keys(allFields).length - 1" class="separator my-2"/>
                </ul>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button @click="confirmFields" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Confirm</button>
        </div>
    </div>
</template>


<script>
import { reactive, ref, onMounted } from 'vue';

export default {
    props: {
        event: {
            type: Object,
            required: true
        },
        eventId: {
            type: Number,
            required: true
        },
        formFields: {
            type: Array,
            default: () => []
        }
    },
    setup(props, { emit }) {
        const fields = reactive([...props.formFields]);
        const dragIndex = ref(null);
        const dropdownOpen = ref(false);

        const allFields = reactive({
            company: { label: 'Company', type: 'text', placeholder: 'Enter company name', name: 'company' },
            jobTitle: { label: 'Job Title', type: 'text', placeholder: 'Enter job title', name: 'jobTitle' },
            topicsOfInterest: { label: 'Topics of Interest', type: 'textarea', placeholder: 'Enter topics of interest', name: 'topicsOfInterest' },
            linkedIn: { label: 'LinkedIn Profile', type: 'url', placeholder: 'Enter LinkedIn profile URL', name: 'linkedIn' },
            website: { label: 'Website', type: 'url', placeholder: 'Enter website URL', name: 'website' }, 
            gender: { label: 'Gender', type: 'select', options: ['Male', 'Female', 'Non-Binary'], name: 'gender' },
            preferredDate: { label: 'Preferred Date', type: 'date', placeholder: 'Select preferred date', name: 'preferredDate' }, 
            preferredTime: { label: 'Preferred Time', type: 'time', placeholder: 'Select preferred time', name: 'preferredTime' },
            phone: { label: 'Phone', type: 'tel', placeholder: 'Enter phone number', name: 'phone' }, 
            address: { label: 'Address', type: 'text', placeholder: 'Enter address', name: 'address' }, 
            zipCode: { label: 'Zip Code', type: 'text', placeholder: 'Enter zip code', name: 'zipCode' }, 
            country: { label: 'Country', type: 'select', options: ['Country1', 'Country2', 'Country3'], name: 'country' }, 
            contactMethod: { label: 'Preferred Contact Method', type: 'select', options: ['Email', 'Phone', 'SMS'], name: 'contactMethod' }, 
            emergencyContact: { label: 'Emergency Contact', type: 'tel', placeholder: 'Enter emergency contact number', name: 'emergencyContact' },
            dietaryRestrictions: { label: 'Dietary Restrictions', type: 'text', placeholder: 'Enter any dietary restrictions', name: 'dietaryRestrictions' },
            sessionPreferences: { label: 'Session Preferences', type: 'select', options: ['Morning Session', 'Afternoon Session', 'Networking Event'], name: 'sessionPreferences' },
            foodPrefernces: { label: 'Food Preferences', type: 'select', options: ['Vegetarian', 'Non-Vegetarian', 'Vegan'], name: 'foodPreferences' }, 
            musicRequests: { label: 'Music Requests', type: 'textarea', placeholder: 'Enter your music requests', name: 'musicRequests' }, 
            allergies: { label: 'Allergies', type: 'textarea', placeholder: 'Enter any allergies', name: 'allergies' }
        });

        const fieldConfigs = reactive({
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
                { label: 'Phone', type: 'tel', placeholder: 'Enter phone number', name: 'phone' },
                { label: 'Email', type: 'email', placeholder: 'Enter email', name: 'email' },
                { label: 'Plus One or More', type: 'number', placeholder: 'Enter number of plus ones', name: 'plusOne' },
            ],
            appointment: [
                { label: 'Name', type: 'text', placeholder: 'Enter name', name: 'name' },
                { label: 'Sex', type: 'text', placeholder: 'Enter sex', name: 'sex' },
                { label: 'Phone', type: 'tel', placeholder: 'Enter phone number', name: 'phone' },
                { label: 'Email', type: 'email', placeholder: 'Enter email', name: 'email' },
                { label: 'Reason for Appointment', type: 'textarea', placeholder: 'Enter reason for appointment', name: 'reasonForAppointment' }, 
            ],
            reservation: [
                { label: 'Name', type: 'text', placeholder: 'Enter name', name: 'name' },
                { label: 'Sex', type: 'text', placeholder: 'Enter sex', name: 'sex' },
                { label: 'Phone', type: 'tel', placeholder: 'Enter phone number', name: 'phone' },
                { label: 'Email', type: 'email', placeholder: 'Enter email', name: 'email' },
                { label: 'Number of Guests', type: 'number', placeholder: 'Enter number of guests', name: 'numberOfGuests' }
            ]
        });

        // Load default form fields based on event category
        onMounted(() => {
            console.log('Props received:', props.event, props.eventId); // Add log statement
            loadCategoryForm();
        });

        function loadCategoryForm() {
            if (!props.event || !props.event.category) {
                console.error('Event category is missing or undefined'); // Add error check
                return;
            }
            const category = props.event.category.toLowerCase();
            console.log('Loading fields for category:', category);
            const defaultFields = fieldConfigs[category] || [];
            defaultFields.forEach(field => {
                fields.push(field);
                delete allFields[field.name];
            });
            console.log('Form fields loaded:', fields);
        }

        function addField(fieldKey) {
            const newField = allFields[fieldKey];
            if (newField) {
                fields.push(newField);
                delete allFields[fieldKey];
                console.log('Field added:', newField);
                console.log('Updated selected fields:', fields);
            } else {
                console.log('Field not found in allFields:', fieldKey);
            }
        }

        function removeField(index) {
            if (index >= 0 && index < fields.length) {
                const field = fields.splice(index, 1)[0];
                if (field) {
                    allFields[field.name] = field;
                    console.log('Field removed:', field);
                } else {
                    console.log('Removed field is undefined');
                }
            } else {
                console.log('Invalid index:', index);
            }
        }

        function dragStart(index) {
            dragIndex.value = index;
        }

        function dragEnter(index) {
            const temp = fields[dragIndex.value];
            fields[dragIndex.value] = fields[index];
            fields[index] = temp;
            dragIndex.value = index;
        }

        function dragEnd() {
            dragIndex.value = null;
        }

        function toggleDropdown() {
            dropdownOpen.value = !dropdownOpen.value;
        }

        function confirmFields() {
            emit('confirm', fields);
        }

        return {
            fields,
            dragIndex,
            dropdownOpen,
            allFields,
            addField,
            removeField,
            dragStart,
            dragEnter,
            dragEnd,
            toggleDropdown,
            confirmFields
        };
    }
};
</script>


<style scoped>
.selected-fields-container {
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.field-selection {
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 8px;
    max-height: 200px;
    overflow-y: auto;
    background-color: #f9f9f9;
}

.field-selection li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 0;
    position: relative;
}

.field-selection li:not(:last-child)::after { 
    content: ""; 
    display: block; 
    position: absolute; 
    bottom: 0; 
    left: 0; 
    width: 100%; 
    height: 1px; 
    background-color: #ccc; /* Separator color */ }

.field-selection button, .selected-fields button {
    width: 24px;
    height: 24px;
    line-height: 24px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.field-selection button {
    background-color: #28a745;
    color: white;
    border-radius: 50%;
}

.field-selection button:hover {
    background-color: #218838;
}

.selected-fields button {
    background-color: #dc3545;
    color: white;
    border-radius: 50%;
}

.selected-fields button:hover {
    background-color: #c82333;
}

.drag-area {
    min-height: 50px;
}

.drag-item {
    padding: 5px 0;
    border-bottom: 1px solid #ddd;
    cursor: move;
}

.drag-item:last-child {
    border-bottom: none;
}

.separator { 
    border-top: 1px solid #ccc; width: 100%; 
}

.flex {
    display: flex;
}

.justify-between {
    justify-content: space-between;
}

</style>
