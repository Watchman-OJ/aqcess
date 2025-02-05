<template>
    <div class="container mx-auto p-4">
        <label for="category" class="block mb-1 font-bold">Category</label>
        <select 
            id="category" 
            v-model="selectedCategory" 
            class="category-select"
        >
            <option value="" disabled>Select a Category</option>
            <option v-for="category in categories" :key="category.value" :value="category.value">
                {{ category.text }}
            </option>
        </select>  
        <div class="flex justify-between mt-4">
            <button @click="$emit('previous')" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">
                Previous
            </button>
            <button @click="handleNext" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700" :disabled="!selectedCategory"> 
                Next 
            </button>
        </div>    
    </div>   
</template>

<script>
export default {
    props: ['eventDetails'],
    data() {
        return {
            selectedCategory: this.eventDetails.category,
            categories: [
                { text: 'Meeting', value: 'meeting' },
                { text: 'Party', value: 'Party' },
                { text: 'Appointment', value: 'appointment' },
                { text: 'Reservation', value: 'reservation' }
            ]
        };
    },
    methods: { 
        handleNext() { 
            this.$emit('next', { category: this.selectedCategory }); 
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

.category-select {
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

.category-select::placeholder {
    color: #999;
}
</style>
