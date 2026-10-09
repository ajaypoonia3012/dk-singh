<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExerciseResource\Pages;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ExerciseResource extends Resource
{
    protected static ?string $model = Exercise::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationGroup = 'Fitness & Movement';

    protected static ?string $navigationLabel = 'Exercise Library';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $set('slug', Str::slug($state));
                                $set('seo_title', "{$state} Technique, Form & Execution Guide | DK Singh Fitness");
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->readOnly()
                            ->dehydrated(),

                        Forms\Components\Select::make('exercise_category')
                            ->options([
                                'Chest' => 'Chest',
                                'Back' => 'Back',
                                'Legs' => 'Legs',
                                'Shoulders' => 'Shoulders',
                                'Arms' => 'Arms',
                                'Core' => 'Core',
                                'Cardio' => 'Cardio',
                                'Mobility' => 'Mobility',
                                'Yoga & Flexibility' => 'Yoga & Flexibility',
                                'Full Body' => 'Full Body',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('primary_muscle')
                            ->required()
                            ->placeholder('e.g. Pectoralis Major, Quadriceps, Latissimus Dorsi'),

                        Forms\Components\TagsInput::make('secondary_muscles')
                            ->placeholder('Add secondary muscle')
                            ->separator(','),

                        Forms\Components\Select::make('equipment')
                            ->options([
                                'Bodyweight' => 'Bodyweight',
                                'Barbell' => 'Barbell',
                                'Dumbbells' => 'Dumbbells',
                                'Kettlebell' => 'Kettlebell',
                                'Cable' => 'Cable Machine',
                                'Resistance Band' => 'Resistance Band',
                                'Machine' => 'Gym Machine',
                                'Yoga Mat' => 'Yoga Mat',
                            ])
                            ->required(),

                        Forms\Components\Select::make('difficulty')
                            ->options([
                                'Beginner' => 'Beginner',
                                'Intermediate' => 'Intermediate',
                                'Advanced' => 'Advanced',
                            ])
                            ->default('Beginner')
                            ->required(),

                        Forms\Components\Select::make('movement_pattern')
                            ->options([
                                'Horizontal Push' => 'Horizontal Push',
                                'Vertical Push' => 'Vertical Push',
                                'Horizontal Pull' => 'Horizontal Pull',
                                'Vertical Pull' => 'Vertical Pull',
                                'Squat' => 'Squat',
                                'Hip Hinge' => 'Hip Hinge',
                                'Lunge' => 'Lunge',
                                'Core Anti-Extension' => 'Core Anti-Extension',
                                'Core Rotation' => 'Core Rotation',
                                'Conditioning' => 'Conditioning',
                                'Mobility Flow' => 'Mobility Flow',
                            ]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Biomechanical Execution Instructions')
                    ->schema([
                        Forms\Components\Textarea::make('short_description')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('setup')
                            ->label('Setup & Anatomical Alignment')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('execution_steps')
                            ->label('Step-by-Step Execution Cues')
                            ->placeholder('Add next step')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('breathing_guidance')
                            ->label('Breathing Guidance & Intra-Abdominal Pressure')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('common_mistakes')
                            ->label('Common Mistakes to Avoid')
                            ->placeholder('Add mistake cue')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('safety_considerations')
                            ->label('Safety & Joint Alignment Considerations')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Variations & Modifications')
                    ->schema([
                        Forms\Components\TextInput::make('beginner_modification')
                            ->placeholder('e.g. Knee Push-ups or Incline push-ups'),

                        Forms\Components\TextInput::make('advanced_variation')
                            ->placeholder('e.g. Deficit push-ups or Weighted vest'),

                        Forms\Components\TextInput::make('home_variation')
                            ->placeholder('e.g. Resistance band push-ups'),

                        Forms\Components\TextInput::make('gym_variation')
                            ->placeholder('e.g. Low cable flyes or dips'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Media & Video')
                    ->schema([
                        Forms\Components\Select::make('media_id')
                            ->label('Featured Image')
                            ->relationship('media', 'file_name')
                            ->getOptionLabelFromRecordUsing(
                                fn ($record) => "{$record->folder} / {$record->file_name}"
                            )
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('video_url')
                            ->label('Video Embed URL (YouTube/Vimeo)')
                            ->url()
                            ->placeholder('https://www.youtube.com/embed/...'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Frequently Asked Questions')
                    ->schema([
                        Forms\Components\Repeater::make('faqs')
                            ->schema([
                                Forms\Components\TextInput::make('question')->required(),
                                Forms\Components\Textarea::make('answer')->required()->rows(2),
                            ])
                            ->columnSpanFull()
                            ->collapsible()
                            ->defaultItems(0),
                    ]),

                Forms\Components\Section::make('Publishing & SEO')
                    ->schema([
                        Forms\Components\Toggle::make('featured')
                            ->default(false),

                        Forms\Components\Toggle::make('status')
                            ->label('Published')
                            ->default(true),

                        Forms\Components\TextInput::make('seo_title')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('meta_description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('canonical_url')
                            ->url()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('media.path')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->square()
                    ->height(50),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('exercise_category')
                    ->label('Category')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('primary_muscle')
                    ->label('Target')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('equipment')
                    ->sortable(),

                Tables\Columns\TextColumn::make('difficulty')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Beginner' => 'success',
                        'Intermediate' => 'info',
                        'Advanced' => 'danger',
                        default => 'secondary',
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->label('Featured'),

                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->label('Active'),

                Tables\Columns\TextColumn::make('views')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('exercise_category')
                    ->options([
                        'Chest' => 'Chest',
                        'Back' => 'Back',
                        'Legs' => 'Legs',
                        'Shoulders' => 'Shoulders',
                        'Arms' => 'Arms',
                        'Core' => 'Core',
                        'Cardio' => 'Cardio',
                        'Mobility' => 'Mobility',
                        'Yoga & Flexibility' => 'Yoga & Flexibility',
                        'Full Body' => 'Full Body',
                    ]),
                Tables\Filters\SelectFilter::make('difficulty')
                    ->options([
                        'Beginner' => 'Beginner',
                        'Intermediate' => 'Intermediate',
                        'Advanced' => 'Advanced',
                    ]),
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Published Status'),
                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Featured'),
                Tables\Filters\Filter::make('missing_image')
                    ->label('Missing Image')
                    ->query(fn (Builder $query) => $query->whereNull('media_id'))
                    ->toggle(),
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

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'primary_muscle', 'exercise_category'];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExercises::route('/'),
            'create' => Pages\CreateExercise::route('/create'),
            'edit' => Pages\EditExercise::route('/{record}/edit'),
        ];
    }
}
