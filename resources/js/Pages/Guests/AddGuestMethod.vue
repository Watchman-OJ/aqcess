<template>
    <AuthenticatedLayout>
        <div>
            <h2>Select Method</h2>
            <button @click="selectMethod('manual')">Manually</button> 
            <button @click="selectMethod('link')">Url Link</button>
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
        addGuestMethod(method) {
            if (method === 'manual') {
                this.$router.push({ name: 'CustomizeForm' });
            } else {
                this.$router.push({ name: 'InputEmails' });
            }
        }
    }
}
</script>