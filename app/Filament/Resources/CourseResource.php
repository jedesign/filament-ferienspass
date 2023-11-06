<?php

namespace App\Filament\Resources;

use App\Enums\CourseState;
use App\Enums\DaySpan;
use App\Enums\GradeGroup;
use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
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
                ->selectablePlaceholder(false)
                ->required()
                ->markAsRequired(false),

            TextInput::make('min_participants')
                ->required()
                ->integer(),

            TextInput::make('max_participants')
                ->required()
                ->integer(),

            Select::make('grade_group')
                ->enum(GradeGroup::class)
                ->options(GradeGroup::values())
                ->selectablePlaceholder(false)
                ->required()
                ->markAsRequired(false),

            TextInput::make('meeting_point')
                ->required(),

            TextInput::make('clothes'),

            TextInput::make('bring_along'),

            TextInput::make('price')
                ->required()
                ->numeric(),

            Placeholder::make('created_at')
                ->label('Created Date')
                ->content(fn(?Course $record): string => $record?->created_at?->diffForHumans() ?? '-'),

            Placeholder::make('updated_at')
                ->label('Last Modified Date')
                ->content(fn(?Course $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
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
