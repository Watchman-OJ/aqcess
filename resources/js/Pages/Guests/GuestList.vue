<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4">
            <h1 class="text-3xl text-center font-bold mb-4">Guest List</h1>
            <div v-if="!hasGuests" class="flex flex-col items-center justify-center h-full">
                <p class="mt-4 text-lg text-gray-500">No Guest. Click below to add your first or generate unique ID!</p>
                <div class="flex space-x-4 mt-4">
                    <Link :href="route('events.addGuestMethod', event.id)"> 
                        <button class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700"> 
                            Add Guest 
                        </button> 
                    </Link>
                </div>
            </div>
            
            
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link :href="route('events.addGuestMethod', event.id)"> 
                    <button class="absolute top-12 right-2 bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 mt-4"> 
                        Add Guest 
                    </button> 
                </Link>
                <ul>
                    <li v-for="guest in guests" :key="guest.id" class="mt-4" >
                        <div class="cursor-pointer" @click="viewGuest(guest)">
                            <p><strong>{{ guest.name }}</strong></p>
                            <p v-if="guest.checked_in_at">Checked in at: {{ guest.checked_in_at }}</p>
                        </div>
                        <div class="flex items-center">
                            <span v-if="guest.checked_in_at" class="text-green-500"> <!--check mark--> </span>
                        </div>
                    </li>
                </ul>
                
            </div>
        </div>
    </AuthenticatedLayout>
</template> 

<script>
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddGuestMethod from '../Guests/AddGuestMethod.vue';

export default {
    components: {
        Link,
        AuthenticatedLayout,
        AddGuestMethod
    },
    props: {
        event: {
            type: Object,
            required: true
        },
        guests: {
            type: Array,
            required: true
        },
        hasGuests: {
            type: Boolean,
            required: true
        },    
    },
    methods: {
        viewGuest(guest) {
            Inertia.get(route('events.viewGuest', { event: this.event.id, guest: guest.id }));
        }          
    }

}
</script>
