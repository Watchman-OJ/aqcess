<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-3xl font-bold mb-4">Create Event</h1>
            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input id="title" v-model="form.title" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>

                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input id="date" v-model="form.date" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>

                <div class="mb-4">
                    <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
                    <input id="time" v-model="form.time" type="time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div class="mb-4">
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input id="location" v-model="form.location" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Short Description</label>
                    <textarea id="description" v-model="form.description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Check-in Option</label>
                    <select v-model="form.check_in_option" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="Pass">Pass</option>
                        <option value="Qr Code">Qr Code</option>
                    </select>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout,
    },
    setup() {
        const form = useForm({
            title: '',
            date: '',
            time: '',
            location: '',
            description: '',
            check_in_option: 'Pass',
        });

        function submitForm() {
            form.post(route('events.store'));
        }

        return {
            form,
            submitForm,
        };
    },
};
</script>
