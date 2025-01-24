<template>
    <div>
      <div class="mt-4">
        <div class="mb-4">
          <label for="startDate" class="block text-sm font-medium text-gray-700">Event Start</label>
          <input id="startDate" v-model="startDate" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required/>
        </div>
        <div class="mb-4 mt-4">  
          <TimeFrame id="startTime" v-model="startTime" label="Start Time"/> 
        </div> 
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
import TimeFrame from '../../Components/TimeFrame.vue';
import CalendarComponent from '../../Components/CalendarComponent.vue';
  
  export default {
    components: {
      TimeFrame,
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
  