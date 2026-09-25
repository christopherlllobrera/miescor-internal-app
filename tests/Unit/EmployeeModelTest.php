<?php

use App\Models\Employee;

test('ItemPict attribute is hidden from serialization', function () {
    $employee = new Employee([
        'EmpNo' => 'TEST001',
        'EmpFName' => 'John',
        'EmpLName' => 'Doe',
        'ItemPict' => "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x01\x00`\x00`\x00\x00\xFF\xDB\x00C\x80\x81", // Invalid UTF-8 binary bytes
    ]);

    expect($employee->getHidden())->toContain('ItemPict');
    expect(array_key_exists('ItemPict', $employee->toArray()))->toBeFalse();
    expect(array_key_exists('ItemPict', $employee->attributesToArray()))->toBeFalse();

    // Verify json_encode does not throw JsonException for malformed UTF-8
    $json = json_encode($employee->toArray(), JSON_THROW_ON_ERROR);
    expect($json)->toBeString();
});
