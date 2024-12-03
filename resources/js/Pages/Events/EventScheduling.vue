<template>
    <div>
      <label for="eventType" class="block text-sm font-medium text-gray-700">Event Scheduling</label>
      <select id="eventType" v-model="eventType" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        <option value="" disabled>Select Event Type</option>
        <option value="single">Single Day Event</option>
        <option value="multiple">Multiple Day Event</option>
      </select>
  
      <div v-if="eventType === 'single'" class="mt-4">
        <div class="mb-4">
          <label for="startDate" class="block text-sm font-medium text-gray-700">Event Start</label>
          <input id="startDate" v-model="startDate" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required/>
        </div>
        <div class="mb-4 mt-4">  
          <TimeFrame id="startTime" v-model="startTime" label="Start Time"/> 
        </div> 
        <div class="mb-4"> 
          <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label> 
          <select id="timezone" v-model="timezone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"> 
            <option value="" disabled>Select timezone</option> 
            <option v-for="zone in timezones" :key="zone" :value="zone">{{ zone }}</option> 
          </select> 
        </div> 
      </div>
  
      <div v-if="eventType === 'multiple'" class="mt-4">
        <label class="block text-sm font-medium text-gray-700">Select Event Days</label>
        <div class="mt-2">
          <CalendarComponent v-model="eventDays" />
        </div>
        <div class="mb-4 mt-4"> 
          <label for="startTime" class="block text-sm font-medium text-gray-700">Start Time</label> 
          <TimeFrame id="startTime" v-model="startTime" label="Start Time"/> 
        </div> 
        <div class="mb-4"> 
          <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label> 
          <select id="timezone" v-model="timezone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"> 
            <option value="" disabled>Select timezone</option> 
            <option v-for="zone in timezones" :key="zone" :value="zone">{{ zone }}</option> 
          </select> 
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
        eventType: this.eventDetails.eventType || '', 
        startDate: this.eventDetails.startDate || '',
        timezone: this.eventDetails.timezone || '', 
        eventDays: this.eventDetails.eventDays || [], 
        startTime: this.eventDetails.startTime || '',
        timezones: [
          'UTC', 'GMT', 'EST', 'CST', 'MST', 'PST', 'AST', 'HST', 'AKST', 'IST'
        ]
      };
    },
    watch: { 
      eventType(val) { 
        console.log('Event Type updated:', val); 
      }, startDate(val) { 
        console.log('Start Date updated:', val); 
      }, 
      timezone(val) { 
        console.log('Timezone updated:', val); 
      }, 
      eventDays(val) { 
        console.log('Event Days updated:', val); 
      }, 
      startTime(val) { 
        console.log('Start Time updated:', val); 
      } 
    },
    computed: {
      isNextEnabled() {
        const isSingleValid = this.eventType === 'single' && this.startDate && this.startTime && this.timezone; 
        const isMultipleValid = this.eventType === 'multiple' && this.eventDays.length && this.startTime && this.timezone;
        console.log('Event Type:', this.eventType); 
        console.log('Single Valid:', isSingleValid); 
        console.log('Multiple Valid:', isMultipleValid); 
        console.log('Start Date:', this.startDate);
        console.log('Event Days:', this.eventDays); 
        console.log('Start Time:', this.startTime); 
        console.log('Timezone:', this.timezone); 
        return isSingleValid || isMultipleValid; 
      }
    },
    methods: { 
      handleNext() { 
        const updatedDetails = {
          eventType: this.eventType,
          timezone: this.timezone,
        };
        if (this.eventType === 'single') {
          updatedDetails.startDate = this.startDate;
          updatedDetails.startTime = this.startTime;
        } else if(this.eventType === 'multiple') {
          updatedDetails.eventDays = this.eventDays;
          updatedDetails.startTime = this.startTime;
        }
        this.$emit('next', updatedDetails);
      } 
    }
};
</script>
  