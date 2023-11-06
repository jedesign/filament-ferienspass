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
                ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

            TextInput::make('slug')
                ->disabled()
                ->required()
                ->unique(Course::class, 'slug', fn($record) => $record),

            Textarea::make('description')
                ->required(),

            Select::make('state')
                ->enum(CourseState::class)
                ->options(CourseState::values())
                ->selectablePlaceholder(false)
                ->required()
                ->markAsRequired(false),

            DateTimePicker::make('start')
                ->native(false)
                ->seconds(false)
                ->suffixIcon('heroicon-m-calendar-days'),

            DateTimePicker::make('end')
                ->native(false)
                ->seconds(false)
                ->suffixIcon('heroicon-m-calendar-days'),

            Select::make('day_span')
                ->enum(DaySpan::class)
                ->options(DaySpan::values())
                ->required()
                ->markAsRequired(false),

            TextInput::make('min_participants')
                ->required()
                ->integer()
                ->minValue(5)
                ->suffixIcon('heroicon-m-user-group'),

            TextInput::make('max_participants')
                ->required()
                ->integer()
                ->suffixIcon('heroicon-m-user-group'),

            Select::make('grade_group')
                ->enum(GradeGroup::class)
                ->options(GradeGroup::values())
                ->required()
                ->markAsRequired(false),

            Textarea::make('meeting_point')
                ->required(),

            Textarea::make('clothes'),

            Textarea::make('bring_along'),

            TextInput::make('price')
                ->required()
                ->numeric(),
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

            TextColumn::make('description')
                ->limit(50),

            TextColumn::make('state')
                ->sortable(),

            TextColumn::make('state_message'),

            TextColumn::make('beginning')
                ->date()
                ->sortable(),

            TextColumn::make('end')
                ->date()
                ->sortable(),

            TextColumn::make('day_span')
                ->sortable(),

            TextColumn::make('min_participants')
                ->sortable(),

            TextColumn::make('max_participants')
                ->sortable(),

            TextColumn::make('grade_group')
                ->sortable(),

            TextColumn::make('meeting_point'),

            TextColumn::make('clothes'),

            TextColumn::make('bring_along'),

            TextColumn::make('price')
                ->sortable()
                ->money('CHF'),
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
