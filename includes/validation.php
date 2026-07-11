<?php
function sanitize_input($value) {
    if (is_array($value)) {
        return array_map('sanitize_input', $value);
    }

    $value = trim((string) $value);
    return strip_tags($value);
}

function validate_required($value, $fieldName = 'This field') {
    $clean = sanitize_input($value);
    if ($clean === '') {
        return ['valid' => false, 'message' => $fieldName . ' is required.'];
    }

    return ['valid' => true, 'value' => $clean];
}

function validate_name($value) {
    $result = validate_required($value, 'Name');
    if (!$result['valid']) {
        return $result;
    }

    if (mb_strlen($result['value']) < 3) {
        return ['valid' => false, 'message' => 'Name must be at least 3 characters.'];
    }

    return ['valid' => true, 'value' => $result['value']];
}

function validate_email($value) {
    $result = validate_required($value, 'Email');
    if (!$result['valid']) {
        return $result;
    }

    if (!filter_var($result['value'], FILTER_VALIDATE_EMAIL)) {
        return ['valid' => false, 'message' => 'Please enter a valid email address.'];
    }

    return ['valid' => true, 'value' => strtolower($result['value'])];
}

function validate_password($value, $minLength = 8) {
    $result = validate_required($value, 'Password');
    if (!$result['valid']) {
        return $result;
    }

    if (mb_strlen($result['value']) < $minLength) {
        return ['valid' => false, 'message' => 'Password must be at least ' . $minLength . ' characters.'];
    }

    return ['valid' => true, 'value' => $result['value']];
}

function validate_phone($value) {
    if ($value === '' || $value === null) {
        return ['valid' => true, 'value' => ''];
    }

    $clean = sanitize_input($value);
    if (!preg_match('/^[\d\s\-+]{8,15}$/', $clean)) {
        return ['valid' => false, 'message' => 'Please enter a valid phone number.'];
    }

    return ['valid' => true, 'value' => $clean];
}

function validate_search($value) {
    return ['valid' => true, 'value' => sanitize_input($value)];
}
