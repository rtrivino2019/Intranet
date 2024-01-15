<?php

namespace App\Filament\Employee\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Topic;
use App\Models\Message;
use Filament\Infolists;
use Filament\Forms\Form;
use App\Models\Restaurant;


use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components;
use Filament\Infolists\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Employee\Resources\TopicResource\Pages;
use App\Filament\Employee\Resources\TopicResource\RelationManagers;




class TopicResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $modelLabel = 'Message';

    protected static ?string $navigationLabel = 'Inbox';
    protected static ?string $navigationGroup = 'Communications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Textarea::make('messages.content')
                            ->required(),
                ])
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Creator')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),
                Tables\Columns\TextColumn::make('messages.content')
                ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->recordClasses(function (Topic $record) use ($table): string|null {
                $unreads = collect($table->getRecords()->loadMissing('messages'))
                        ->mapWithKeys(function($record) {
                            return [$record->id => $record->messages->where('sender_id', '!==', auth()->id())->where('read_at', '===', null)->count()];
                        })
                        ->filter(function ($value) {
                            return $value == true;
                        })
                        ->keys()
                        ->all();

                return in_array($record->id, $unreads) ? 'bg-gray-100' : null;
            })
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTopics::route('/'),
            'create' => Pages\CreateTopic::route('/create'),
            'edit' => Pages\EditTopic::route('/{record}/edit'),
            'view' => Pages\ViewTopic::route('/{record}/view'),

        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // TextEntry::make('subject')
                //         ->inlineLabel(),
                components\RepeatableEntry::make('messages')
                ->hiddenLabel()
                ->schema(fn () => [
                    Grid::make()
                    ->schema([
                        TextEntry::make('sender.name')
                        ->inlineLabel(),
                TextEntry::make('created_at')
                        ->inlineLabel()
                        ->hiddenLabel()
                        ->since()
                        ->alignEnd(),
                TextEntry::make('content')
                        ->hiddenLabel(),
                    ]),


                ])
                 ->columnSpanFull(),


                Actions::make([
                    Action::make('reply')
                        ->form([
                            Forms\Components\Textarea::make('content')
                                ->required(),
                        ])
                        ->action(function (Topic $record, array $data): void {
                            $record->messages()->create([
                                'sender_id' => auth()->id(),
                                //'user_id' => auth()->id(),
                                'content' => $data['content'],
                            ]);

                            //  Notification::make()
                            //      ->title('New Reply To ' . $record->subject)
                            //     ->sendToDatabase(User::find($record['sender_id']));

                            Notification::make()
                                ->title('New Reply To ' . $record->subject)
                                ->sendToDatabase(User::find($record['receiver_id']) ?? auth()->user());


                          })
                        ])
                ->columnSpanFull(),
            ]);


    }


    public static function getNavigationBadge(): ?string
{
    $url = request()->url();
     // Use regular expression to extract restaurant ID from the URL
     if (preg_match('/employee\/(\d+)\/topics/', $url, $matches)) {
        $restaurantId = $matches[1]; // The restaurant ID captured by the regular expression
    } else {
        // Handle the case where the URL doesn't match the expected pattern
        return null;
    }



    // Assuming you have a 'topics' relationship on the Restaurant model
    $topics = Restaurant::findOrFail($restaurantId)->topics()->get();
    $unreadCount = 0;

    foreach ($topics as $topic) {
        foreach ($topic->messages as $message) {
            if ($message->read_at === null) {
                $unreadCount++;
            }
        }
    }

    return $unreadCount > 0 ? (string) $unreadCount : null; // Cast to string if $unreadCount is greater than 0, otherwise return null
}













    // public static function getEloquentQuery(): Builder
    // {
    //     return Topic::query();
    // }
}
