<template>
    <div>
        <h2>Input Emails</h2>
        <!-- Email input logic here --> 
        <button @click="nextStep">Next</button>
    </div>
</template>

<script>
export default {
    methods: {
        nextStep() {
            this.$router.push({ name: 'CustomizeForm' });
        }
    }
};
</script>