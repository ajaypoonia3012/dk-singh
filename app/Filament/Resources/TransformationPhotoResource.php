<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransformationPhotoResource\Pages;
use App\Filament\Resources\TransformationPhotoResource\RelationManagers;
use App\Models\TransformationPhoto;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;



class TransformationPhotoResource extends Resource
{
    protected static ?string $model = TransformationPhoto::class;

    protected static ?string $navigationIcon =
'heroicon-o-camera';

protected static ?string $navigationLabel =
    'Transformation Photos';

protected static ?string $navigationGroup =
    'Coaching';

    public static function form(Form $form): Form
{
    return $form
        ->schema([

            Select::make('user_id')
                ->label('Member')
                ->options(
                    User::orderBy('name')
                        ->pluck('name', 'id')
                )
                ->searchable()
                ->required(),

            FileUpload::make('front_photo')
                ->image()
                ->directory('transformations')
                ->required(),

            FileUpload::make('side_photo')
                ->image()
                ->directory('transformations'),

            FileUpload::make('back_photo')
                ->image()
                ->directory('transformations'),

            Textarea::make('notes')
                ->rows(4)
                ->columnSpanFull(),

        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

    TextColumn::make('user.name')
        ->label('Member')
        ->searchable()
        ->sortable(),

    ImageColumn::make('front_photo')
        ->label('Front'),

    ImageColumn::make('side_photo')
        ->label('Side'),

    ImageColumn::make('back_photo')
        ->label('Back'),

    TextColumn::make('created_at')
        ->dateTime('d M Y')
        ->sortable(),

])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransformationPhotos::route('/'),
            'create' => Pages\CreateTransformationPhoto::route('/create'),
            'edit' => Pages\EditTransformationPhoto::route('/{record}/edit'),
        ];
    }
}
