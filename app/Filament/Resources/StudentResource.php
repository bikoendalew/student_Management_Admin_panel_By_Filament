<?php

namespace App\Filament\Resources;

use App\Events\PromotStudent;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers\GuardiansRelationManager;
use App\Models\Certificate;
use App\Models\CertificateStudent;
use App\Models\Student;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $recordTitleAttribute='name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Forms\Components\Section::make('Personal Information')->schema([
                    Forms\Components\TextInput::make(name:'name')->required()->maxlength(length:255)
                    ->minLength(length:2),
                    Forms\Components\TextInput::make(name:'age')->required(),
                    Forms\Components\TextInput::make(name:'student_id')->required(),
                    Forms\Components\TextInput::make(name:'address')->required(),
  
                 ])->collapsed(),

                 Forms\Components\Section::make('Grade Information')->schema([
                    Forms\Components\Select::make(name:'standard_id')->required()
                    ->relationship('standard','name'),
                 ])->collapsed(),

                Forms\Components\Section::make('Other Information')->schema([
                    Forms\Components\Repeater::make('vitals')->schema([
                        Forms\Components\Select::make('Name')->options(
                            config('vital_config')
                        )->required(),
                        Forms\Components\TextInput::make('value')
                    ]),
                ])->collapsed(),

              
                Forms\Components\Section::make('Certificates')
                ->description('Add student certificate information')
                ->collapsible()
                ->schema([
                    Forms\Components\Repeater::make('certificates')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('certificate_id')
                                ->options(Certificate::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                            Forms\Components\TextInput::make('description'),
                        ])
                        ->defaultItems(0)
                        ->columns(2),
                ]),


              
               
              
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(name:'student_id'),
                Tables\Columns\TextColumn::make(name:'name')->searchable(),
                Tables\Columns\TextColumn::make(name:'age'),
                Tables\Columns\TextColumn::make(name:'address'),
                Tables\Columns\TextColumn::make(name:'standard.name'),
            ])
            ->filters([
               Tables\Filters\Filter::make('start')->query(fn(Builder $query):Builder=>$query->where(
                'standard_id',1)),

              
                SelectFilter::make('All Standard')->relationship('standard','name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('promote')->action(function (Student $record){
                        $record->standard_id=$record->standard_id+1;
                        $record->save();
                    })->requiresConfirmation()->color('success'),
                    Tables\Actions\Action::make('Demote')->action(function (Student $record){
                        if($record->standard_id>1){
                            $record->standard_id=$record->standard_id-1;
                            $record->save();
                        }
                     
                    })->requiresConfirmation()->color('danger'),
                ])
               
              
              
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),

                 Tables\Actions\BulkAction::make('promote all')
                 ->action(function(Collection  $records){
                    $records->each(function($record){
                     event(new PromotStudent($record));
                    });
                    
                })->requiresConfirmation()
                ->deselectRecordsAfterCompletion()

                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
          GuardiansRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];

    }    

   
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Name' => $record->name,
            'Standard' => $record->standard->name
        ];
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        return[
            Action::make('edit')->IconButton()->icon('heroicon-s-pencil')
            ->url(static::getUrl('edit',['record'=>$record]))
        ];
    }
}
