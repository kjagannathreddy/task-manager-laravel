<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Models\Task;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Validate input data and store task, notes and attachments in database
     * 
     * @param CreateTaskRequest $request
     * 
     * @return JsonResponse
     */
    public function store(CreateTaskRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $task = Task::create($request->only('subject', 'description', 'start_date', 'due_date', 'status', 'priority'));

            if (isset($validatedData['notes'])) {
                foreach ($request->notes as $noteData) {
                    $attachments = [];

                    foreach ($noteData['attachments'] as $attachment) {
                        $fileName = \Str::random(32) . '.' . $attachment->getClientOriginalExtension();
                        $attachment->move(public_path('uploads'), $fileName);
                        $attachments[] = $fileName;
                    }

                    $note = $task->notes()->create([
                        'subject' => $noteData['subject'],
                        'note' => $noteData['note'],
                        'attachments' => implode(',', $attachments),
                    ]);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to create task'], 500);
        }
    }

    /**
     * Fetch tasks with notes and apply filters if present
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {

        $tasks = Task::withCount('notes')
            ->whereHas('notes')
            ->with('notes')
            ->orderBy('priority', 'desc')
            ->orderBy('notes_count', 'desc');

        if ($request->has('filter')) {
            $filters = $request->input('filter');
            $tasks->withFilters($filters);
        }

        $tasks = $tasks->get();

        return response()->json(['tasks' => $tasks], 200);
    }
}
