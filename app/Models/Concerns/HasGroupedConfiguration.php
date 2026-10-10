<?php

namespace App\Models\Concerns;

trait HasGroupedConfiguration
{
    abstract protected function groupedConfigurationColumn(): string;

    /**
     * @return array<string, mixed>
     */
    abstract protected function groupedConfigurationDefaults(): array;

    /**
     * @return array<string, 'boolean'|'integer'|'float'|'string'>
     */
    protected function groupedConfigurationTypes(): array
    {
        return [];
    }

    private function getConfigurationArray(): array
    {
        $column = $this->groupedConfigurationColumn();
        $raw = $this->attributes[$column] ?? null;

        if (is_array($raw)) {
            return $raw;
        }

        if (is_string($raw) && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    /**
     * Return every grouped field as flat form data, including defaults.
     *
     * @return array<string, mixed>
     */
    public function groupedConfigurationForForm(): array
    {
        return array_replace($this->groupedConfigurationDefaults(), $this->getConfigurationArray());
    }

    /**
     * Remove grouped fields from a form payload and persist them in the JSON attribute.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed> Remaining relational attributes.
     */
    public function fillGroupedConfigurationFromForm(array $data): array
    {
        $configuration = $this->getConfigurationArray();

        foreach ($this->groupedConfigurationDefaults() as $key => $default) {
            if (! array_key_exists($key, $data)) {
                continue;
            }

            $configuration[$key] = $this->normalizeGroupedConfigurationValue($key, $data[$key]);
            unset($data[$key]);
        }

        parent::setAttribute($this->groupedConfigurationColumn(), $configuration);

        return $data;
    }

    public function getAttribute($key)
    {
        if (is_string($key) && array_key_exists($key, $this->groupedConfigurationDefaults())) {
            $configuration = $this->getConfigurationArray();

            return $configuration[$key] ?? $this->groupedConfigurationDefaults()[$key];
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if (is_string($key) && array_key_exists($key, $this->groupedConfigurationDefaults())) {
            $column = $this->groupedConfigurationColumn();
            $configuration = $this->getConfigurationArray();
            $configuration[$key] = $this->normalizeGroupedConfigurationValue($key, $value);

            return parent::setAttribute($column, $configuration);
        }

        return parent::setAttribute($key, $value);
    }

    public function isFillable($key)
    {
        if (is_string($key) && array_key_exists($key, $this->groupedConfigurationDefaults())) {
            return true;
        }

        return parent::isFillable($key);
    }

    protected function fillableFromArray(array $attributes)
    {
        $groupedKeys = array_keys($this->groupedConfigurationDefaults());
        $allFillable = array_merge($this->getFillable(), $groupedKeys);

        if (count($allFillable) > 0 && ! static::$unguarded) {
            return array_intersect_key($attributes, array_flip($allFillable));
        }

        return $attributes;
    }

    private function normalizeGroupedConfigurationValue(string $key, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($this->groupedConfigurationTypes()[$key] ?? 'string') {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOL),
            'integer' => (int) $value,
            'float' => (float) $value,
            default => (string) $value,
        };
    }
}
