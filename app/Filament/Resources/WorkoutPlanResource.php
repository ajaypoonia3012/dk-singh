<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkoutPlanResource\Pages;
use App\Models\WorkoutPlan;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class WorkoutPlanResource extends Resource
{
    protected static ?string $model = WorkoutPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('title')
    ->required()
    ->live(onBlur: true)
    ->afterStateUpdated(function (Set $set, ?string $state) {
        $set('slug', Str::slug($state));
    }),

Forms\Components\TextInput::make('slug')
    ->required()
    ->readOnly(),

Forms\Components\FileUpload::make('thumbnail')
    ->image()
    ->imageEditor()
    ->imageEditorAspectRatios([
        '4:5',
    ])
    ->imageCropAspectRatio('4:5')
    ->imageResizeMode('cover')
    ->imageResizeTargetWidth('800')
    ->imageResizeTargetHeight('1000')
    ->disk('public')
    ->disk('public')
->directory('workout-plans')
    ->imagePreviewHeight('220'),

Forms\Components\Textarea::make('description')
    ->rows(4),

                Forms\Components\RichEditor::make('content')
    ->required()
    ->columnSpanFull()
    ->toolbarButtons([
        'bold',
        'italic',
        'bulletList',
        'orderedList',
        'h2',
        'h3',
        'link',
    ]),

                Forms\Components\Select::make('difficulty')
    ->options([
        'Beginner' => 'Beginner',
        'Intermediate' => 'Intermediate',
        'Advanced' => 'Advanced',
    ])
    ->required(),

Forms\Components\Select::make('category')
    ->options([
        'Weight Loss' => 'Weight Loss',
        'Fat Loss' => 'Fat Loss',
        'Weight Gain' => 'Weight Gain',
        'Muscle Building' => 'Muscle Building',
        'Strength' => 'Strength',
        'Home Workout' => 'Home Workout',
        'Mobility' => 'Mobility',
        'Women Fitness' => 'Women Fitness',
        'Senior Fitness' => 'Senior Fitness',
    ])
    ->searchable()
    ->required(),

Forms\Components\Select::make('required_access')
    ->options([
        'public' => 'Public (Free)',
        'basic' => 'Basic Plan',
        'pro' => 'Pro Plan',
        'elite' => 'Elite Plan',
    ])
    ->default('public')
    ->required(),

Forms\Components\Select::make('video_type')
    ->options([
        'youtube' => 'YouTube',
        'google_drive' => 'Google Drive',
        'upload' => 'Upload',
    ])
    ->default('youtube'),

Forms\Components\TextInput::make('video_url')
    ->label('Video URL')
    ->visible(fn ($get) =>
        in_array($get('video_type'), ['youtube', 'google_drive'])
    ),

Forms\Components\FileUpload::make('video_file')
    ->disk('public')
->directory('workout-videos')
    ->acceptedFileTypes([
        'video/mp4',
        'video/quicktime',
    ])
    ->visible(fn ($get) =>
        $get('video_type') === 'upload'
    ),

Forms\Components\Toggle::make('featured')
    ->label('Featured Workout'),

Forms\Components\Toggle::make('status')
    ->label('Published')
    ->default(true),

Forms\Components\TextInput::make('sort_order')
    ->numeric()
    ->default(0),

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

    Tables\Columns\ImageColumn::make('thumbnail')
        ->disk('public')
        ->square()
        ->height(60),

    Tables\Columns\TextColumn::make('title')
        ->searchable()
        ->sortable(),

    Tables\Columns\BadgeColumn::make('difficulty'),

    Tables\Columns\BadgeColumn::make('category'),

    Tables\Columns\BadgeColumn::make('required_access'),

    Tables\Columns\BadgeColumn::make('video_type'),

    Tables\Columns\IconColumn::make('featured')
        ->boolean(),

    Tables\Columns\IconColumn::make('status')
        ->boolean(),

    Tables\Columns\TextColumn::make('sort_order')
        ->sortable(),

])  

->filters([

    Tables\Filters\SelectFilter::make('difficulty')
        ->options([
            'Beginner' => 'Beginner',
            'Intermediate' => 'Intermediate',
            'Advanced' => 'Advanced',
        ]),

    Tables\Filters\SelectFilter::make('required_access')
        ->options([
            'public' => 'Public',
            'basic' => 'Basic',
            'pro' => 'Pro',
            'elite' => 'Elite',
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
->bulkActions([
    Tables\Actions\BulkActionGroup::make([
        Tables\Actions\DeleteBulkAction::make(),
    ]),
])

->defaultSort('sort_order');
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
            'index' => Pages\ListWorkoutPlans::route('/'),
            'create' => Pages\CreateWorkoutPlan::route('/create'),
            'edit' => Pages\EditWorkoutPlan::route('/{record}/edit'),
        ];
    }
}