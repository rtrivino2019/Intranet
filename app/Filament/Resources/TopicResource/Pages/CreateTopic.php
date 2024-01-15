<?php

namespace App\Filament\Resources\TopicResource\Pages;

use App\Models\User;
use Filament\Actions;
//use Filament\Notifications\Notification;
use Filament\Notifications\Notification;
use App\Filament\Resources\TopicResource;
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

        Notification::make()
            ->title('New Message')
            ->sendToDatabase(User::find($this->data['user_id']));
    }





    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
