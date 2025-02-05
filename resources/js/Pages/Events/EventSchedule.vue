<template>
  <div class="container mx-auto p-4">
      <div class="mt-4 notepad-item">
          <label for="startDate" class="block mb-1 font-bold">Event Start</label>
          <input 
              id="startDate" 
              v-model="startDate" 
              type="date" 
              class="schedule-input" 
              required
          />
      </div>
      <div class="mb-4 mt-4 schedule-time">  
        <label for="startTime" class="block mb-1 font-bold">Start Time</label>
          <input 
              id="startTime" 
              v-model="startTime" 
              type="time" 
              class="notepad-input"
              required
          /> 
      </div>

      <div class="flex justify-between mt-4"> 
          <button @click="$emit('previous')" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700"> 
              Previous 
          </button> 
          <button @click="handleNext" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700" :disabled="!isNextEnabled"> 
              Next 
          </button> 
      </div>
  </div>
</template>

<script>
import CalendarComponent from '../../Components/CalendarComponent.vue';

export default {
  components: {
      CalendarComponent
  },
  props: ['eventDetails'],
  data() {
      return {
          startDate: this.eventDetails.startDate || '',
          startTime: this.eventDetails.startTime || '',
      };
  },
  watch: { 
      startDate(val) { 
          console.log('Start Date updated:', val); 
      }, 
      startTime(val) { 
          console.log('Start Time updated:', val); 
      } 
  },
  computed: {
      isNextEnabled() {
          return this.startDate && this.startTime;
      }
  },
  methods: { 
      handleNext() { 
          const updatedDetails = {
              startDate: this.startDate,
              startTime: this.startTime,
          };     
          this.$emit('next', updatedDetails);
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

.schedule-time {
  position: relative;
  padding: 10px 0;
  border-bottom: 1px solid #ccc; 
}

.schedule-time:last-child {
  border-bottom: none; 
}

.schedule-input {
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

.schedule-input::placeholder {
  color: #999;
}
</style>
