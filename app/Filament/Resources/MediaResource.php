<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use App\Models\MediaCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Website Builder';

    protected static ?string $navigationLabel = 'Media Library';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\FileUpload::make('path')
                    ->label('Image')
                    ->disk('public')
                    ->directory('media')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->columnSpanFull()
                    ->afterStateUpdated(function ($state, Set $set) {

                        if (!$state) {
                            return;
                        }

                        $absolute = storage_path('app/public/'.$state);

                        if (!file_exists($absolute)) {
                            return;
                        }

                        $image = (new ImageManager(new Driver()))
                            ->read($absolute);

                        $set('file_name', basename($state));

                        if (!$set('name')) {
                            $set(
                                'name',
                                pathinfo($state, PATHINFO_FILENAME)
                            );
                        }

                        $set('disk', 'public');
                        $set('mime_type', mime_content_type($absolute));
                        $set('size', filesize($absolute));
                        $set('width', $image->width());
                        $set('height', $image->height());
                        $set('type', 'image');
                    }),

                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->helperText('Auto generated if empty.')
                    ->maxLength(255),

                Forms\Components\Select::make('media_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Hidden::make('file_name'),
                Forms\Components\Hidden::make('disk'),
                Forms\Components\Hidden::make('mime_type'),
                Forms\Components\Hidden::make('size'),
                Forms\Components\Hidden::make('width'),
                Forms\Components\Hidden::make('height'),
                Forms\Components\Hidden::make('type'),

                Forms\Components\TextInput::make('alt'),

                Forms\Components\TextInput::make('title'),

                Forms\Components\Textarea::make('description')
                    ->rows(3),

                Forms\Components\TagsInput::make('tags'),

                Forms\Components\Toggle::make('featured'),

                Forms\Components\Toggle::make('active')
                    ->default(true),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),

            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([

                Tables\Columns\ImageColumn::make('path')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

            ])

            ->filters([

                Tables\Filters\SelectFilter::make('media_category_id')
                    ->relationship('category', 'name'),

            ])

            ->actions([

                Tables\Actions\EditAction::make(),

            ])

            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),

                ]),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
}
