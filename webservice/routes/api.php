<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [UserController::class, 'login']);
Route::post('/cadastro', [UserController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {

    Route::put('/perfil', [UserController::class, 'profile']);
    Route::post('/conteudo/adicionar', [ContentController::class, 'adicionar']);
});

Route::get('/tests', function () {
//    $user = \App\User::find(1);
//    $user2 = \App\User::find(2);
//    $user->contents()->create([
//        'title' => 'Conteúdo de Teste 02',
//        'description' => 'Essa é uma descrição de teste de conteúdo 02',
//        'image' => 'URL da Imagem 02',
//        'link' => 'Link 02',
//        'date' => '2021-05-16' //date('Y-m-d')
//    ]);
//
//    return $user->contents;

    /*
     * Adicionar Amigo
     */
//    $user->friends()->attach($user2->id);
//    $user->friends()->detach($user2->id);
//    $user->friends()->toggle($user2->id);

//    return $user->friends;

    /*
 * Adicionar Curtidas
 */
//    $content = \App\Content::find(1);
//    $user->likes()->toggle($content->id);

//    return $content->likes()->count();
//    return $content->likes;

    /**
     * Add Comentários
     */
//    $content = \App\Content::find(1);
//        $user->comments()->create([
//            'content_id' => $content->id,
//        'description' => 'Essa é uma descrição de teste de conteúdo 003',
//        'date' => date('Y-m-d')
//    ]);
//
//    $user2->comments()->create([
//        'content_id' => $content->id,
//        'description' => 'Essa é uma descrição de teste de conteúdo 004',
//        'date' => date('Y-m-d')
//    ]);
//
//    return $content->comments;
});

