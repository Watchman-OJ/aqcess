<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-3xl font-bold">Guest List</h1>
                <Link v-if="hasGuests" :href="route('events.selectForm', event.id)">
                    <button class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                        Add Guest
                    </button>
                </Link>
            </div>

            <div v-if="!hasGuests" class="flex flex-col items-center justify-center h-full">
                <p class="mt-4 text-lg text-gray-500">No Guest. Click below to add your first or generate unique ID!</p>
                <div class="flex space-x-4 mt-4">
                    <Link :href="route('events.selectForm', event.id)">
                        <button class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                            Add Guest
                        </button>
                    </Link>
                </div>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <ul class="guests">
                    <li v-for="guest in guests" :key="guest.id" @click="viewGuest(guest)" class="guest-list cursor-pointer">
                        <div class="flex justify-between items-center">
                            <p><strong>{{ guest.form_data.name }}</strong></p>
                        </div>
                        <div class="text-sm text-gray-400">
                            <p class="text-sm text-gray-400">{{ formatDateTime(guest.created_at) }}</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { format } from 'date-fns';

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
        guests: {
            type: Array,
            required: true
        },
        hasGuests: {
            type: Boolean,
            required: true
        },
    },
    methods: {
        viewGuest(guest) {
            Inertia.get(route('events.viewGuest', { event: this.event.id, guest: guest.id }));
        },
        formatDateTime(dateTime) {
            return format(new Date(dateTime), 'MMM dd, yyyy h:mm a');
        }
    }
}
</script>

<style scoped>
.container {
    background-color: #f7fafc;
    padding: 32px;
    margin: 70px auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.guests {
    list-style-type: none;
    padding: 0;
    padding: 10px;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.guest-list {
    position: relative;
    padding: 10px 0;
}

.guest-list::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 1px;
    background-color: #ccc;
}
</style>
