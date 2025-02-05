<template>
    <div class="container mx-auto p-4">
        <label for="title" class="block mb-1 font-bold">Event Title</label>
        <input 
            id="title" 
            v-model="title" 
            type="text" 
            placeholder="Write the event title here..." 
            class="title-input" 
            required
         />
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

<style scoped>
.container {
    background-color: #f7fafc;
    padding: 32px;
    margin: 70px auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.title-input {
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

.title::placeholder {
    color: #999;
}
</style>
