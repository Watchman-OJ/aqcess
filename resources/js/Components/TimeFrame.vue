<template>
  <div class="container mx-auto p-4">
      <label :for="id" class="block mb-1 font-bold">{{ label }}</label>
      <input 
          :id="id" 
          :value="modelValue" 
          @input="$emit('update:modelValue', $event.target.value)"
          type="time" 
          class="time-input"
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

<style scoped>
.container {
  background-color: #f7fafc;
  padding: 32px;
  margin: 70px auto;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

.time-input {
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

.time-input::placeholder {
  color: #999;
}
</style>
