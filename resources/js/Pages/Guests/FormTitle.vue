<template>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Enter Form Title</h2>
        <input v-model="title" type="text" placeholder="Enter form title" class="border rounded px-4 py-2 w-full mb-4" />
        <button @click="nextStep" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Next</button>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    setup(_, { emit }) {
        const title = ref('');

        function nextStep() {
            if (title.value.trim()) {
                emit('next', title.value);
            } else {
                alert('Please enter a form title.');
            }
        }

        return {
            title,
            nextStep
        };
    }
};
</script>
