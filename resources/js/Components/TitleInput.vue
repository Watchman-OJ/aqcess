<template>
    <div>
      <label :for="id">{{ label }}</label>
      <input 
      :id="id" 
      :value="value"
      @input="$emit('update:value', $event.target.value)"
      type="text" 
      class="form-input" 
      />
    </div>
  </template>
  
  <script>
  export default {
    props: ['id', 'label', 'value'],
  };
  </script>
  