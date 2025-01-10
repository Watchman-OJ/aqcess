<template>
    <div class="container mx-auto p-4">
        <label :for="field.name" class="block text-sm font-medium text-gray-700">{{ field.label }}</label>
        <input 
            :type="field.type" 
            v-model="value" 
            :placeholder="field.placeholder" 
            :name="field.name" 
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
        />
        <div class="flex justify-between mt-6">
            <button @click="$emit('previous', value)" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">Previous</button>
            <button @click="nextStep" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Next</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        field: {
            type: Object,
            required: true
        },
        initialValue: {
            type: [String, Number, Boolean],
            default: ''
        }
    },
    data() {
        return {
            value: this.initialValue
        };
    },
    watch: {
        initialValue(newValue) {
            this.value = newValue;
        }
    },
    methods: {
        nextStep() {
            console.log('Next step value:', this.value);
            this.$emit('next', this.value);
        }
    }
};
</script>
