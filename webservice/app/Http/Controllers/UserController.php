<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Login de Usuário
     * @param Request $request
     * @return array|false[]
     */
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validate->fails()) {
            return [
                'status' => false,
                'validation' => true,
                'errors' => $validate->errors()
            ];
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = \auth()->user();
            $user->token = $user->createToken($user->email)->accessToken;
            $user->image = asset($user->image);

            return [
                'status' => true,
                'user' => $user
            ];
        } else {
            return [
                'status' => false
            ];
        }

    }


    /**
     * Registrar Novo Usuário
     * @param Request $request
     * @return array
     */
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validate->fails()) {
            return [
                'status' => false,
                'validation' => true,
                'errors' => $validate->errors()
            ];
        }

        $image = "/perfils/padrao.png";

        $user = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'image' => $image
        ]);

        $user->token = $user->createToken($user->email)->accessToken;
        $user->image = asset($user->image);

        return [
            'status' => true,
            'user' => $user
        ];
    }


    /**
     * Perfil do Usuário
     * @param Request $request
     * @return \Illuminate\Support\MessageBag|mixed
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        $data = $request->all();

        if (isset($data['password'])) {
            $validate = Validator::make($data, [
                'name' => 'required|string',
                'email' => ['required', 'string', 'email', Rule::unique('users')->ignore($user->id)],
                'password' => 'required|string|min:6|confirmed'
            ]);

            if ($validate->fails()) {
                return [
                    'status' => false,
                    'validation' => true,
                    'errors' => $validate->errors()
                ];
            }

            $user->password = bcrypt($data['password']);
        } else {
            $validate = Validator::make($data, [
                'name' => 'required|string',
                'email' => ['required', 'string', 'email', Rule::unique('users')->ignore($user->id)],
            ]);

            if ($validate->fails()) {
                return [
                    'status' => false,
                    'validation' => true,
                    'errors' => $validate->errors()
                ];
            }

            $user->name = $data['name'];
            $user->email = $data['email'];

        }

        if (isset($data['image'])) {

            Validator::extend('base64image', function ($attribute, $value, $parameters, $validator) {
                $explode = explode(',', $value);
                $allow = ['png', 'jpg', 'svg', 'jpeg'];
                $format = str_replace(
                    [
                        'data:image/',
                        ';',
                        'base64',
                    ],
                    [
                        '', '', ''
                    ],
                    $explode[0]
                );

                if (!in_array($format, $allow)) {
                    return false;
                }

                if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
                    return false;
                }

                return true;
            });

            $validate = Validator::make($data, [
                'image' => 'base64image',
            ], ['base64image' => 'Imagem Inválida']);

            if ($validate->fails()) {
                return [
                    'status' => false,
                    'validation' => true,
                    'errors' => $validate->errors()
                ];
            }

            $time = time();
            $diretorioPai = 'perfils';
            $diretorioImagem = $diretorioPai . DIRECTORY_SEPARATOR . 'perfil_id' . $user->id;
            $ext = substr($data['image'], 11, strpos($data['image'], ';') - 11);

            $urlImagem = $diretorioImagem . DIRECTORY_SEPARATOR . $time . '.' . $ext;

            $file = str_replace('data:image/' . $ext . ';base64,', '', $data['image']);
            $file = base64_decode($file);

            if (!file_exists($diretorioPai)) {
                mkdir($diretorioPai, 0700);
            }

            if ($user->image) {
                if (file_exists($user->image)) {
                    unlink($user->image);
                }
            }

            if (!file_exists($diretorioImagem)) {
                mkdir($diretorioImagem, 0700);
            }

            file_put_contents($urlImagem, $file);
            $user->image = $urlImagem;
        }

        $user->save();

        $user->image = asset($user->image);
        $user->token = $user->createToken($user->email)->accessToken;

        return [
            'status' => true,
            'user' => $user
        ];
    }
}
