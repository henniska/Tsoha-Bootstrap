<?php


class UserController extends BaseController{
  
    public static function login(){
        View::make('user/login.html');
    }

    public static function handle_login(){
        $params = $_POST;

        $user = User::authenticate($params['username'], $params['password']);

        if(!$user){
            View::make('user/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
        }else{
            $_SESSION['user'] = $user->id;

            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->name . '!'));
        }
    }

    public static function logout(){
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function showProfile(){
        $user = self::get_user_logged_in();
        View::make('user/profile.html', array('user' => $user));
    }

    public static function store(){
        $params = $_POST;
        $user = new User(array(
            'username' => $params['username'],
            'name' => $params['name'],
            'email' => $params['email'],
            'phone' => $params['phone'],
            'home_address' => $params['home_address'],
            'password' => $params['password']
        ));

        $errors = $user->errors();

        if(count($errors) > 0){
            View::make('user/register.html', array('errors' => $errors));
        }else{
            $user->save();

            Redirect::to('/login', array('message' => 'Käyttäjän rekisteröityminen onnistui!'));  
        }
    }

    public static function create(){
        View::make('user/register.html');
    }

    public static function edit(){
        $user = self::get_user_logged_in();
        View::make('user/editProfile.html', array('user' => $user));
    }

    public static function update(){
        $params = $_POST;
        $old_user = self::get_user_logged_in();
        $attributes = array(
            'id' => $old_user->id,
            'username' => $params['username'],
            'name' => $params['name'],
            'email' => $params['email'],
            'phone' => $params['phone'],
            'home_address' => $params['home_address'],
            'password' => $old_user->password
        );

        $user = new User($attributes);
        $errors = $user->errors();

        if(count($errors) > 0){
            View::make('user/editProfile.html', array('errors' => $errors, 'user' => $user));
        }else{
            $user->update();

            Redirect::to('/profile', array('message' => 'Profiilia on muokattu onnistuneesti!'));
        }
    }

    public static function destroy(){
        $user = self::get_user_logged_in();
        $user->destroy();
        self::logout();
    }
}