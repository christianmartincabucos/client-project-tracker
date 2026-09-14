<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $query = Project::query();

        if ($search = request('search')) {
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('client_name', 'like', "%{$search}%")
                    ->orWhere('project_name', 'like', "%{$search}%");
            });
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($priority = request('priority')) {
            $query->where('priority', $priority);
        }

        $allowedSorts = ['project_name', 'client_name', 'start_date', 'due_date', 'priority', 'status'];
        $sort = in_array(request('sort'), $allowedSorts, true) ? request('sort') : 'created_at';
        $direction = request('direction') === 'desc' ? 'desc' : 'asc';

        return ProjectResource::collection($query->orderBy($sort, $direction)->get());
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        return (new ProjectResource($project))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project): ProjectResource
    {
        $project->update($request->validated());

        return new ProjectResource($project->refresh());
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
