<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <FormTitle v-if="currentStep === 'formTitle'" @next="handleNext" />
            <CustomizeForm 
                v-if="currentStep === 'customizeForm'" 
                :event="event"
                :eventId="eventId"
                :formFields="formFields"
                @confirm="handleConfirm" 
            />
            <ReviewForm v-if="currentStep === 'reviewForm'" 
                :formTitle="formTitle" 
                :formFields="formFields" 
                @save="handleSave" 
            />
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FormTitle from './FormTitle.vue';
import CustomizeForm from './CustomizeForm.vue';
import ReviewForm from './ReviewForm.vue';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';

export default {
    components: {
        AuthenticatedLayout,
        FormTitle,
        CustomizeForm,
        ReviewForm
    },
    props: { 
        event: { 
            type: Object, 
            required: true 
    }, 
        eventId: { 
            type: Number, 
            required: true 
        } 
    },
    data() {
        return {
            currentStep: 'formTitle',
            formTitle: '',
            formFields: []
        };
    },
    methods: {
        handleNext(title) {
            this.formTitle = title;
            this.currentStep = 'customizeForm';
        },
        handleConfirm(fields) {
            this.formFields = fields;
            this.currentStep = 'reviewForm';
        },
        async handleSave() {
            try {
                const response = await axios.post(`/events/${this.eventId}/form-handler`,{
                    title: this.formTitle,
                    fields: this.formFields,
                    event_id: this.eventId
                });
                if (response.data.message === 'Form saved successfully') {
                    Inertia.get(`/events/${this.eventId}/select-form`);
                }       
            } catch (error) {
                console.error('Error saving form:', error);
            }
        }
    }
};
</script>
