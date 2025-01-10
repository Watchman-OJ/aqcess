<template>
    <div>
      <label for="location" class="block text-sm font-medium text-gray-700">Event Location</label>
      <input id="location" v-model="location" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
      <div class="flex justify-between mt-4">
        <button @click="$emit('previous')" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">
          Previous
        </button>
        <button @click="handleNext" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700" :disabled="!location">
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
        location: this.eventDetails.location
      };
    },
    methods: {
      handleNext() {
        this.$emit('next', {location: this.location});
      }
    }
  };
  </script>
  