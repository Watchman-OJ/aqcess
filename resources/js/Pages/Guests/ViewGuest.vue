<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4 rounded shadow-md bg-gray-100 dark:bg-gray-900">
            <h1 class="text-2xl font-bold mb-4">Guest Information for {{ guest.form_data.name }}</h1>
            <div class="guest-info">
                <div v-for="(value, key) in guest.form_data" :key="key">
                    <p><strong>{{ key }}:</strong> {{ value }}</p>
                </div>
            </div>
            <Link :href="route('guest.guestTicket', { event: event.id, guest: guest.id })" class="inline-block bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 mt-4 ml-4"> 
                View Ticket 
            </Link>
            <button @click="deleteGuest" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 mt-4">Delete Guest</button>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {
        AuthenticatedLayout,
        Link
    },
    props: {
        event: {
            type: Object,
            required: true
        },
        guest: {
            type: Object,
            required: true
        }
    },
    methods: {
        deleteGuest() {
            if (confirm('Are you sure you want to delete this guest?')) {
                Inertia.delete(route('events.deleteGuest', { guest: this.guest.id}));
            }
        }
    },
    // filters: {
    //     capitalize(value) {
    //         if (!value) return '';
    //         value = value.toString();
    //         return value.charAt(0).toUpperCase() + value.slice(1);
    //     }
    // }
};
</script>