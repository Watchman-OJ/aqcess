<template>
    <div class="container mx-auto p-4">
        <h2 class="text-3xl font-bold mb-6">Review and Submit</h2>
        <div>
            <h3 class="text-2xl font-bold mb-4">Event Details</h3>
            <pre>{{ event }}</pre>
        </div>
        <div>
            <h3 class="text-2xl font-bold mb-4">Selected Fields</h3>
            <pre>{{ selectedFields }}</pre>
        </div>
        <div class="flex justify-between mt-6">
            <button @click="$emit('previous')" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">Previous</button>
            <button @click="$emit('submit', guestDetails)" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Submit</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        event: {
            type: Object,
            required: true
        },
        selectedFields: {
            type: Array,
            required: true
        },
        guestDetails: {
            type: Object,
            default: () => ({})
        }
    },
    emits: ['previous', 'submit']
};
</script>
