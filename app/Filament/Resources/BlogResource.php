<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
{
    return $form
        ->schema([

            Forms\Components\TextInput::make('title')
                ->required(),

            Forms\Components\TextInput::make('slug')
                ->required(),

            Forms\Components\RichEditor::make('content')
                ->required(),

            Forms\Components\FileUpload::make('featured_image'),

            Forms\Components\Toggle::make('featured'),

            Forms\Components\Toggle::make('status'),

        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([

            Tables\Columns\ImageColumn::make('featured_image'),

            Tables\Columns\TextColumn::make('title'),

            Tables\Columns\IconColumn::make('featured')
                ->boolean(),

            Tables\Columns\IconColumn::make('status')
                ->boolean(),

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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
