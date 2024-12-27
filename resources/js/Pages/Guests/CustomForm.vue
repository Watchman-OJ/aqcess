<template>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">{{ formTitle }}</h2>
        <form>
            <div v-for="(field, index) in fields" :key="index" class="mb-4">
                <label :for="field.name" class="block text-lg font-medium mb-1">{{ field.label }}</label>
                <input
                    v-if="field.type === 'text' || field.type === 'tel' || field.type === 'email' || field.type === 'url' || field.type === 'date' || field.type === 'time' || field.type === 'number'"
                    :id="field.name"
                    :type="field.type"
                    :placeholder="field.placeholder"
                    class="w-full p-2 border rounded"
                />
                <textarea
                    v-if="field.type === 'textarea'"
                    :id="field.name"
                    :placeholder="field.placeholder"
                    class="w-full p-2 border rounded"
                ></textarea>
                <select
                    v-if="field.type === 'select'"
                    :id="field.name"
                    class="w-full p-2 border rounded"
                >
                    <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
                </select>
            </div>
        </form>
    </div>
</template>

<script>
import { reactive, ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    props: {
        formId: { 
            type: String, 
            required: true 
        }
    },
    setup(props) {
        const fields = reactive([]);
        const formTitle = ref('Custom Form');

        onMounted(() => {
            fetchFormData();
        });

        function fetchFormData() {
            Inertia.get(`/api/forms/${props.formId}`, {}, {
                preserveState: true,
                onSuccess: (page) => {
                    const formData = page.props.formData;
                    formTitle.value = formData.title;
                    fields.push(...formData.fields);
                },
                onError: (error) => {
                    console.error('Error fetching form data:', error);
                }
            });
        }

        return {
            fields,
            formTitle,
        };
    }
};
</script>
