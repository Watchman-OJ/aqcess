<template>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Enter Form Title</h2>
        <input 
            v-model="title" 
            type="text" 
            placeholder="Write the form title here..." 
            class="form-title-input" 
            required
        />
        <div class="flex justify-end mt-4">
            <button @click="nextStep" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Next</button>
        </div>
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

<style scoped>
.container {
    background-color: #f7fafc;
    padding: 32px;
    margin: 70px auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.form-title-input {
    width: 100%;
    padding: 0;
    border: none;
    border-bottom: 1px dotted #ccc;
    outline: none;
    background: transparent;
    font-family: 'Courier New', Courier, monospace;
    color: #333;
    margin-top: 10px;
}

.form-title-input::placeholder {
    color: #999;
}
</style>
