<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <div class="flex justify-between items-center mb-4">
                <!-- <Link :href="route('events.index')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 hover:text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link> -->
                <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert"> 
                    <strong class="font-bold">Success!</strong> 
                    <span class="block sm:inline">{{ successMessage }}</span> 
                    <span @click="closeMessage" class="absolute top-0 bottom-0 right-0 px-4 py-3"> 
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M14.348 5.652a1 1 0 1 0-1.414-1.414L10 7.586 7.066 4.652a1 1 0 0 0-1.414 1.414l2.934 2.934-2.934 2.934a1 1 0 0 0 1.414 1.414L10 12.414l2.934 2.934a1 1 0 1 0 1.414-1.414l-2.934-2.934 2.934-2.934z"/>
                        </svg> 
                    </span> 
                </div>
            </div>
            <Link :href="route('events.guestList', { event: event.id })">
                <button class="absoute top-0 right-0 mr-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Guest List
                </button>
            </Link>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h1 class="text-3xl font-bold"> {{ event.title }} </h1>
                <div v-if="event.eventType === 'single'" > 
                    <p><strong>Start Date:</strong> {{ event.startDate }}</p> 
                    <p><strong>Start Time:</strong> {{ event.startTime }}</p> 
                    <p><strong>Timezone:</strong> {{ event.timezone }}</p> 
                </div> 
                <div v-if="event.eventType === 'multiple'" > 
                    <p><strong>Days:</strong> {{ event.eventDays.join(', ') }}</p> 
                    <p><strong>Start Time:</strong> {{ event.startTime }}</p> 
                    <p><strong>Timezone:</strong> {{ event.timezone }}</p> 
                </div> 
                <p><strong>Location:</strong> {{ event.location }}</p> 
                <p><strong>Guests:</strong> {{ event.guests_count }}</p>
                <p class="mb-2"><strong>Partners:</strong> {{ event.partner_count }} </p>

                <div v-if="localQrCode" class="my-4">
                    <h3 class="text-xl font-semibold mb-2">QR Code</h3>
                    <img :src="localQrCode" alt="QR Code">
                </div>

                <div class="flex space-x-4 mt-4">
                    <Link :href="route('events.edit', event.id)">
                        <button class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                            Edit
                        </button>
                    </Link>
                    <button @click="deleteEvent" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Delete
                    </button>
                    
                    <button @click="scanEvent" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Scan
                    </button>
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
import GuestList from '../Guests/GuestList.vue';

export default {
    components: {
        Link,
        AuthenticatedLayout,
        GuestList,
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
        }
    },
    setup(props) {
        const event = ref(props.event);

        function deleteEvent() {
            if (confirm('Are you sure you want to delete this guest?')) {
                Inertia.delete(route('events.deleteEvent', { event: event.value.id}));
            }
        }

        const scanEvent = () => {
            // Logic for handling the scan action
        };

        return {
            event,
            deleteEvent,
            scanEvent,
        };
    },
};
</script>
