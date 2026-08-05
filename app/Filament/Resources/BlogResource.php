<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Blog Information')
                    ->schema([

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn (Set $set, ?string $state) =>
                                    $set('slug', Str::slug($state))
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->readOnly(),

                        Forms\Components\FileUpload::make('featured_image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                            ])
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth(1600)
                            ->imageResizeTargetHeight(900)
                            ->disk('public')
                            ->directory('blogs')
                            ->imagePreviewHeight('220'),

                        Forms\Components\Textarea::make('excerpt')
                            ->rows(4)
                            ->maxLength(300)
                            ->helperText('Short summary shown on blog listing pages.')

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Article')
                    ->schema([

                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),

                    ]),

                Forms\Components\Section::make('Blog Details')
                    ->schema([

                        Forms\Components\Select::make('category')
                            ->options([
                                'Fitness' => 'Fitness',
                                'Nutrition' => 'Nutrition',
                                'Weight Loss' => 'Weight Loss',
                                'Muscle Gain' => 'Muscle Gain',
                                'Supplements' => 'Supplements',
                                'Lifestyle' => 'Lifestyle',
                            ])
                            ->searchable(),

                        Forms\Components\TextInput::make('author')
                            ->default('DK Singh'),

                        Forms\Components\TextInput::make('reading_time')
                            ->placeholder('5 min read'),

                    ])
                    ->columns(3),

                Forms\Components\Section::make('Publishing')
                    ->schema([

                        Forms\Components\Toggle::make('featured')
                            ->label('Featured Article'),

                        Forms\Components\Toggle::make('status')
                            ->label('Published')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

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

                Tables\Columns\ImageColumn::make('featured_image')
                    ->disk('public')
                    ->square()
                    ->height(60),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('category'),

                Tables\Columns\TextColumn::make('author'),

                Tables\Columns\TextColumn::make('reading_time'),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Fitness' => 'Fitness',
                        'Nutrition' => 'Nutrition',
                        'Weight Loss' => 'Weight Loss',
                        'Muscle Gain' => 'Muscle Gain',
                        'Supplements' => 'Supplements',
                        'Lifestyle' => 'Lifestyle',
                    ]),

                Tables\Filters\TernaryFilter::make('featured'),

                Tables\Filters\TernaryFilter::make('status'),

            ])
            ->actions([

                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}