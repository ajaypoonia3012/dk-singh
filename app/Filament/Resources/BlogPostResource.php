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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Editorial Content';

    protected static ?string $navigationLabel = 'Articles';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // ── LEFT / MAIN COLUMN ─────────────────────────────────────
                Forms\Components\Group::make()
                    ->schema([

                        Forms\Components\Section::make('Article Content')
                            ->schema([

                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                        $set('slug', Str::slug($state));
                                        $set('seo_title', $state);
                                    })
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->readOnly()
                                    ->dehydrated()
                                    ->columnSpanFull()
                                    ->helperText('Auto-generated from title. URL-safe.'),

                                Forms\Components\Textarea::make('excerpt')
                                    ->rows(3)
                                    ->required()
                                    ->maxLength(300)
                                    ->helperText(fn ($state) => strlen((string) $state) . ' / 300 characters')
                                    ->columnSpanFull(),

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

                        Forms\Components\Section::make('Categorisation')
                            ->schema([

                                Forms\Components\Select::make('blog_category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('content_type')
                                    ->label('Content Format')
                                    ->options([
                                        'article'        => 'Standard Article',
                                        'exercise_guide' => 'Exercise Guide',
                                        'workout_guide'  => 'Workout Guide',
                                        'product_guide'  => 'Product / Equipment Guide',
                                        'comparison'     => 'Comparison / Versus',
                                        'how_to'         => 'How-To Guide',
                                        'beginner_guide' => 'Beginner Blueprint',
                                        'fitness_faq'    => 'Fitness FAQ',
                                    ])
                                    ->default('article')
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

                                Forms\Components\Hidden::make('views')->default(0),

                            ])
                            ->columns(2),

                        Forms\Components\Section::make('SEO')
                            ->schema([

                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->maxLength(70)
                                    ->helperText(fn ($state) => strlen((string) $state) . ' / 70 characters — ideal 50–60')
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('seo_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->helperText(fn ($state) => strlen((string) $state) . ' / 160 characters — ideal 130–155')
                                    ->columnSpanFull(),

                            ])
                            ->collapsible(),

                    ])
                    ->columnSpan(['lg' => 2]),

                // ── RIGHT / SIDEBAR COLUMN ─────────────────────────────────
                Forms\Components\Group::make()
                    ->schema([

                        Forms\Components\Section::make('Publishing')
                            ->schema([

                                Forms\Components\Toggle::make('status')
                                    ->label('Published')
                                    ->default(true),

                                Forms\Components\Toggle::make('featured')
                                    ->default(false),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->seconds(false)
                                    ->default(now()),

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
                                    ->required()
                                    ->helperText('Choose from the Media Library.'),

                            ]),

                    ])
                    ->columnSpan(['lg' => 1]),

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('media.path')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->height(56)
                    ->defaultImageUrl(fn () => null)
                    ->tooltip(fn ($record) => $record->media_id ? null : 'Missing featured image'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(45)
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('content_type')
                    ->label('Format')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'article'        => 'Article',
                        'exercise_guide' => 'Exercise Guide',
                        'workout_guide'  => 'Workout Guide',
                        'product_guide'  => 'Product Guide',
                        'comparison'     => 'Comparison',
                        'how_to'         => 'How-To',
                        'beginner_guide' => 'Beginner',
                        'fitness_faq'    => 'FAQ',
                        default          => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('author')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->label('Featured')
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->label('Published')
                    ->sortable(),

                // SEO health indicator
                Tables\Columns\IconColumn::make('seo_status')
                    ->label('SEO')
                    ->icon(fn ($record) => ($record->seo_title && $record->seo_description)
                        ? 'heroicon-o-check-circle'
                        : 'heroicon-o-exclamation-circle'
                    )
                    ->color(fn ($record) => ($record->seo_title && $record->seo_description)
                        ? 'success'
                        : 'warning'
                    )
                    ->tooltip(fn ($record) => ($record->seo_title && $record->seo_description)
                        ? 'SEO complete'
                        : 'Missing SEO title or description'
                    )
                    ->getStateUsing(fn () => true),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('d M Y')
                    ->sortable(),

            ])

            ->filters([

                Tables\Filters\SelectFilter::make('blog_category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),

                Tables\Filters\SelectFilter::make('content_type')
                    ->label('Content Format')
                    ->options([
                        'article'        => 'Standard Article',
                        'exercise_guide' => 'Exercise Guide',
                        'workout_guide'  => 'Workout Guide',
                        'product_guide'  => 'Product / Equipment Guide',
                        'comparison'     => 'Comparison / Versus',
                        'how_to'         => 'How-To Guide',
                        'beginner_guide' => 'Beginner Blueprint',
                        'fitness_faq'    => 'Fitness FAQ',
                    ]),

                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Featured'),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Published'),

                Tables\Filters\Filter::make('missing_seo')
                    ->label('Missing SEO')
                    ->query(fn (Builder $query) => $query->where(function ($q) {
                        $q->whereNull('seo_title')
                          ->orWhere('seo_title', '')
                          ->orWhereNull('seo_description')
                          ->orWhere('seo_description', '');
                    }))
                    ->toggle(),

                Tables\Filters\Filter::make('missing_image')
                    ->label('Missing Image')
                    ->query(fn (Builder $query) => $query->whereNull('media_id'))
                    ->toggle(),

            ])

            ->actions([

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])

            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => true]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Unpublish Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['status' => false]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make(),

                ]),

            ])
            ->searchPlaceholder('Search by title, author, slug…')
            ->defaultSort('published_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['category', 'media', 'tags']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'excerpt', 'author'];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit'   => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
