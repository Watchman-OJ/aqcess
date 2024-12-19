<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <customize-form
                :event="event"
                :eventId="eventId"
                @confirm="handleConfirm"
            />
            <new-form
                v-if="currentStep === 'newForm'"
                :eventId="eventId"
                :formFields="formFields"
                @submit="handleSubmit"
            />
            <review-details
                v-if="currentStep === 'review'"
                :formFields="formFields"
                :guestDetails="guestDetails"
            />
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CustomizeForm from './CustomizeForm.vue';
import NewForm from './NewForm.vue';
import ReviewDetails from './ReviewDetails.vue';
import { ref } from 'vue';

export default {
    components: {
        AuthenticatedLayout,
        CustomizeForm,
        NewForm,
        ReviewDetails
    },
    props: {
        eventId: {
            type: Number,
            required: true
        },
        event: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const currentStep = ref('customize');
        const formFields = ref([]);
        const guestDetails = ref({});

        function handleConfirm(fields) {
            formFields.value = fields;
            currentStep.value = 'newForm';
        }

        function handleSubmit(details) {
            guestDetails.value = details;
            currentStep.value = 'review';
        }

        return {
            currentStep,
            formFields,
            guestDetails,
            handleConfirm,
            handleSubmit
        };
    }
};
</script>
