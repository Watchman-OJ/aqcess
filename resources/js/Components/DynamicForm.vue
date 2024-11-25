<template>
    <div>
        <h1>{{ form.name }}</h1>
        <div v-for="(field, index) in fields" :key="index">
            <component :is="field.component" :id="field.id" :label="field.label" v-model="field.value" />
        </div>
        <button @click="submitForm">Submit</button>
    </div>
</template>

<script>
import axios from 'axios'; 
import TitleInput from './TitleInput.vue'; 
import DatePicker from './DatePicker.vue';

export default {
    components: {
        TitleInput,
        DatePicker,
    },
    props: ['form'],
    data() {
        return {
            fields: JSON.parse(this.form.fields),
        };
    },
    methods: {
        submitForm() {
            axios.post(`/api/forms/${this.form.id}`, { fields: this.fields }) 
            .then(response => { 
                console.log('Form submitted successfully', response.data); 
            })
            .catch(error => {
                console.error('Error submitting form:', error);
            });
        },
    },
};
</script>

