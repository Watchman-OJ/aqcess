<template>
    <AuthenticatedLayout>
        <div>
            <h2>Input Emails</h2>
            <!-- Email input logic here --> 
            <button @click="nextStep">Next</button>
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
        nextStep() {
            this.$router.push({ name: 'CustomizeForm' });
        }
    }
};
</script>