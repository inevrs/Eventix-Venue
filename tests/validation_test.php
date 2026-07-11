<?php
require_once __DIR__ . '/../includes/validation.php';

$tests = [];

$tests[] = ['sanitize_input trims and strips tags', sanitize_input("  <b>Jane</b>  ") === 'Jane'];
$tests[] = ['validate_email accepts valid addresses', validate_email('user@example.com')['valid']];
$tests[] = ['validate_email rejects invalid addresses', !validate_email('bad-email')['valid']];
$tests[] = ['validate_password enforces minimum length', !validate_password('short')['valid']];
$tests[] = ['validate_name enforces minimum length', !validate_name('Jo')['valid']];
$tests[] = ['validate_phone accepts Malaysian style numbers', validate_phone('+60123456789')['valid']];

foreach ($tests as $index => $test) {
    if (!$test[1]) {
        fwrite(STDERR, "Validation test failed: {$test[0]}\n");
        exit(1);
    }
}

fwrite(STDOUT, "All validation tests passed\n");
