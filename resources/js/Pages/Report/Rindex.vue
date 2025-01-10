<template>
    <AuthenticatedLayout>
        <div>
            <h1 class="mt-7 text-3xl text-center font-bold">Report Issue</h1>
            <form @submit.prevent="submit">
                <textarea v-model="issue" placeholder="Describe the issue" class="w-full p-2 border border-gray-300 rounded"></textarea>
                <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded">Submit</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout,
    },
    data() {
        return {
            issue: '',
        };
    },
    methods: {
        submit() {
            // Submit the issue
            console.log(this.issue);
        }
    }
}
</script>
