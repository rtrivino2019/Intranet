<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\User;
use App\Models\Restaurant;
use Filament\Notifications\Notification;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['user_id'] = auth()->id();
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }



    protected function afterCreate(): void
    {
        $order = $this->record;

        // Get the user who created the order
        $user = auth()->user();

        // Get the restaurants related to the order for the specific user
        $restaurants = $user->restaurants;

        if ($restaurants->isNotEmpty()) {
            // Get the names of the restaurants
            $restaurantNames = $restaurants->pluck('name')->implode(', ');

            // Notification content
            $notificationContent = sprintf(
                "New Order %s Created by %s at Restaurant: %s!",
                $order->id,
                $user->name,
                $restaurantNames
            );

            // Get all users with roles 'Manager', 'Admin', or 'Owner'
            $usersWithRoles = User::role(['Manager', 'Admin', 'Owner'])->get();

            // Include the user who created the order
            $usersToNotify = $usersWithRoles->push($user)->unique('id');

            if ($usersToNotify->isNotEmpty()) {
                Notification::make()
                    ->title('New Order Created')
                    ->icon('heroicon-o-shopping-bag')
                    ->body($notificationContent)
                    ->sendToDatabase($usersToNotify);
            }
        }
    }

    



}
