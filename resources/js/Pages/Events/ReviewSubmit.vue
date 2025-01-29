<template>
  
    <div class="container mx-auto my-8 p-8 rounded shadow-lg bg-gray-200 dark:bg-gray-900">
      <h2 class="text-xl font-semibold mb-4 underline underline-offset-4 underline-custom">Review and Submit</h2>
      <div class="review">
        <div class="review-list mb-4">
          <label class="block text-sm font-medium text-gray-700">Event Title</label>
          <p>{{ eventDetails.title }}</p>
        </div>
        <div class="review-list mb-4">
          <label class="block text-sm font-medium text-gray-700">Category</label>
          <p>{{ eventDetails.category }}</p>
        </div>
        <div class="review-list mb-4">
          <label class="block text-sm font-medium text-gray-700">Date</label>
          <p>{{ eventDetails.startDate }}</p>
        </div>
        <div class="review-list mb-4">
          <label class="block text-sm font-medium text-gray-700">Time</label>
          <p>{{ eventDetails.startTime }}</p>
        </div>
        <div class="review-list mb-4">
          <label class="block text-sm font-medium text-gray-700">Location</label>
          <p>{{ eventDetails.location }}</p>
        </div>
      </div>
      <div class="flex justify-between mt-4">
        <button @click="$emit('previous')" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">
          Previous
        </button>
        <button @click="submitEvent" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
          Submit
        </button>
      </div>
    </div>
</template>

<script>
export default {
  props: ['eventDetails'],

  methods: {
    submitEvent() {
      this.$emit('submit', this.eventDetails);
    }
  },
  mounted() {
    console.log('Event Details:', this.eventDetails);
  }
};
</script>

<style scoped>
.container {
  background-color: #f7fafc;
  padding: 32px;
  margin: 70px auto;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.review {
  list-style-type: none;
  padding: 0;
  border-radius: 8px;
  background-color: #f9f9f9;
}

.review-list {
  position: relative;
  padding: 10px 0;
  border-bottom: 1px solid #ccc; 
}

.underline-custom {
  text-decoration-color: #ccc; 
}
</style>
