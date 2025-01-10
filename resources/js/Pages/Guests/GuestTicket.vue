<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4 rounded shadow-md bg-gray-100 dark:bg-gray-900">
            <div class="ticket">
                <div class="ticket-header flex items-center justify-between">
                    <!-- <img :src="appLogo" alt="App Logo" class="logo"> -->
                    <h2 class="title">Event Ticket</h2>
                </div>
                <h3> {{ event.title }} </h3>
                <div v-if="localQrCode" class="my-4">
                    <img :src="localQrCode" alt="QR Code">
                </div>
                <div class="ticket-body">
                    <h3>Date</h3>
                    <p> {{ event.startDate }} </p>
                    <h3>Time</h3>
                    <p> {{ event.startTime }} </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';

export default {
    components: {
        AuthenticatedLayout
    },
    props: {
        event: {
            type: Object,
            required: true
        },
        guest: {
            type: Object,
            required: true
        },
        // appLogo: {
        //     type: String,
        //     required: true
        // },
        qrCode: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            localQrCode: this.qrCode,
        };
    },
    mounted() {
        console.log("Qr Code received:", this.qrCode);
    },
};
</script>

<style scoped>
.ticket {
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fff;
    padding: 16px;
    max-width: 400px;
    margin: 0 auto;
}

.ticket-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    width: 50px;
    height: 50px;
}

.title {
    font-size: 24px;
    font-weight: bold;
}

.ticket-body {
    margin-top: 16px;
}

.qr-code {
    text-align: center;
    margin-top: 16px;
}
</style>
