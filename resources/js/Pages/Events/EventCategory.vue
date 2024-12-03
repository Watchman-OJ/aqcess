<template>
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select id="category" v-model="selectedCategory" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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