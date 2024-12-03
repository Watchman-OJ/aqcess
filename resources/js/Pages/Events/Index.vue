<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-3xl text-center font-bold mb-4">Events</h1>
            <div v-if="!hasEvents" class="flex flex-col items-center justify-center h-full">
                <p class="mt-4 text-lg text-gray-500">No events found. Click below to create your first event!</p>
                <Link :href="route('events.create')">
                    <button class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 mt-4">
                        Create Event
                    </button>
                </Link>
            </div>
              
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link :href="route('events.create')">
                    <button class="absolute top-12 right-2 bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 mt-4">
                        Create Event
                    </button>
                </Link> 
                <div v-for="event in events" :key="event.id" 
                class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 cursor-pointer">
                <Link :href="route('events.show', event.id)">
                    <h2 class="text-xl font-bold mb-2">{{ event.title }}</h2>
                    <p class="text-gray-600"><strong>Time:</strong> {{ event.startTime }}</p>
                    <p class="text-gray-600"><strong>Location:</strong> {{ event.location }}</p>
                </Link>             
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
export default {
    components: {
        Link,
        AuthenticatedLayout
    },
    props: {
        events: {
            type: Array,
            required: true
        },
        hasEvents: {
            type: Boolean,
            required: true
        }
    },
    data() { 
        return { 
            selectedEvent: null, 
        }; 
    }
}
</script>
