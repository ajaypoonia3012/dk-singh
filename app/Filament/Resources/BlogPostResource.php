<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Blog CMS';

    protected static ?string $navigationLabel = 'Posts';
protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
{
    return $form
        ->schema([

            Forms\Components\Section::make('Post Information')
                ->schema([

                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, ?string $state) {

    $set('slug', Str::slug($state));

    $set('seo_title', $state);

}),

                    Forms\Components\TextInput::make('slug')
    ->required()
    ->unique(ignoreRecord: true)
    ->readOnly()
    ->dehydrated(),

                    Forms\Components\Select::make('blog_category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload(),

                    Forms\Components\TextInput::make('author')
                        ->default('DK Singh')
->autocomplete(false)
                        ->required(),

                    Forms\Components\TextInput::make('reading_time')
                        ->numeric()
                        ->suffix('min')
                        ->default(5),

                    Forms\Components\Hidden::make('views')
                        ->default(0),
                ])
                ->columns(2),

            Forms\Components\Section::make('Content')
                ->schema([

                    Forms\Components\Textarea::make('excerpt')
                        ->rows(4)
                        ->required(),

                    Forms\Components\RichEditor::make('content')
    ->toolbarButtons([
        'bold',
        'italic',
        'underline',
        'bulletList',
        'orderedList',
        'h2',
        'h3',
        'blockquote',
        'link',
        'redo',
        'undo',
    ])
                        ->columnSpanFull()
                        ->required(),

                ]),

            Forms\Components\Section::make('Featured Image')
                ->schema([

                    Forms\Components\Select::make('media_id')
    ->label('Featured Image')
    ->relationship('media', 'file_name')
    ->getOptionLabelFromRecordUsing(
        fn ($record) => "{$record->folder} / {$record->file_name}"
    )
    ->searchable()
    ->preload()
    ->required(),

                ]),

            Forms\Components\Section::make('Publishing')
                ->schema([

                    Forms\Components\Toggle::make('featured')
                        ->default(false),

                    Forms\Components\Toggle::make('status')
                        ->label('Published')
                        ->default(true),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->seconds(false)
                        ->default(now()),

                ])
                ->columns(3),

            Forms\Components\Section::make('SEO')
                ->schema([

                    Forms\Components\TextInput::make('seo_title'),

                    Forms\Components\Textarea::make('seo_description')
                        ->rows(4),

                ]),

        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([

            Tables\Columns\ImageColumn::make('media.path')
                ->label('Image')
                ->disk('public')
                ->square()
                ->height(60),

            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable()
                ->limit(40),

            Tables\Columns\TextColumn::make('category.name')
                ->label('Category')
                ->badge()
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('tags')
    ->label('Tags')
    ->formatStateUsing(
        fn ($record) => $record->tags->pluck('name')->implode(', ')
    )
    ->wrap()
    ->toggleable(),

            Tables\Columns\TextColumn::make('author')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('reading_time')
                ->suffix(' min')
                ->sortable(),

            Tables\Columns\TextColumn::make('views')
                ->numeric()
                ->sortable(),

            Tables\Columns\IconColumn::make('featured')
                ->boolean()
                ->label('Featured'),

            Tables\Columns\IconColumn::make('status')
                ->boolean()
                ->label('Published'),

            Tables\Columns\TextColumn::make('published_at')
                ->dateTime('d M Y')
                ->sortable(),

        ])

        ->filters([

            Tables\Filters\SelectFilter::make('blog_category_id')
                ->relationship('category', 'name')
                ->label('Category'),

            Tables\Filters\TernaryFilter::make('featured'),

            Tables\Filters\TernaryFilter::make('status'),

        ])

        ->actions([

            Tables\Actions\ViewAction::make(),

            Tables\Actions\EditAction::make(),

            Tables\Actions\DeleteAction::make(),

        ])

        ->bulkActions([

            Tables\Actions\BulkActionGroup::make([

                Tables\Actions\DeleteBulkAction::make(),

            ]),

        ])
->searchPlaceholder('Search blog posts...')
        ->defaultSort('published_at', 'desc');
}

public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->with([
            'category',
            'media',
            'tags',
        ]);
}

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}