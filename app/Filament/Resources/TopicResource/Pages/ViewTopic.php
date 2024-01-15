<?php

namespace App\Filament\Resources\TopicResource\Pages;

use App\Filament\Resources\TopicResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\User;
use App\Models\Topic;
use Filament\Forms\Components\Textarea;
//use Filament\Notifications\Notification;




class ViewTopic extends ViewRecord
{
    protected static string $resource = TopicResource::class;

    protected ?string $heading = 'View Conversation';

    public function getSubheading(): ?string
    {
        return 'Subject: ' . $this->record->subject;
    }

    protected function getHeaderActions(): array {
        return [
            Actions\Action::make('reply')
                ->form([
                    Textarea::make('content')
                        ->required(),
                ])
                ->action(function (Topic $record, array $data): void {
                    $record->messages()->create([
                        'sender_id' => auth()->id(),
                        'content' => $data['content'],
                    ]);

                    // Notification::make()
                    //     ->title('New Reply To ' . $record->subject)
                    //     ->sendToDatabase(User::find($record['receiver_id']));
                })
        ];
    }

}
