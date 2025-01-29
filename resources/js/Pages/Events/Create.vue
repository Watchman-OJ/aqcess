<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <!-- <h1 class="text-3xl font-bold mb-4">Create Event</h1> -->
            <div class="progress-container">
                <div class="progress-steps">
                    <div
                    v-for="(step, index) in steps"
                    :key="index"
                    :class="['progress-step', { 'active': index === currentStep }]"
                    >
                    {{ step }}
                    </div>
                </div>
            </div>

            <component 
            :is="currentStepComponent" 
            @next="handleNextStep" 
            @previous="previousStep"
            :event-details="eventDetails"
            @submit="submitEvent"
            />
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'; 
import EventTitle from './EventTitle.vue'; 
import EventCategory from './EventCategory.vue'; 
import EventSchedule from './EventSchedule.vue'; 
import EventLocation from './EventLocation.vue'; 
import ReviewSubmit from './ReviewSubmit.vue';
import axios from 'axios';

export default { 
    components: { 
        AuthenticatedLayout, 
        EventTitle, 
        EventCategory, 
        EventSchedule, 
        EventLocation, 
        ReviewSubmit, 

    }, 
    data() { 
        return { 
            steps: ['Title', 'Category', 'Scheduling', 'Location', 'Submit'], 
            currentStep: 0, 
            eventDetails: { 
                title: '', 
                category: '', 
                startDate: '', 
                startTime: '', 
                location: '', 
            }
        }; 
    },
    computed: { 
        progressWidth() { 
            const stepCount = this.steps.length - 1; 
            const progress = (this.currentStep / stepCount) * 100; 
            return `${progress}%`; 
        }, 
        currentStepComponent() { 
            switch (this.currentStep) { 
                case 0: return EventTitle; 
                case 1: return EventCategory; 
                case 2: return EventSchedule; 
                case 3: return EventLocation; 
                case 4: return ReviewSubmit; 
                default: 
                return EventTitle; 
            } 
        } 
    },
    methods: {
        handleNextStep(updatedDetails) {
            this.eventDetails = {...this.eventDetails, ...updatedDetails};
            this.nextStep();
        },
        nextStep() {
            console.log('Next Step Triggered');
            if (this.currentStep < this.steps.length -1) {
                this.currentStep++;
            }
        },
        previousStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
            }
        },
        submitEvent(eventDetails) {
            // Validate required fields
            if ( 
                !eventDetails.title || 
                !eventDetails.category || 
                !eventDetails.startDate ||
                !eventDetails.startTime ||
                !eventDetails.location 
            ) { 
                console.error('Validation Error: Missing required fields'); 
                return; 
            }

            console.log('Submitting Event Details:', eventDetails);
            axios.post('/events', eventDetails)
            .then(response => {
                console.log('Event creation response:', response);
                if (response.data.id) {
                    this.$router.push({
                    name: 'events.index',
                    params: { id: response.data.id },
                    query: { message: 'Event created successfully' }
                });
                } else {
                    console.error('Error creating event: No valid ID returned');
                }
                
            })
            .catch(error => {
                if (error.response) {
                    console.error('Error creating event:', error.response.data.errors);
                    console.error('Error response data:', error.response.data);
                } else {
                    console.error('Error creating event:', error.message);
                } 
            });
        }
    }
};
</script>

<style>
.progress-container {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}
.progress-steps {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}
.progress-step {
    flex: 1;
    text-align: center;
    color: #ccc;
    position: relative;
}
.progress-step::before {
    content: '';
    width: 10px;
    height: 10px;
    background-color: #ccc;
    border-radius: 50%;
    display: block;
    margin: 0 auto;
}
.progress-step.active {
    color: #4a90e2;
}
.progress-step.active::before {
    background-color: #4a90e2;
}
</style>