// useValidation.js
import { ref } from 'vue';

export function useValidation() {
  const email = ref('');
  const password = ref('');
  const passwordConfirmation = ref('');
  const firstName = ref('');
  const lastName = ref('');
  const error = ref('');

  const validateLogin = () => {
    if (!email.value) {
      error.value = 'Email is required.';
      return false;
    }
    if (!password.value) {
      error.value = 'Password is required.';
      return false;
    }

    error.value = '';
    return true;
  };


  const validateRegister = () => {
    if (!firstName.value) {
      error.value = 'First name is required.';
      return false;
    }
    if (!lastName.value) {
      error.value = 'Last name is required.';
      return false;
    }

    if (!validateLogin()) {
    
      return false;
    }
    
    if (password.value !== passwordConfirmation.value) {
      error.value = 'Passwords do not match.';
      return false;
    }


    error.value = '';
    return true;
  };


  const validate = (isRegister = false) => {
    return isRegister ? validateRegister() : validateLogin();
  };

  return { email, password, passwordConfirmation, firstName, lastName, error, validate };
}
