<template>
    <div>
      <label :for="id">{{ label }}</label>
      <input 
        :id="id" 
        :value="modelValue" 
        @input="$emit('update:modelValue', $event.target.value)"
        type="time" 
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
      />
    </div>
</template>
  
<script>
  export default {
    props: {
      id: {
        type: String,
        required: true
      },
      label: {
        type: String,
        required: true
      },
      modelValue: {
        type: String,
        default: ''
      }
    }
};
</script>
  