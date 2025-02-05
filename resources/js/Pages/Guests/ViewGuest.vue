<template>
    <AuthenticatedLayout>
        <div class="container mx-auto my-8 p-8 rounded shadow-lg bg-gray-200 dark:bg-gray-900">
            <h1 class="text-2xl font-bold mb-4 underline underline-offset-4 underline-custom">{{ guest.form_data.name }}</h1>
            <div class="guest-info mb-6">
                <ul class="info-list">
                    <li v-for="(value, key) in guest.form_data" :key="key" class="mb-2">
                        <p><strong>{{ key }}:</strong> {{ value }}</p>
                    </li>    
                </ul>
            </div>
            <Link :href="route('guest.guestTicket', { event: event.id, guest: guest.id })" class="inline-block bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 mt-4 ml-4"> 
                View Ticket 
            </Link>
            <button @click="deleteGuest" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 mt-4 ml-4">Delete Guest</button>
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
                Inertia.delete(route('events.deleteGuest', { event: this.event.id, guest: this.guest.id }));
            }
        }
    },
};
</script>

<style scoped>
.container {
    background-color: #f7fafc;
    padding: 32px;
    margin: 70px auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.guest-info {
    margin-bottom: 24px;
}

.info-list {
    list-style-type: none;
    padding: 0;
}

.info-list li {
    position: relative;
    margin-bottom: 16px;
    padding-bottom: 8px;
}

.info-list li::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 1px;
    background-color: #ccc; 
}

.underline-custom {
    text-decoration-color: #ccc; 
}

button,
a.inline-block {
    margin-left: 4px;
}
</style>
