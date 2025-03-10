<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommandeResource\Pages;
use App\Filament\Resources\CommandeResource\RelationManagers;
use App\Filament\Resources\CommandeResource\RelationManagers\AdresseRelationManager;
use App\Models\Commande;
use App\Models\Produit;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Number;

class CommandeResource extends Resource
{
    protected static ?string $model = Commande::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Information de commande')->schema([
                        Select::make('user_id')
                             ->label('Utilisateur')
                             ->relationship('user','name')
                             ->searchable()
                             ->preload()
                             ->required(),

                        Select::make('payment_method')
                             ->options([
                                'stripe' => 'Stripe',
                                'cod' => 'Cash on delivery'
                             ])
                             ->required(),

                        Select::make('payment_status')
                              ->options([
                                'pending' => 'En cours',
                                'paid' => 'Payer',
                                'failed' =>'Echoue'
                              ])
                              ->default('pending')
                              ->required(),

                        ToggleButtons::make('status')
                              ->inline()
                              ->default('new')
                              ->required()
                              ->options([
                                'new' => 'Nouveau',
                                'processing' => 'Processus',
                                'shipped' => 'Accepter',
                                'delivered' => 'Livrer',
                                'Cancelled' => 'Annuler'
                              ])
                              ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'shipped' => 'success',
                                'delivered' => 'success',
                                'Cancelled' => 'danger'
                              ])
                              ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'Cancelled' => 'heroicon-m-x-circle'
                              ]),

                        Select::make('currency')
                              ->options([
                                'dollar' => 'DOLLAR',
                                'eur' => 'EUR',
                                'mga' => 'MGA',
                                'usd' => 'USD'
                              ])
                              ->default('dollar')
                              ->required(),

                        Select::make('shipping_method')
                              ->options([
                                'fedex' => 'FedEx',
                                'ups' => 'UPS',
                                'dhl' => 'DHL',
                                'usps' => 'USPS'
                              ]),

                        Textarea::make('notes')
                              ->columnSpanFull()

                    ])->columns(2),

                        Section::make('Commande Items')->schema([
                            Repeater::make('items')
                              ->relationship()
                              ->schema([

                            Select::make('produit_id')
                                ->relationship('produit', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->distinct()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->columnSpan(4)
                                ->reactive()
                                ->afterStateUpdated(fn($state, Set $set) => $set('unit_amount', Produit::find($state)?->price ?? 0))
                                ->afterStateUpdated(fn($state, Set $set) => $set('total_amount', Produit::find($state)?->price ?? 0)),

                            TextInput::make('quantity')
                               ->numeric()
                               ->required()
                               ->default(1)
                               ->minValue(1)
                               ->columns(2)
                               ->reactive()
                               ->afterStateUpdated(fn($state, Set $set, Get $get) =>$set('total_amount', $state*$get('unit_amount'))),

                            TextInput::make('unit_amount')
                               ->numeric()
                               ->required()
                               ->disabled()
                               ->dehydrated()
                               ->columnSpan(3),

                            TextInput::make('total_amount')
                               ->numeric()
                               ->required()
                               ->columnSpan(3)
                               ->dehydrated()
                             ])->columns(12),

                            Placeholder::make('grand_total_placeholder')
                               ->label('Grand total')
                               ->content(function(Get $get, Set $set){
                                $total = 0;
                                if(!$repeaters = $get('items')){
                                    return $total;
                                }
                                foreach ($repeaters as $key =>$repeater){
                                    $total += $get("items.{$key}.total_amount");
                                }
                                $set('grand_total', $total);
                                return Number::currency($total, 'EUR');
                               }),
                            Hidden::make('grand_total')
                               ->default(0)
                        ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Client')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('grand_total')
                    ->label('Total')
                    ->numeric()
                    ->sortable()
                    ->money('EUR'),


                TextColumn::make('payment_method')
                    ->label('Methode de payment')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('payment_status')
                   ->label('Statut')
                   ->searchable()
                   ->sortable(),

                TextColumn::make('currency')
                   ->label('Devise')
                   ->searchable()
                   ->sortable(),

                TextColumn::make('shipping_method')
                   ->label('Expédition')
                   ->searchable()
                   ->sortable(),

                SelectColumn::make('status')
                   ->options([
                    'new' => 'Nouveau',
                    'processing' => 'Processus',
                    'shipped' => 'Accepter',
                    'delivered' => 'Livrer',
                    'Cancelled' => 'Annuler'
                   ])
                   ->searchable()
                   ->sortable(),

                TextColumn::make('created_at')
                   ->label('Date de creation')
                   ->dateTime()
                   ->sortable()
                   ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                   ->label('Date de modification')
                   ->dateTime()
                   ->sortable()
                   ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()

              ])
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
            AdresseRelationManager::class
        ];
    }

    public static function getNavigationBadge():?string{
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null{
        return static::getModel()::count() > 10? 'success' : 'danger';
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommandes::route('/'),
            'create' => Pages\CreateCommande::route('/create'),
            'view' => Pages\ViewCommande::route('/{record}'),
            'edit' => Pages\EditCommande::route('/{record}/edit'),
        ];
    }
}
