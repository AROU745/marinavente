<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);
Route::redirect('/index', '/');
Route::get('partenaires', [HomeController::class, 'partenaires'])->name('partenaires');
Route::get('nos-partenariats', [HomeController::class, 'nosPartenariats'])->name('nos.partenariats');
Route::get('apropos', [HomeController::class, 'apropos'])->name('apropos');
Route::get('contact', [HomeController::class, 'contact'])->name('contact');
Route::get('formations', [HomeController::class, 'formations'])->name('formations');
Route::get('offreemplois', [HomeController::class, 'offreEmplois'])->name('offreemplois');
Route::get('digitalservice', [HomeController::class, 'digitalService'])->name('digitalservice');
Route::get('digitalservice/{slug}', [HomeController::class, 'digitalServiceShow'])->name('digitalservice.show');
Route::get('conception-application', [HomeController::class, 'conceptionApplication'])->name('conception.application');
Route::get('conception-application/{slug}', [HomeController::class, 'conceptionApplicationShow'])->name('conception.application.show');
Route::redirect('/services/education-formation', '/services/conception-application');

// Projets environnement
Route::get('projetsenvironnement', [HomeController::class, 'projetsEnvironnement'])->name('projets.environnement');
Route::get('projetsarealiser', [HomeController::class, 'projetsARealiser'])->name('projets.arealiser');
Route::get('projetrealiser', [HomeController::class, 'projetRealiser'])->name('projets.realiser');
Route::get('soutenir/{slug}', [HomeController::class, 'soutenirProjet'])->name('projets.soutenir');

// Business Center catalogue
Route::redirect('/services/business-center/international', '/services/business-center');
Route::get('/services/business-center/catalogue/{pays?}', [HomeController::class, 'businessCatalogue'])
    ->where('pays', 'togo|chine|turquie|nigeria|tous')
    ->name('business.catalogue');
Route::get('/services/business-center/commande/{slug}', [HomeController::class, 'businessCommande'])
    ->name('business.commande');
Route::get('/services/immobilier-btp/biens/{type}', [HomeController::class, 'immobilierBiens'])
    ->where('type', 'vente|location|terrains|investir')
    ->name('immobilier.biens');
Route::get('/services/immobilier-btp/biens/{type}/{slug}', [HomeController::class, 'immobilierBienShow'])
    ->where('type', 'vente|location|terrains|investir')
    ->name('immobilier.bien.show');
Route::get('/services/sous-traitance/inscription', [HomeController::class, 'sousTraitanceInscription'])
    ->name('sous-traitance.inscription');
Route::get('/services/sous-traitance/deposer-projet', [HomeController::class, 'sousTraitanceProjet'])
    ->name('sous-traitance.projet');
Route::get('/services/{slug}', [HomeController::class, 'service'])->name('services.show');

Route::get('/creer-ma-boutique', [BoutiqueController::class, 'create'])->name('boutiques.create');
Route::post('/creer-ma-boutique', [BoutiqueController::class, 'store'])->name('boutiques.store');
Route::get('/creer-ma-boutique/succes', [BoutiqueController::class, 'success'])->name('boutiques.success');
Route::get('/boutique/{slug}', [BoutiqueController::class, 'show'])->name('boutiques.show');
Route::get('/boutique/{slug}/article/{produit}', [BoutiqueController::class, 'showProduit'])->name('boutiques.produit');

Route::get('/connexion', [AuthController::class, 'showConnexion'])->name('connexion');
Route::post('/connexion', [AuthController::class, 'login'])->name('connexion.submit');
Route::get('/inscription', [AuthController::class, 'showInscription'])->name('inscription');
Route::post('/inscription', [AuthController::class, 'register'])->name('inscription.submit');

Route::get('/utilisateur/connexion', [AuthController::class, 'showUserLogin'])->name('user.login');
Route::post('/utilisateur/connexion', [AuthController::class, 'loginUser'])->name('user.login.submit');
Route::get('/utilisateur/inscription', [AuthController::class, 'showUserRegister'])->name('user.register');
Route::post('/utilisateur/inscription', [AuthController::class, 'registerUser'])->name('user.register.submit');
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/mon-espace', [AuthController::class, 'dashboard'])->name('user.dashboard');
});

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/inscription', [AdminAuthController::class, 'showRegister'])->name('admin.register');
Route::post('/admin/inscription', [AdminAuthController::class, 'register'])->name('admin.register.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('admin.boutique')->group(function () {
    Route::get('/admin/boutiques', [BoutiqueController::class, 'adminIndex'])->name('boutiques.admin');
    Route::get('/admin/boutiques/creer', [BoutiqueController::class, 'adminCreate'])->name('boutiques.admin.create');
    Route::post('/admin/boutiques', [BoutiqueController::class, 'adminStore'])->name('boutiques.admin.store');
    Route::get('/admin/boutiques/{boutique}/modifier', [BoutiqueController::class, 'adminEdit'])->name('boutiques.admin.edit');
    Route::put('/admin/boutiques/{boutique}', [BoutiqueController::class, 'adminUpdate'])->name('boutiques.admin.update');
    Route::delete('/admin/boutiques/{boutique}', [BoutiqueController::class, 'adminDestroy'])->name('boutiques.admin.destroy');
    Route::get('/admin/commandes-ia', [BoutiqueController::class, 'adminCommandesIa'])->name('boutiques.admin.commandes-ia');
    Route::patch('/admin/commandes-ia/{commande}', [BoutiqueController::class, 'adminUpdateCommandeIa'])->name('boutiques.admin.commandes-ia.update');
});
