<template>
    <AuthenticatedLayout>
        <h2>Fill Form Fields</h2>
        <!-- Form fields rendering logic here --> 
        <button @click="nextField">Next</button>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout
    },
    methods: {
        nextField() {
            // Logic to proceed to next field
        },
        finalizeForm() {
            this.$router.push({ name: 'ReviewDetails'})
        }
    }
}
</script>