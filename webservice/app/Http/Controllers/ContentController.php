<?php

namespace App\Http\Controllers;

use App\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function adicionar(Request $request)
    {
        $user = $request->user();

        $content = new Content();
        $content->title = $request['titulo'];
        $content->description = $request['texto'];
        $content->image = $request['imagem'] ?? '#';
        $content->link = $request['link'] ?? '#';
        $content->date = date('Y-m-d H:i:s');

        $user->contents()->save($content);

        return ['status' => true, 'contents' => $user->contents];

    }
}
