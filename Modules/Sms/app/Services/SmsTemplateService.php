<?php

namespace Modules\Sms\app\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Sms\app\Models\SmsTemplate;

class SmsTemplateService
{
    public function paginate(
        ?string $search = null
    ): LengthAwarePaginator {
        return SmsTemplate::query()
            ->when(
                $search,
                function ($query, string $search) {
                    $query->where(
                        function ($query) use ($search) {
                            $query
                                ->where(
                                    'title',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'body',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'type',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function active(): Collection
    {
        return SmsTemplate::query()
            ->where('status', 'active')
            ->orderBy('title')
            ->get();
    }

    public function find(int $id): SmsTemplate
    {
        return SmsTemplate::query()
            ->findOrFail($id);
    }

    public function create(array $data): SmsTemplate
    {
        return SmsTemplate::query()
            ->create($data);
    }

    public function update(
        int $id,
        array $data
    ): SmsTemplate {
        $template = $this->find($id);

        $template->update($data);

        return $template->refresh();
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }
}