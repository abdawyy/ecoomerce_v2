<?php

namespace App\Services;

use App\Models\GuestUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AdminCustomerProfileService
{
    public function fromUser(User $user): array
    {
        $user->load([
            'address',
            'order' => fn ($query) => $query->with(['cities', 'discountCodes'])->orderByDesc('created_at'),
        ]);

        return $this->buildProfile(
            type: 'registered',
            id: $user->id,
            name: $user->name,
            email: $user->email,
            phone: $user->phone,
            joinedAt: $user->created_at,
            isActive: (bool) ($user->is_active ?? true),
            orders: $user->order,
            addresses: $user->address,
            toggleUrl: route('admin.users.toggleStatus', $user->id),
            listRoute: route('users.list'),
            showTitle: __('users.show_title'),
        );
    }

    public function fromGuest(GuestUser $guest): array
    {
        $guest->load([
            'address',
            'orders' => fn ($query) => $query->with(['cities', 'discountCodes'])->orderByDesc('created_at'),
        ]);

        return $this->buildProfile(
            type: 'guest',
            id: $guest->id,
            name: $guest->name,
            email: $guest->email,
            phone: null,
            joinedAt: $guest->created_at,
            isActive: null,
            orders: $guest->orders,
            addresses: $guest->address,
            toggleUrl: null,
            listRoute: route('admin.guest.list'),
            showTitle: __('guest.show_title'),
        );
    }

    protected function buildProfile(
        string $type,
        int $id,
        string $name,
        string $email,
        ?string $phone,
        ?Carbon $joinedAt,
        ?bool $isActive,
        Collection $orders,
        Collection $addresses,
        ?string $toggleUrl,
        string $listRoute,
        string $showTitle,
    ): array {
        $lastOrder = $orders->first();

        return [
            'type' => $type,
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'joined_at' => $joinedAt,
            'is_active' => $isActive,
            'is_new' => $joinedAt && $joinedAt->gte(Carbon::now()->subDays(7)),
            'orders_count' => $orders->count(),
            'total_spent' => $orders->sum('total_amount'),
            'last_order_at' => $lastOrder?->created_at,
            'last_order_id' => $lastOrder?->id,
            'city' => $lastOrder?->cities?->name ?? $addresses->first()?->city,
            'orders' => $orders,
            'addresses' => $addresses,
            'toggle_url' => $toggleUrl,
            'list_route' => $listRoute,
            'show_title' => $showTitle,
        ];
    }
}
