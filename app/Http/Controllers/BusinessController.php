<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Models\Business;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::query()->latest();

        if ($request->search) {
            $query->where('business_name', 'like', "%{$request->search}%")
                ->orWhere('mobile', 'like', "%{$request->search}%");
        }

        return Inertia::render('Businesses/Index', [
            'businesses' => $query->paginate(10),
            'filters' => $request->only('search'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Businesses/Create');
    }

    public function store(StoreBusinessRequest $request)
    {
        Business::create($request->validated());

        return redirect()
            ->route('businesses.index')
            ->with('success', 'مخاطب با موفقیت ثبت شد');
    }

    public function edit(Business $business)
    {
        return Inertia::render('Businesses/Edit', [
            'business' => $business,
        ]);
    }

    public function update(UpdateBusinessRequest $request, Business $business)
    {
        $business->update($request->validated());

        return redirect()
            ->route('businesses.index')
            ->with('success', 'مخاطب ویرایش شد');
    }

    public function destroy(Business $business)
    {
        $business->delete();

        return back()->with('success', 'مخاطب حذف شد');
    }
}
