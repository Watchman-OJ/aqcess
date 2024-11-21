<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4 rounded shadow-md bg-gray-100 dark:bg-gray-900">
            <h1 class="text-3xl font-bold mb-4">Add Guest to {{ event.title }}</h1>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" v-model="form.name" id="name" class="mt-1 block w-full" required>
                </div>
                <div class="mb-4">
                    <label for="sex" class="block text-sm font-medium text-gray-700">Sex</label>
                    <select v-model="form.sex" id="sex" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                        <option value="">Select Sex</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="guest_cap" class="block text-sm font-medium text-gray-700">Guest Cap</label>
                    <input type="number" v-model="form.guest_cap" id="guest_cap" class="mt-1 block w-full" required>
                </div>
                <div class="mb-4">
                    <label for="note" class="block text-sm font-medium text-gray-700">Note</label>
                    <textarea v-model="form.note" id="note" class="mt-1 block w-full"></textarea>
                </div>
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700">Add</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestQrCode from '@/Pages/Events/GuestQrCode.vue';

export default {
    components: {
        AuthenticatedLayout,
        GuestQrCode
    },
    props: {
        event: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const form = useForm({
            name: '',
            sex: '',
            guest_cap: '',
            note: ''
        });

        function submit() {
            form.post(route('events.storeGuest', props.event.id), {
                onSuccess: (page) => {
                    const guestId = page.props.guest.id;
                    Inertia.get(route('events.guestQrCode', { event: props.event.id, guest: guestId }));
                },
                onError: () => {
                    console.error('Error submitting form', form.errors);
                }
            });
        }

        return { form, submit };
    }
};
</script>
