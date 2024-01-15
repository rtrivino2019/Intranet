<?php

namespace App\Filament\Employee\Resources\TopicResource\Pages;

use App\Filament\Employee\Resources\TopicResource;
use Filament\Actions;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTopic extends CreateRecord
{
    protected static string $resource = TopicResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['creator_id'] = auth()->id();
        return $data;

    }

    public function afterCreate(): void
    {
        $this->getRecord()->messages()->create([
            'sender_id' => auth()->id(),
            'content' => $this->data['messages']['content'],
        ]);

        // Notification::make()
        //     ->title('New Message')
        //     ->sendToDatabase(User::find($this->data['creator_id']));
    }


    // protected function afterCreate(): void
    // {
    //     $this->getRecord()->messages()->create([
    //         'sender_id' => auth()->id(),
    //         'content' => $this->data['messages']['content'],
    //     ]);

    //     $topic = $this->record;

    //     // Get the user who created the order
    //     $user = auth()->user();

    //     // Get the restaurants related to the order for the specific user
    //     $restaurants = $user->restaurants;

    //     if ($restaurants->isNotEmpty()) {
    //         // Get the names of the restaurants
    //         $restaurantNames = $restaurants->pluck('name')->implode(', ');

    //         // Notification content
    //         $notificationContent = sprintf(
    //             "New Message %s Created by %s at Restaurant: %s!",
    //             $topic->id,
    //             $user->name,
    //             $restaurantNames
    //         );

    //         // Get all users with roles 'Manager', 'Admin', or 'Owner'
    //         $usersWithRoles = User::role(['Manager', 'Admin', 'Owner','employee'])->get();

    //         // Include the user who created the order
    //         $usersToNotify = $usersWithRoles->push($user)->unique('id');

    //         if ($usersToNotify->isNotEmpty()) {
    //             Notification::make()
    //                 ->title('New Message Created')
    //                 ->icon('heroicon-o-shopping-bag')
    //                 ->body($notificationContent)
    //                 ->sendToDatabase($usersToNotify);
    //         }
    //     }
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
