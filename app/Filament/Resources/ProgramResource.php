<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramResource\Pages;
use App\Filament\Resources\ProgramResource\RelationManagers;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;


class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([

            Forms\Components\Section::make('Program Information')
                ->schema([

                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->readOnly(),

                    Forms\Components\Textarea::make('description')
                        ->rows(5)
                        ->required(),

                ])
                ->columns(2),

            Forms\Components\Section::make('Program Details')
    ->schema([

        Forms\Components\FileUpload::make('image')
            ->image()
            ->imageEditor()
            ->imageEditorAspectRatios([
                '4:5',
            ])
            ->directory('programs')
            ->imagePreviewHeight('250')
            ->required(),

        Forms\Components\Select::make('category')
            ->options([
                'Fat Loss' => 'Fat Loss',
                'Weight Gain' => 'Weight Gain',
                'Muscle Building' => 'Muscle Building',
                'Strength' => 'Strength',
                'Home Workout' => 'Home Workout',
                "Women's Fitness" => "Women's Fitness",
            ])
            ->required(),

        Forms\Components\Select::make('duration')
            ->options([
                '4 Weeks' => '4 Weeks',
                '8 Weeks' => '8 Weeks',
                '12 Weeks' => '12 Weeks',
                '16 Weeks' => '16 Weeks',
                '24 Weeks' => '24 Weeks',
            ])
            ->required(),

        Forms\Components\TextInput::make('price')
            ->numeric()
            ->prefix('₹')
            ->required(),

        Forms\Components\Toggle::make('featured')
            ->label('Show on Homepage'),

        Forms\Components\Toggle::make('status')
            ->label('Published')
            ->default(true),

        Forms\Components\TextInput::make('sort_order')
            ->numeric()
            ->default(0),

    ])
    ->columns(2),
Forms\Components\Section::make('SEO')
    ->schema([

        Forms\Components\TextInput::make('seo_title')
            ->maxLength(255),

        Forms\Components\Textarea::make('seo_description')
            ->rows(4)
            ->maxLength(500),

    ]),

        ]);
}



public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\ImageColumn::make('image')
    ->square()
    ->height(70),

            Tables\Columns\TextColumn::make('title')
                ->searchable(),

            Tables\Columns\TextColumn::make('category'),

            Tables\Columns\TextColumn::make('duration'),

            Tables\Columns\TextColumn::make('price')
->money('INR'),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
