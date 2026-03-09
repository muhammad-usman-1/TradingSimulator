<?php

namespace App\Http\Controllers;

use App\Models\TutorialProgress;
use App\Models\User;
use App\Support\Graphs\TutorialGraph;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Simple tutorial flow guiding a beginner through a few key concepts.
 *
 * Uses TutorialProgress to track completion and TutorialGraph to define the path.
 */
class TutorialController extends Controller
{
    /**
     * Static list of tutorial steps.
     *
     * @return array<string, array<string, string>>
     */
    private function steps(): array
    {
        return [
            'intro' => [
                'title' => 'Introduction to the simulator',
                'body' => 'This simulator lets you practise trading with virtual money so you can learn safely. '
                    . 'You will see how prices move and how your balance changes before you ever risk real cash.',
            ],
            'candles' => [
                'title' => 'What is a candlestick?',
                'body' => 'Each candlestick shows how price moved in a short period of time: open, high, low, and close. '
                    . 'Green candles close above the open price, red candles close below.',
            ],
            'risk' => [
                'title' => 'Basic risk management',
                'body' => 'Never risk too much of your balance on a single trade. Beginners often lose huge amounts by '
                    . 'placing very large positions in volatile assets like crypto.',
            ],
            'practice' => [
                'title' => 'Practice assignment',
                'body' => 'Open the practice market, place a few small trades, and then check your session summary to see '
                    . 'how your decisions changed your balance.',
            ],
        ];
    }

    private function buildGraph(): TutorialGraph
    {
        $graph = new TutorialGraph();
        $graph->addEdge('intro', 'candles');
        $graph->addEdge('candles', 'risk');
        $graph->addEdge('risk', 'practice');

        return $graph;
    }

    public function index(Request $request): View
    {
        $userId = $request->session()->get('user_id');
        /** @var User $user */
        $user = User::findOrFail($userId);

        $steps = $this->steps();
        $graph = $this->buildGraph();
        $order = $graph->depthFirstOrder('intro');

        $progress = TutorialProgress::where('user_id', $user->id)->get()
            ->keyBy('step_key');

        return view('tutorial.index', [
            'order' => $order,
            'steps' => $steps,
            'progress' => $progress,
        ]);
    }

    public function show(Request $request, string $stepKey): View
    {
        $steps = $this->steps();

        if (!isset($steps[$stepKey])) {
            abort(404);
        }

        return view('tutorial.step', [
            'stepKey' => $stepKey,
            'step' => $steps[$stepKey],
        ]);
    }

    public function complete(Request $request, string $stepKey): RedirectResponse
    {
        $userId = $request->session()->get('user_id');
        /** @var User $user */
        $user = User::findOrFail($userId);

        $steps = $this->steps();

        if (!isset($steps[$stepKey])) {
            abort(404);
        }

        TutorialProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'step_key' => $stepKey,
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );

        $graph = $this->buildGraph();
        $order = $graph->depthFirstOrder('intro');

        // Find the next step in the path.
        $next = null;
        for ($i = 0; $i < count($order); $i++) {
            if ($order[$i] === $stepKey && isset($order[$i + 1])) {
                $next = $order[$i + 1];
                break;
            }
        }

        if ($next !== null) {
            return redirect()->route('tutorial.step', ['step' => $next])
                ->with('status', 'Step marked as complete. Moving to the next topic.');
        }

        return redirect()->route('tutorial.index')
            ->with('status', 'You have completed the learning path. Try another practice session in the market.');
    }
}

