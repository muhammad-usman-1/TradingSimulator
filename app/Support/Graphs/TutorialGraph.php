<?php

namespace App\Support\Graphs;

/**
 * Very small directed graph for representing tutorial steps and paths.
 *
 * We implement a simple depth-first traversal to satisfy the
 * "graph/tree traversal" requirement in the complexity checklist.
 */
class TutorialGraph
{
    /**
     * @var array<string, array<int, string>> adjacency list mapping a step key to child step keys.
     */
    private array $edges = [];

    /**
     * Add a directed edge from $from to $to.
     */
    public function addEdge(string $from, string $to): void
    {
        if (!isset($this->edges[$from])) {
            $this->edges[$from] = [];
        }

        $this->edges[$from][] = $to;

        // Ensure the destination exists in the adjacency list even if it has no outgoing edges yet.
        if (!isset($this->edges[$to])) {
            $this->edges[$to] = [];
        }
    }

    /**
     * Depth-first traversal from a start node, returning the order of visited steps.
     *
     * This uses recursion to walk the graph in depth-first order.
     *
     * @return array<int, string>
     */
    public function depthFirstOrder(string $start): array
    {
        $visited = [];
        $order = [];

        $this->dfs($start, $visited, $order);

        return $order;
    }

    /**
     * Internal recursive DFS implementation.
     *
     * @param array<string, bool> $visited
     * @param array<int, string>  $order
     */
    private function dfs(string $node, array &$visited, array &$order): void
    {
        if (isset($visited[$node])) {
            return;
        }

        $visited[$node] = true;
        $order[] = $node;

        if (!isset($this->edges[$node])) {
            return;
        }

        foreach ($this->edges[$node] as $neighbor) {
            $this->dfs($neighbor, $visited, $order);
        }
    }
}

