document.addEventListener('DOMContentLoaded', function() {
  const inputs = document.querySelectorAll('input.digit-only');

  inputs.forEach(function(input) {
    input.addEventListener('input', function() {
      // Remove all non-digit characters
      this.value = this.value.replace(/\D/g, '');

      // Limit length to 9 digits (maxlength attribute handles this mostly, but just in case)
      if (this.value.length > 9) {
        this.value = this.value.slice(0, 9);
      }

      // Validate: exactly 9 digits required
      if (this.value.length !== 9) {
        this.style.borderColor = 'red';
      } else {
        this.style.borderColor = '';
      }
    });

    // Optional: validate on blur as well
    input.addEventListener('blur', function() {
      if (this.value.length !== 9) {
        this.style.borderColor = 'red';
      } else {
        this.style.borderColor = '';
      }
    });
  });
});