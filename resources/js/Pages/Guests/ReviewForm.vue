<template>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Review Form</h2>
        <div class="form-card p-6 shadow-lg rounded-lg bg-white mb-4">
            <h3 class="text-xl font-bold mb-4">{{ formTitle }}</h3>
            <div v-for="(field, index) in formFields" :key="index" class="mb-4">
                <label :for="field.name" class="block mb-1 font-bold">{{ field.label }}</label>
                <div class="input-mimic">
                    {{ formData[field.name] }}
                </div>
            </div>
            <button @click="handleSave" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Save</button>
        </div>
    </div>
</template>

<script>
import { reactive, onMounted } from 'vue';

export default {
    props: {
        formTitle: {
            type: String,
            required: true
        },
        formFields: {
            type: Array,
            required: true
        }
    },
    setup(props, { emit }) {
        const formData = reactive({});

        onMounted(() => {
            props.formFields.forEach(field => {
                formData[field.name] = ''; // Initialize with empty or default values if available
            });
        });

        function handleSave() {
            emit('save', formData);
        }

        return {
            formData,
            handleSave
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

.input-mimic {
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 0.375rem;
    padding: 0.5rem;
    width: 100%;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: start;
    color: #333;
}
</style>

