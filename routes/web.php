
<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();
Route::middleware(['auth'])->group(function () {

    Route::get('home',[HomeController::class, 'index'])->name('home');

    
    //rota admin
    Route::middleware(['is_admin'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])
            ->name('dashboard');
            
        
        Route::get('/users', function () {
            return view('users.index');
        })->name('admin.user');

        Route::post('/users', function () {
            return view('users.index');
        })->name('admin.user');
        
        Route::get('/artists', function () {
            return view('artistas.index');
        })->name('admin.artists');

        Route::post('/artists', function () {
            return view('artistas.index');
        })->name('admin.artists');

        Route::get('/musics', function () {
            return view('musics.index');
        })->name('admin.musics');

        Route::post('/musics', function () {
            return view('musics.index');
        })->name('admin.musics');
    });
});
