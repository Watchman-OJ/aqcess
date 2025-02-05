<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: {
        AuthenticatedLayout,
        Head,
        Link
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
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Upcoming Events
                </h2>
                <Link :href="route('events.create')">
                    <button class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                        Create Event
                    </button>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div v-if="!hasEvents" class="flex flex-col items-center justify-center h-full">
                <p class="mt-4 text-lg text-gray-500">No events found. Click below to create your first event!</p>
                <Link :href="route('events.create')">
                    <button class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 mt-4">
                        Create Event
                    </button>
                </Link>
            </div>

            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="event in events" :key="event.id" class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 cursor-pointer">
                        <Link :href="route('events.show', event.id)">
                            <h2 class="text-xl font-bold mb-2">{{ event.title }}</h2>
                            <p class="text-gray-600"><strong>Location:</strong> {{ event.location }}</p>
                            <p class="text-gray-600"><strong>Date:</strong> {{ event.startDate }}</p>
                            <p class="text-gray-600"><strong>Time:</strong> {{ event.startTime }}</p>
                        </Link>             
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.grid {
    gap: 1.5rem; 
}

.bg-white {
    background-color: #ffffff;
}

.p-4 {
    padding: 1rem;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.shadow-md {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.hover\:shadow-lg:hover {
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

.transition-shadow {
    transition: box-shadow 0.2s;
}

.cursor-pointer {
    cursor: pointer;
}

.text-xl {
    font-size: 1.25rem;
}

.font-bold {
    font-weight: 700;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.text-gray-600 {
    color: #4a5568;
}
</style>
