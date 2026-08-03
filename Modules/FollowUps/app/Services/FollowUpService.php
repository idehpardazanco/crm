<?php

namespace Modules\FollowUps\app\Services;

use Modules\FollowUps\app\Models\FollowUp;

class FollowUpService
{
    public function paginate(?string $search = null)
    {
        return FollowUp::query()
            ->with([
                'contact',
                'user'
            ])
            ->when($search, function ($query) use ($search) {

                $query->whereHas('contact', function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%");

                });

            })
            ->latest('follow_up_at')
            ->paginate(20);
    }

    public function create(array $data)
    {
        return FollowUp::create($data);
    }

    public function update(int $id, array $data)
    {
        $followUp = FollowUp::findOrFail($id);

        $followUp->update($data);

        return $followUp;
    }

    public function delete(int $id)
    {
        return FollowUp::findOrFail($id)
            ->delete();
    }
}