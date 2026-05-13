<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'News';

    protected static ?string $modelLabel = 'News Article';

    protected static ?string $pluralModelLabel = 'News';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'news';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Article')
                    ->description('Write the public news article used by the landing page and /news detail pages.')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Forms\Set $set, Forms\Get $get): void {
                                if (blank($get('slug')) && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Textarea::make('excerpt')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Taxonomy')
                    ->schema([
                        Forms\Components\Select::make('author_id')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                                Forms\Components\TextInput::make('slug')->required()->maxLength(255),
                                Forms\Components\Textarea::make('bio')->rows(3)->columnSpanFull(),
                                Forms\Components\TextInput::make('photo')->label('Photo URL')->url()->maxLength(2048),
                                Forms\Components\TextInput::make('email')->email()->maxLength(255),
                                Forms\Components\Toggle::make('is_active')->default(true),
                            ]),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                                Forms\Components\TextInput::make('slug')->required()->maxLength(255),
                                Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
                                Forms\Components\Toggle::make('is_active')->default(true),
                            ]),
                        Forms\Components\Select::make('tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                                Forms\Components\TextInput::make('slug')->required()->maxLength(255),
                                Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
                                Forms\Components\Toggle::make('is_active')->default(true),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Publishing')
                    ->schema([
                        Forms\Components\Placeholder::make('current_featured_image')
                            ->label('Current featured image')
                            ->content(fn (?BlogPost $record): HtmlString|string => filled($record?->featured_image_url)
                                ? new HtmlString('<a class="bsa-current-media-link" href="' . e($record->featured_image_url) . '" target="_blank" rel="noopener">View current image</a>')
                                : 'No featured image uploaded yet.')
                            ->visible(fn (?BlogPost $record): bool => filled($record?->featured_image))
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('featured_image_upload')
                            ->label('Featured image')
                            ->image()
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('1600')
                            ->imageResizeTargetHeight('900')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->disk('public')
                            ->directory('news')
                            ->visibility('public')
                            ->helperText('Upload the main news artwork used on the landing page and detail page.')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'scheduled' => 'Scheduled',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->required()
                            ->default('draft'),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->seconds(false),
                        Forms\Components\DateTimePicker::make('scheduled_at')
                            ->seconds(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('SEO')
                    ->description('PRD requires SEO metadata on public article detail pages.')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->maxLength(60),
                        Forms\Components\TextInput::make('meta_keywords')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('meta_description')
                            ->maxLength(160)
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('canonical_url')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\Placeholder::make('current_og_image')
                            ->label('Current Open Graph image')
                            ->content(fn (?BlogPost $record): HtmlString|string => filled($record?->og_image_url)
                                ? new HtmlString('<a class="bsa-current-media-link" href="' . e($record->og_image_url) . '" target="_blank" rel="noopener">View current share image</a>')
                                : 'No Open Graph image set yet.')
                            ->visible(fn (?BlogPost $record): bool => filled($record?->og_image))
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('og_image_upload')
                            ->label('Open Graph image')
                            ->image()
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('1200')
                            ->imageResizeTargetHeight('630')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->disk('public')
                            ->directory('news/og')
                            ->visibility('public')
                            ->helperText('Optional. Leave empty to reuse the featured image.'),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image_url')
                    ->label('Image')
                    ->square(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'scheduled' => 'warning',
                        'archived' => 'gray',
                        default => 'info',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('view_count')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('author')
                    ->relationship('author', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('published_at', 'desc');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function normalizeFormData(array $data): array
    {
        if (filled($data['featured_image_upload'] ?? null)) {
            $data['featured_image'] = $data['featured_image_upload'];
        }

        unset($data['featured_image_upload']);

        if (filled($data['og_image_upload'] ?? null)) {
            $data['og_image'] = $data['og_image_upload'];
        }

        unset($data['og_image_upload']);

        if (blank($data['slug'] ?? null) && filled($data['title'] ?? null)) {
            $data['slug'] = Str::slug((string) $data['title']);
        }

        if (($data['status'] ?? null) === 'published' && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        if (blank($data['canonical_url'] ?? null) && filled($data['slug'] ?? null)) {
            $data['canonical_url'] = url('/news/' . $data['slug']);
        }

        if (blank($data['meta_title'] ?? null) && filled($data['title'] ?? null)) {
            $data['meta_title'] = Str::limit((string) $data['title'] . ' | Black Sky News', 60, '');
        }

        if (blank($data['meta_description'] ?? null) && filled($data['excerpt'] ?? null)) {
            $data['meta_description'] = Str::limit((string) $data['excerpt'], 160, '');
        }

        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
