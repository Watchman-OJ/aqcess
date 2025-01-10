<template>
  <div>
    <h2 class="text-xl font-semibold mb-4">Review and Submit</h2>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Event Title</label>
      <p>{{ eventDetails.title }}</p>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Category</label>
      <p>{{ eventDetails.category }}</p>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Event Scheduling</label>
      <p v-if="eventDetails.eventType === 'single'">Single Day Event</p>
      <p v-if="eventDetails.eventType === 'multiple'">Multiple Day Event</p>
      <div v-if="eventDetails.eventType === 'single'">
        <p>Start: {{ eventDetails.startDate }}</p>
        <p>Start Time: {{ eventDetails.startTime }}</p>
        <p>Timezone: {{ eventDetails.timezone }}</p>
      </div>
      <div v-if="eventDetails.eventType === 'multiple'">
        <p>Days: {{ eventDetails.eventDays.join(', ') }}</p>
        <p>Start Time: {{ eventDetails.startTime }}</p>
        <p>Timezone: {{ eventDetails.timezone }}</p>
      </div>
    </div>
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Location</label>
      <p>{{ eventDetails.location }}</p>
    </div>
    <div class="flex justify-between mt-4">
      <button @click="$emit('previous')" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">
        Previous
      </button>
      <button @click="submitEvent" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
        Create Event
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
