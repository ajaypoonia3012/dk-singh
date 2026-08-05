<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
protected static ?string $navigationGroup = 'Business';

   public static function form(Form $form): Form
{
    return $form
        ->schema([

            Forms\Components\Section::make('Service Information')
                ->schema([

                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, ?string $state) {
                            $set('slug', Str::slug($state));
                        }),

                    Forms\Components\TextInput::make('slug')
                        ->required()
->unique(ignoreRecord: true)
->readOnly(),

                    Forms\Components\Textarea::make('description')
                        ->rows(5)
                        ->columnSpanFull(),

                ])
                ->columns(2),

            Forms\Components\Section::make('Service Features')
                ->schema([

                    Forms\Components\Repeater::make('features')
                        ->schema([
                            Forms\Components\TextInput::make('feature')
                                ->required(),
                        ])
                        ->defaultItems(5)
                        ->columnSpanFull()
                        ->formatStateUsing(function ($state) {

                            if (is_string($state)) {
                                return collect(json_decode($state, true))
                                    ->map(fn ($item) => ['feature' => $item])
                                    ->toArray();
                            }

                            return $state;
                        })
                        ->dehydrateStateUsing(function ($state) {

                            return json_encode(
                                collect($state)->pluck('feature')->toArray()
                            );

                        }),

                ]),

            Forms\Components\Section::make('Pricing')
                ->schema([

                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->prefix('₹')
                        ->required(),

                    Forms\Components\TextInput::make('duration')
                        ->default('12 Weeks'),

                    Forms\Components\TextInput::make('button_text')
                        ->default('Enroll Now'),

                ])
                ->columns(3),

            Forms\Components\Section::make('Media')
                ->schema([

                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '16:9',
                        ])
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeMode('cover')
                        ->imageResizeTargetWidth('1200')
                        ->imageResizeTargetHeight('675')
                       ->disk('public')
->directory('services')
                        ->imagePreviewHeight('220')
                        ->required(),

                ]),

            Forms\Components\Section::make('Settings')
    ->schema([

        Forms\Components\Toggle::make('featured')
            ->label('Show on Homepage'),

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

        Forms\Components\TextInput::make('seo_title')
            ->maxLength(255),

        Forms\Components\Textarea::make('seo_description')
            ->rows(4),

    ]),

             
        ]);
}


public static function table(Table $table): Table
{
    return $table
        ->columns([

            Tables\Columns\ImageColumn::make('image')
    ->disk('public')
    ->square()
    ->height(70),

            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('price')
                ->money('INR')
                ->sortable(),

            Tables\Columns\TextColumn::make('duration')
    ->sortable(),

Tables\Columns\TextColumn::make('sort_order')
    ->sortable(),
            Tables\Columns\IconColumn::make('featured')
                ->boolean(),

            Tables\Columns\IconColumn::make('status')
                ->boolean(),

        ])
        ->filters([
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
