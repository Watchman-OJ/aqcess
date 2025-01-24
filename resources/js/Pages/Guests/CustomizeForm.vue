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
                    <li v-for="(field, key) in allFields" :key="key" class="flex justify-between items-center mb-2">
                        <span>{{ field.label }}</span>
                        <button type="button" @click="addField(key)" class="bg-green-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-green-600">+</button>
                    </li>
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
import axios from 'axios';

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
        const allFields = reactive({});

        // Fetching allFields from server
        onMounted(async () => {
            try {
                const response = await axios.get('/api/fields');
                Object.assign(allFields, response.data);
                console.log('All fields loaded:', allFields);
                loadCategoryForm();
            } catch (error) {
                console.error('Error fetching all fields:', error.response || error.message);
            }
        });

        async function loadCategoryForm() {
            if (!props.event || !props.event.category) {
                console.error('Event category is missing or undefined');
                return;
            }
            const category = props.event.category.toLowerCase();
            console.log('Loading fields for category:', category);
            try {
                const response = await axios.get(`/api/fields/category/${category}`);
                const defaultFields = response.data || [];
                defaultFields.forEach(field => {
                    fields.push(field);
                    delete allFields[field.name];
                });
                console.log('Form fields loaded:', fields);
            } catch (error) {
                console.error('Error loading category fields:', error.response || error.message);
            }
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
