<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4 rounded shadow-md bg-gray-100 dark:bg-gray-900">
            <h1 class="text-3xl font-bold mb-4">Guest Information for {{ guest.name }}</h1>
            <div>
                <p><strong>Sex:</strong> {{ guest.sex }}</p>
                <p><strong>Guest Cap:</strong> {{ guest.guest_cap }}</p>
                <p><strong>Note:</strong> {{ guest.note }}</p>
            </div>
            <div class="flex justify-between mt-4">
                <button @click="editGuest(guest)" 
                class="bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-700 ml-2">
                Edit Guest
                </button>
                <button @click="destroyGuest(guest.id)" 
                class="bg-red-500 text-white py-1 px-2 rounded hover:bg-red-700 ml-2">
                Delete Guest</button>
            </div>
    </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {
        AuthenticatedLayout,
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
    setup(props) {
        const guest = ref(props.guest);

        function editGuest() {
            Inertia.get(route('events.editGuest', { guest: guest.value.id}));
        }
        function destroyGuest() {
            if (confirm('Are you sure you want to delete this guest?')) {
                Inertia.delete(route('events.deleteGuest', { event: guest.value.event_id, guest: guest.value.id}));
            }
        }

        return { guest, editGuest, destroyGuest };
    }
};
</script>