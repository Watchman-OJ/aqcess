<template>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">{{ formTitle }}</h2>
        <div v-for="(field, index) in formFields" :key="index" class="mb-4 form-fields">
            <label :for="field.name" class="block mb-1 font-bold">{{ field.label }}</label>
            <input 
                v-if="field.type !== 'textarea' && field.type !== 'select'" 
                :type="field.type" 
                :placeholder="field.placeholder" 
                :name="field.name" 
                class="form-field-input" 
                readonly 
            />
            <textarea 
                v-if="field.type === 'textarea'" 
                :placeholder="field.placeholder" 
                :name="field.name" 
                class="form-field-input"
                readonly
            />
            <select 
                v-if="field.type === 'select'" 
                :name="field.name" 
                class="form-field-input"
                disabled
            >
                <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
            </select>
        </div>
        <div class="flex justify-end mt-6">
            <button @click="$emit('save')" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Save Form</button>
        </div>
    </div>
</template>

<script>
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

.form-fields {
    position: relative;
    padding: 10px 0;
    border-bottom: 1px solid #ccc; 
}

.form-fields:last-child {
    border-bottom: none; 
}

.form-field-input {
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

.form-field-input::placeholder {
    color: #999;
}
</style>
