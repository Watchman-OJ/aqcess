

<template>
    <AuthenticatedLayout>
        <div>
            <h2>Review and Submit</h2>
            <button @click="submitForm">Submit</button>
        </div>      
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    },
    methods: {
        submitForm() {
            // Submit form Logic
            this.$router.push({ name: 'GuestQrCode' });
        }
    }
}
</script>