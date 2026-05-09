<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    public function index()
    {
        $polls = Auth::user()->polls()->latest()->paginate(10);
        return view('polls.index', compact('polls'));
    }

    public function create()
    {
        return view('polls.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $validated['is_active'] ?? false;
        $poll = Auth::user()->polls()->create($validated);

        // 🔥 Меняем редирект на конструктор
        return redirect()->route('polls.build', $poll)->with('success', 'Опрос создан! Теперь добавьте вопросы.');
    }

    /**
     * 🔥 Новый метод: страница конструктора
     */
    public function build(Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }
        
        $poll->load('questions');
        
        return view('polls.build', compact('poll'));
    }

    public function show(Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }
        return view('polls.show', compact('poll'));
    }

    public function edit(Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }
        return view('polls.edit', compact('poll'));
    }

    public function update(Request $request, Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $validated['is_active'] ?? false;
        $poll->update($validated);

        return redirect()->route('polls.index')->with('success', 'Опрос обновлён!');
    }

    public function destroy(Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }

        $poll->delete();
        return redirect()->route('polls.index')->with('success', 'Опрос удалён!');
    }
}