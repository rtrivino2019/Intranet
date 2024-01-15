<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Topic;
use App\Models\Message;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components;
use Filament\Infolists\Components\Grid;
use Filament\Forms\Components\TextInput;
//use Filament\Notifications\Notification;



use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Actions;

use Filament\Infolists\Components\Section;

use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\ReplicateAction;
use App\Filament\Resources\TopicResource\Pages;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;





class TopicResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $modelLabel = 'Message';

    protected static ?string $navigationLabel = 'Inbox';
    protected static ?string $navigationGroup = 'Communications';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('messages.content')
                        ->required(),
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
                        })
                ])
                ->columnSpanFull(),
            ]);


    }

    // public static function getNavigationBadge(): ?string
    // {
    //     $topics = static::getModel()::where('creator_id', auth()->id())//->orWhere('sender_id', auth()->id())
    //         ->with('messages')
    //         ->get();

    //     $unreadCount = 0;

    //     foreach ($topics as $topic) {
    //         foreach ($topic->messages as $message) {
    //             if ($message->sender_id !== auth()->id() && $message->read_at === null) {
    //                 $unreadCount++;
    //             }
    //         }
    //     }

    //     return $unreadCount;
    // }

    // public static function getEloquentQuery(): Builder
    // {
    //     return Topic::query();
    // }
}




