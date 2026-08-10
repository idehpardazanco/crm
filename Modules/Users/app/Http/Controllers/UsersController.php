<?php

namespace Modules\Users\app\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Users\app\Http\Requests\StoreUserRequest;
use Modules\Users\app\Http\Requests\UpdateUserRequest;
use Modules\Users\app\Services\UserService;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function __construct(
        private readonly UserService $service
    ) {
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Users/Index', [
            'users' => $this->service->paginate(
                $request->get('search')
            ),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create', [
           
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->service->create(
            $request->validated()
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'کاربر با موفقیت ایجاد شد.');
    }

    public function edit(int $id): Response
    {
        return Inertia::render('Users/Edit', [
            'user' => $this->service->find($id),

            'roles' => Role::query()
                ->orderBy('name')
                ->pluck('name')
                ->values(),
        ]);
    }

    public function update(
        UpdateUserRequest $request,
        int $id
    ): RedirectResponse {
        $this->service->update(
            $id,
            $request->validated()
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'کاربر با موفقیت ویرایش شد.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()
            ->route('users.index')
            ->with('success', 'کاربر با موفقیت حذف شد.');
    }
}