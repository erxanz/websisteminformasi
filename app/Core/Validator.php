<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Simple input validator. Rules are declared as "required|min:3|max:20".
 */
final class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    /** @var array<string, mixed> */
    private array $data;

    /** @var array<string, string> */
    private array $labels;

    /** @param array<string, mixed> $data @param array<string, string> $labels */
    private function __construct(array $data, array $labels)
    {
        $this->data   = $data;
        $this->labels = $labels;
    }

    /** @param array<string, string> $rules @param array<string, string> $labels */
    /** @param array<string, mixed> $data @param array<string, string> $rules @param array<string, string> $labels */
    public static function make(array $data, array $rules, array $labels = []): self
    {
        $validator = new self($data, $labels);

        foreach ($rules as $field => $ruleString) {
            foreach (explode('|', $ruleString) as $rule) {
                $validator->apply($field, $rule);
            }
        }

        return $validator;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        return $this->errors === [] ? null : reset($this->errors);
    }

    private function apply(string $field, string $rule): void
    {
        $value = $this->data[$field] ?? null;
        $value = is_string($value) ? trim($value) : $value;
        $label = $this->labels[$field] ?? ucfirst($field);

        if ($rule === 'required') {
            if ($value === null || $value === '') {
                $this->errors[$field] = $label . ' wajib diisi.';
            }

            return;
        }

        // Remaining rules are skipped once the field already failed.
        if (isset($this->errors[$field]) || $value === null || $value === '') {
            return;
        }

        if ($rule === 'email' && filter_var((string) $value, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[$field] = $label . ' harus berupa alamat email yang valid.';
        } elseif ($rule === 'numeric' && !is_numeric($value)) {
            $this->errors[$field] = $label . ' hanya boleh berisi angka.';
        } elseif ($rule === 'alphanumeric' && !ctype_alnum((string) $value)) {
            $this->errors[$field] = $label . ' hanya boleh berisi huruf dan angka.';
        } elseif (str_starts_with($rule, 'min:')) {
            $min = (int) substr($rule, 4);
            if (mb_strlen((string) $value) < $min) {
                $this->errors[$field] = sprintf('%s minimal %d karakter.', $label, $min);
            }
        } elseif (str_starts_with($rule, 'max:')) {
            $max = (int) substr($rule, 4);
            if (mb_strlen((string) $value) > $max) {
                $this->errors[$field] = sprintf('%s maksimal %d karakter.', $label, $max);
            }
        }
    }
}
