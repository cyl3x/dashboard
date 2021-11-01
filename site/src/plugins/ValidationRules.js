export const validateEmail = (value) => {
    if (!/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i.test(value)) return 'Email must be a valid email';
    return true;
}

export const confirmPassword = (value, password) => {
    if (value != password) return 'Passwords do not match';
    return true;
}

export const validatePassword = (value) => {
    if (value && value.length < 8) return 'Password needs to be at least 8 Charactars long';
    else if (!/^.*(?=.{1,})(?=.*[a-z])(?=.*[A-Z]|[\u0080-\u024F])(?=.*[0-9])(?=.*[\d\x]).*$/.test(value)) return 'Password needs to has one capital Letter and a Number';

    return true
}

export const validateUsername = (value) => {
    if (!value) return "You can not have an empty Username";
    else if (value.length < 3 && value.length > 50) return 'Username can only between 3 & 50 Charactars';
    return true
}