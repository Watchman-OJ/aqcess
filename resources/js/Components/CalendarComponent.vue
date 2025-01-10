<template>
    <div>
        <Datepicker
        v-model="selectedDates"
        multiple
        range
        :is-range="true"
        @update:modelValue="updateDates"
        />
    </div>
</template>

<script>
import { ref, watch } from 'vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

export default {
    components: {
        Datepicker
    },
    props: {
        modelValue: {
            type: Array,
            default: () => []
        }
    },
    setup(props, { emit }) {
        const selectedDates = ref(props.modelValue);

        watch(selectedDates, (newDates) => {
            console.log('New Dates:', newDates);
            emit('update:modelValue', newDates);
        });

        const updateDates = (dates) => {
            console.log('Updating Dates:', dates);
            selectedDates.value = Array.isArray(dates) ? dates : [dates];
        };

        return {
            selectedDates,
            updateDates
        };
    }
};
</script>