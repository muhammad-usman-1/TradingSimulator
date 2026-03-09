<?php

namespace App\Support\Collections;

/**
 * Simple last-in-first-out (LIFO) stack implementation.
 *
 * This will be used for features like undoing recent actions or
 * navigating backwards through tutorial steps.
 */
class SimpleStack
{
    /**
     * @var array<int, mixed>
     */
    private array $items = [];

    public function push(mixed $value): void
    {
        $this->items[] = $value;
    }

    public function pop(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        $lastIndex = count($this->items) - 1;
        $value = $this->items[$lastIndex];
        unset($this->items[$lastIndex]);

        return $value;
    }

    public function peek(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->items[count($this->items) - 1];
    }

    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }
}

