<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <div class="flex justify-between items-center mb-4">
                <!-- <Link :href="route('events.index')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 hover:text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </Link> -->
                <h1 class="text-3xl font-bold"> {{ event.title }} </h1>
            </div>
            <Link :href="route('events.guestList', { event: event.id })">
                <button class="absoute top-0 right-0 mr-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Guest List
                </button>
            </Link>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <!-- Dynamically display event fields -->
                 <div v-for="(value, key) in event fields" :key="key" class="mb-2">
                    <strong>{{ key }}:</strong> {{ value }}
                 </div>
                <p class="mb-2"><strong>Guests:</strong> {{ event.guests_count }}</p>
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
import GuestList from './GuestList.vue';

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
            localQrCode: this.qrCode,
        };
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
