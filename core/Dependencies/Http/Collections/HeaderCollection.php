<?php

namespace Saas\Project\Dependencies\Http\Collections;

class HeaderCollection
{
    private array $collector;

    public function __construct(array $headers)
    {
        foreach ($headers as $header => $values) {
            $this->collector[$header] = implode(", ", $values);
        }
    }

    public function add(string $header, string $value): HeaderCollection
    {
        $this->collector[$header] = $value;
        return $this;
    }

    public function all(): array
    {
        return $this->collector;
    }

    public function get(string $header): ?string
    {
        return $this->collector[$header] ?? null;
    }
}
