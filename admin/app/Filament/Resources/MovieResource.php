<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovieResource\Pages;
use App\Jobs\DownloadTrailerJob;
use App\Models\Movie;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Actions\Action;

class MovieResource extends Resource
{
    protected static ?string $model = Movie::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationGroup = 'Sources';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('release_date')
                                            ->timezone('America/New_York')
                                            ->required(),
                                    ]),
                                Forms\Components\Textarea::make('overview')
                                    ->required()
                                    ->maxLength(65535),
                                SpatieTagsInput::make('tags')->required(),
                            ]),
                    ])->columns(1),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('emby_id')
                                            ->label('Emby Id')
                                            ->maxLength(50),
                                        Forms\Components\TextInput::make('tmdb_id')
                                            ->label('TMDB Id')
                                            ->maxLength(50),
                                        Forms\Components\TextInput::make('imdb_id')
                                            ->label('IMDB Id')
                                            ->maxLength(50),
                                        Forms\Components\TextInput::make('rated')
                                            ->maxLength(20),
                                        Forms\Components\TextInput::make('runtime')
                                            ->maxLength(15),
                                        Forms\Components\TextInput::make('rating')
                                            ->maxLength(15),
                                        Forms\Components\TextInput::make('language')
                                            ->maxLength(4),
                                        Forms\Components\TextInput::make('trailer_link')
                                            ->label('YouTube Trailer')
                                            ->maxLength(255)
                                            ->suffixAction(fn (?Movie $record, $state, Closure $set) => Action::make('download-trailer')
                                                ->icon('heroicon-o-download')
                                                ->action(function () use ($state, $record) {
                                                    if ($record === null || blank($state)) {
                                                        Filament::notify('danger', 'Missing trailer link.');
                                                        return;
                                                    }

                                                    DownloadTrailerJob::dispatch($record->id, [$state]);
                                                })
                                                ->visible(fn () => $state !== null)
                                                ->requiresConfirmation()
                                            ),
                                    ]),

                            ])
                    ]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\Textarea::make('tag_line')
                                        ->maxLength(65535),
                                    Forms\Components\Textarea::make('description')
                                        ->maxLength(65535),
                                    Forms\Components\Textarea::make('story_line')
                                        ->maxLength(65535),
                                    Forms\Components\Textarea::make('synopsis')
                                        ->maxLength(65535),
                                ]),
                        ])
                    ])->columnSpanFull(),


                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Repeater::make('themes')
                                    ->nullable()
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ])->columnSpanFull(),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('poster')
                                            ->collection('poster')
                                            ->disk('s3'),
                                        SpatieMediaLibraryFileUpload::make('backdrop')
                                            ->collection('backdrop')
                                            ->disk('s3'),
                                        SpatieMediaLibraryFileUpload::make('trailer')
                                            ->collection('trailer')
                                            ->disk('s3'),
                                    ]),
                            ]),
                    ])->columnSpanFull(),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('poster')->collection('poster'),
                Tables\Columns\TextColumn::make('emby_id')
                    ->label('Emby Id')
                    ->url(fn (Movie $record): string => config('emby.movie_url') . $record->emby_id)
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('tmdb_id')
                    ->label('TMDB Id')
                    ->url(fn (Movie $record): string => config('tmdb.movie_url') . $record->tmdb_id)
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('imdb_id')
                    ->label('IMDB Id')
                    ->url(fn (Movie $record): string => config('imdb.movie_url') . $record->imdb_id)
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('title')->sortable(),
                Tables\Columns\TextColumn::make('rated'),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable(),
                Tables\Columns\TextColumn::make('runtime')
                    ->sortable()
                    ->getStateUsing(function (Movie $record): string {
                        return gmdate('H:i', $record->runtime);
                    }),
                Tables\Columns\TextColumn::make('release_date')
                    ->date('m-Y')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_complete')
                    ->disabled()
                    ->label('Completed')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('is_complete')
                    ->label('Completed')
                    ->query(fn (Builder $query): Builder => $query->where('is_complete', true))
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
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
            'index' => Pages\ListMovies::route('/'),
            'create' => Pages\CreateMovie::route('/create'),
            'view' => Pages\ViewMovie::route('/{record}'),
            'edit' => Pages\EditMovie::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
