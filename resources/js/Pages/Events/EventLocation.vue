<template>
  <div class="container mx-auto p-4">
      <label for="location" class="block mb-1 font-bold">Event Location</label>
      <input 
          id="location" 
          v-model="location" 
          type="text" 
          placeholder="Write the event location here..." 
          class="location-input" 
          required
      />
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

<style scoped>
.container {
  background-color: #f7fafc;
  padding: 32px;
  margin: 70px auto;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

.location-input {
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

.location-input::placeholder {
  color: #999;
}
</style>
