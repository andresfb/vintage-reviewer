<?php

namespace App\Filament\Resources;

use Closure;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $navigationGroup = 'Blog';

    public static function form(Form $form): Form
    {
        $mediaDisk = config('media-library.disk_name');

        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('movie_id')
                                            ->searchable()
                                            ->relationship('movie', 'title')
                                            ->required()
                                            ->columnSpan(1)
                                            ->suffixAction(fn (?Post $record, $state, Closure $set) => Action::make('view_movie')
                                                ->icon('heroicon-o-external-link')
                                                ->url(fn () => route('filament.resources.movies.edit', $record->movie_id))
                                                ->openUrlInNewTab()
                                                ->visible(fn () => $state !== null)
                                            ),
                                    ]),
                            ]),
                    ])->columnSpanFull(),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('tag_line')
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        SpatieTagsInput::make('tags')
                                            ->required()
                                            ->columnSpan(2),
                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->required()
                                            ->displayFormat('M d, Y')
                                            ->timezone('America/New_York'),
                                        Forms\Components\Toggle::make('active')->inline(false),
                                    ]),
                            ]),
                    ])->columnSpanFull(),

                Forms\Components\Tabs::make('Media')
                    ->tabs([
                        Tabs\Tab::make('Image')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('image')
                                    ->label('')
                                    ->collection('image')
                                    ->responsiveImages()
                                    ->disk($mediaDisk)
                                    ->columnSpan(1),
                            ]),
                        Tabs\Tab::make('Gallery')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('gallery')
                                    ->label('')
                                    ->multiple()
                                    ->enableReordering()
                                    ->collection('gallery')
                                    ->responsiveImages()
                                    ->disk($mediaDisk)
                                    ->columnSpan(2),
                            ]),
                    ])->columnSpanFull(),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\MarkdownEditor::make('content'),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')->collection('image'),
                Tables\Columns\TextColumn::make('movie.title'),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tag_line'),
                Tables\Columns\ToggleColumn::make('active')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
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
