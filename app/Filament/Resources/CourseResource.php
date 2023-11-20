<?php

namespace App\Filament\Resources;

use App\Enums\CourseState;
use App\Enums\DaySpan;
use App\Enums\GradeGroup;
use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state, ?string $old) {
                        $slug = $get('slug');

                        if (!$get('slug') || Str::slug($old) === $slug) {
                            $set('slug', Str::slug($state));
                        }
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->lazy()
                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                        if ($state) {
                            $set('slug', Str::slug($state));
                            return;
                        }

                        $set('slug', Str::slug($get('title')));

                    })
                    ->unique(Course::class, 'slug', fn($record) => $record)
                    ->suffixAction(
                        Action::make('regenerateSlug')
                            ->icon('heroicon-m-arrow-path')
                            ->requiresConfirmation()
                            ->action(function (Set $set, Get $get) {
                                $set('slug', Str::slug($get('title')));
                            })
                    ),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('state')
                    ->enum(CourseState::class)
                    ->options(CourseState::class)
                    ->selectablePlaceholder(false)
                    ->default(CourseState::DRAFT->value)
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('state_message'),
                Forms\Components\DateTimePicker::make('start')
                    ->native(false)
                    ->seconds(false)
                    ->displayFormat('d. F Y, H:i')
                    ->prefixIcon('heroicon-m-calendar-days')
                    ->closeOnDateSelection()
                    ->required(),
                Forms\Components\DateTimePicker::make('end')
                    ->native(false)
                    ->seconds(false)
                    ->displayFormat('d. F Y, H:i')
                    ->prefixIcon('heroicon-m-calendar-days')
                    ->closeOnDateSelection()
                    ->required()
                    ->after('start'),
                Forms\Components\Select::make('day_span')
                    ->enum(DaySpan::class)
                    ->options(DaySpan::class)
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('min_participants')
                    ->required()
                    ->integer()
                    ->minValue(5)
                    ->default(5)
                    ->prefixIcon('heroicon-m-user-group'),
                Forms\Components\TextInput::make('max_participants')
                    ->required()
                    ->integer()
                    ->gte('min_participants')
                    ->minValue(5)
                    ->prefixIcon('heroicon-m-user-group'),
                Forms\Components\Select::make('grade_group')
                    ->enum(GradeGroup::class)
                    ->options(GradeGroup::class)
                    ->native(false)
                    ->required(),
                Forms\Components\Textarea::make('meeting_point')
                    ->required(),
                Forms\Components\TextInput::make('clothes'),
                Forms\Components\TextInput::make('bring_along'),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->step(0.05)
                    ->prefix('CHF'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Course $record) => $record->slug),
                Tables\Columns\TextColumn::make('description')
                    ->limit(10)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('state')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('state_message')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day_span')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('min_participants')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_participants')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grade_group')
                    ->sortable(),
                Tables\Columns\TextColumn::make('meeting_point')
                    ->searchable(),
                Tables\Columns\TextColumn::make('clothes')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bring_along')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('CHF')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'description'];
    }
}
