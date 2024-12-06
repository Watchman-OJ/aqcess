<template>
    <h2>Customize Form</h2>
    <!-- Form customization logic here --> 
    <button @click="nextStep">Next</button>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    },
    methods: {
        nextStep() {
            this.$router.push({ name: 'GuestFormFields' });
        }
    }
};
</script>