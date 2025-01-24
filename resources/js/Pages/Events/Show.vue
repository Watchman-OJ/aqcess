<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <h1 class="text-3xl font-bold">{{ event.title }}</h1>
                
                <div v-if="localQrCode" class="flex justify-center my-4">
                    <img :src="localQrCode" alt="QR Code" class="qr-code">
                </div>
                
                <div>
                    <p><strong>Location:</strong> {{ event.location }}</p> 
                    <p><strong>Start Date:</strong> {{ event.startDate }}</p> 
                    <p><strong>Start Time:</strong> {{ event.startTime }}</p> 

                    <div class="flex justify-between items-center mt-4">
                        <button @click="deleteEvent" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Delete
                        </button>
                        <Link :href="route('events.guestList', { event: event.id })">
                            <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Guest List
                            </button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        Link,
        AuthenticatedLayout,
    },
    props: {
        event: {
            type: Object,
            required: true
        },
        qrCode: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            successMessage: '',
            localQrCode: this.qrCode,
        };
    },
    mounted() { 
        const { message } = this.$route.query; 
        if (message) { 
            this.successMessage = message; 
            setTimeout(() => { 
                this.successMessage = ''; 
            }, 5000); 
        } 
    }, 
    methods: { 
        closeMessage() { 
            this.successMessage = ''; 
        },
        deleteEvent() {
            if (confirm('Are you sure you want to delete this event?')) {
                Inertia.delete(route('events.deleteEvent', { event: this.event.id }));
            }
        }
    },
};
</script>

<style scoped>
.container {
    max-width: 600px;
    margin: auto;
}

.text-center {
    text-align: center;
}

.flex {
    display: flex;
}

.items-center {
    align-items: center;
}

.justify-center {
    justify-content: center;
}

.justify-between {
    justify-content: space-between;
}

.mb-4 {
    margin-bottom: 1rem;
}

.mt-4 {
    margin-top: 1rem;
}

.bg-white {
    background-color: white;
}

.bg-green-600 {
    background-color: #16A34A;
}

.bg-green-700 {
    background-color: #15803D;
}

.bg-red-600 {
    background-color: #DC2626;
}

.bg-red-700 {
    background-color: #B91C1C;
}

.p-4 {
    padding: 1rem;
}

.px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
}

.py-2 {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
}

.rounded {
    border-radius: 0.375rem;
}

.qr-code {
    width: 150px;
    height: 150px;
}

.hover\:bg-green-700:hover {
    background-color: #15803D;
}

.hover\:bg-red-700:hover {
    background-color: #B91C1C;
}
</style>
