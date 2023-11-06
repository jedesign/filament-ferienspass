<?php

namespace App\Filament\Resources;

use App\Enums\CourseState;
use App\Enums\DaySpan;
use App\Enums\GradeGroup;
use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $slug = 'courses';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->required()
                ->lazy()
                ->afterStateUpdated(function (Set $set, Get $get, ?string $state, ?string $old) {
                    $slug = $get('slug');

                    if (!$get('slug') || Str::slug($old) === $slug) {
                        $set('slug', Str::slug($state));
                    }
                }),

            TextInput::make('slug')
                ->required()
                ->lazy()
                ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                    if ($state) {
                        $set('slug', Str::slug($state));
                        return;
                    }
                    
                    $set('slug', Str::slug($get('title')));

                })
                ->unique(Course::class, 'slug', fn($record) => $record),

            Textarea::make('description')
                ->required(),

            Select::make('state')
                ->enum(CourseState::class)
                ->options(CourseState::class)
                ->selectablePlaceholder(false)
                ->default(CourseState::DRAFT->value)
                ->native(false)
                ->required(),

            DateTimePicker::make('start')
                ->native(false)
                ->seconds(false)
                ->displayFormat('d. F Y, H:i')
                ->prefixIcon('heroicon-m-calendar-days')
                ->closeOnDateSelection()
                ->required(),

            DateTimePicker::make('end')
                ->native(false)
                ->seconds(false)
                ->displayFormat('d. F Y, H:i')
                ->prefixIcon('heroicon-m-calendar-days')
                ->closeOnDateSelection()
                ->required()
                ->after('start'),

            Select::make('day_span')
                ->enum(DaySpan::class)
                ->options(DaySpan::class)
                ->native(false)
                ->required(),

            TextInput::make('min_participants')
                ->required()
                ->integer()
                ->minValue(5)
                ->default(5)
                ->prefixIcon('heroicon-m-user-group'),

            TextInput::make('max_participants')
                ->required()
                ->integer()
                ->gte('min_participants')
                ->minValue(5)
                ->prefixIcon('heroicon-m-user-group'),

            Select::make('grade_group')
                ->enum(GradeGroup::class)
                ->options(GradeGroup::class)
                ->native(false)
                ->required(),

            Textarea::make('meeting_point')
                ->required(),

            Textarea::make('clothes'),

            Textarea::make('bring_along'),

            TextInput::make('price')
                ->required()
                ->numeric()
                ->inputMode('decimal')
                ->step(0.05)
                ->prefix('CHF'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')
                ->searchable()
                ->sortable(),

            TextColumn::make('slug')
                ->searchable()
                ->sortable(),

            TextColumn::make('description'),

            TextColumn::make('state'),

            TextColumn::make('state_message'),

            TextColumn::make('beginning')
                ->date(),

            TextColumn::make('end')
                ->date(),

            TextColumn::make('day_span'),

            TextColumn::make('min_participants'),

            TextColumn::make('max_participants'),

            TextColumn::make('grade_group'),

            TextColumn::make('meeting_point'),

            TextColumn::make('clothes'),

            TextColumn::make('bring_along'),

            TextColumn::make('price'),
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
        return ['title', 'slug'];
    }
}
