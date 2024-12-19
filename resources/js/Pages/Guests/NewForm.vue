<template>
        <div class="container mx-auto p-4">
            <h2 class="text-2xl font-bold mb-4">Fill in Your Details</h2>

            <form @submit.prevent="submitForm">
                <div v-for="field in formFields" :key="field.name" class="mb-4">
                    <label :for="field.name" class="block text-lg font-medium text-gray-700">{{ field.label }}</label>

                    <!-- Text, Email, Telephone, URL, Password, Number, Date, Time, Color inputs -->
                    <input v-if="['text', 'email', 'tel', 'url', 'password', 'number', 'date', 'time', 'color'].includes(field.type)" 
                           :id="field.name" 
                           v-model="guestDetails[field.name]" 
                           :type="field.type" 
                           :placeholder="field.placeholder" 
                           class="mt-1 p-2 block w-full border-gray-300 rounded-md" />

                    <!-- Textarea input -->
                    <textarea v-if="field.type === 'textarea'"
                              :id="field.name" 
                              v-model="guestDetails[field.name]" 
                              :placeholder="field.placeholder" 
                              class="mt-1 p-2 block w-full border-gray-300 rounded-md"></textarea>

                    <!-- Select input -->
                    <select v-if="field.type === 'select'"
                            :id="field.name" 
                            v-model="guestDetails[field.name]" 
                            class="mt-1 p-2 block w-full border-gray-300 rounded-md">
                        <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
                    </select>

                    <!-- Checkbox input -->
                    <div v-if="field.type === 'checkbox'" class="mt-2">
                        <label v-for="option in field.options" :key="option" class="block text-lg font-medium text-gray-700">
                            <input type="checkbox" v-model="guestDetails[field.name]" :value="option" class="mr-2">
                            {{ option }}
                        </label>
                    </div>

                    <!-- Radio input -->
                    <div v-if="field.type === 'radio'" class="mt-2">
                        <label v-for="option in field.options" :key="option" class="block text-lg font-medium text-gray-700">
                            <input type="radio" :name="field.name" v-model="guestDetails[field.name]" :value="option" class="mr-2">
                            {{ option }}
                        </label>
                    </div>

                    <!-- Range input -->
                    <input v-if="field.type === 'range'" 
                           :id="field.name" 
                           v-model="guestDetails[field.name]" 
                           type="range" 
                           :min="field.min" 
                           :max="field.max" 
                           :step="field.step" 
                           class="mt-1 p-2 block w-full border-gray-300 rounded-md" />
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">Submit</button>
                </div>
            </form>
        </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { Inertia } from '@inertiajs/inertia';

export default {
    props: {
        formFields: {
            type: Array,
            required: true
        },
        eventId: {
            type: Number,
            required: true
        }
    },
    setup(props) {
        const guestDetails = ref({});
        const router = useRouter();

        function submitForm() {
            console.log('Submitting guest details:', guestDetails.value);
            Inertia.post(`/events/${props.eventId}/guests`, guestDetails.value)
                .then(() => {
                    router.push({ name: 'GuestQrCode', params: { guestDetails: guestDetails.value } });
                })
                .catch(error => {
                    console.error('Error submitting guest details:', error);
                });
        }

        return {
            guestDetails,
            submitForm
        };
    }
};
</script>

