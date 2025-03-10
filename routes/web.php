<?php

use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CategoriesPage;
use App\Livewire\HomePage;
use App\Livewire\MonCommandeDetailPage;
use App\Livewire\MonCommandePage;
use App\Livewire\PanierPage;
use App\Livewire\ProduitDetailPage;
use App\Livewire\ProduitsPage;
use App\Livewire\SuccessPage;
use App\Livewire\VerifiéPage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/produits', ProduitsPage::class);
Route::get('/panier', PanierPage::class);
Route::get('/produits/{slug}', ProduitDetailPage::class);

Route::get('/verifié', VerifiéPage::class);
Route::get('/mon-commande', MonCommandePage::class);
Route::get('/mon-commande/{commande}', MonCommandeDetailPage::class);

Route::get('/login', LoginPage::class);
Route::get('/register', RegisterPage::class);
Route::get('/forgot', ForgotPasswordPage::class);
Route::get('/reset', ResetPasswordPage::class);

Route::get('/success', SuccessPage::class);
Route::get('/cancel', CancelPage::class);
