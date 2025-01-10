<template>
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
            <input id="title" v-model="title" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            <div class="flex justify-end mt-4">
                <button @click="handleNext" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                    Next
                </button>
            </div>
        </div>     
</template>

<script>
export default {
    props: ['eventDetails'],
    data() {
        return {
            title: this.eventDetails ? this.eventDetails.title : ''
        };
    },
    methods: {
        handleNext() {
            this.$emit('next', { title: this.title });
        }
    }
};
</script>