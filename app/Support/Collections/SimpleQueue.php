<?php

namespace App\Support\Collections;

/**
 * Simple first-in-first-out (FIFO) queue implementation.
 *
 * This will be used for order processing and queued price updates
 * so that you can demonstrate queue operations in the simulator.
 */
class SimpleQueue
{
    /**
     * @var array<int, mixed>
     */
    private array $items = [];

    public function enqueue(mixed $value): void
    {
        $this->items[] = $value;
    }

    public function dequeue(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        $first = $this->items[0];

        // Manually shift elements left to avoid relying on array_shift.
        $count = count($this->items);
        for ($i = 0; $i < $count - 1; $i++) {
            $this->items[$i] = $this->items[$i + 1];
        }

        unset($this->items[$count - 1]);

        return $first;
    }

    public function peek(): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->items[0];
    }

    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }

    public function size(): int
    {
        return count($this->items);
    }
}

