<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

// Don't remove inspiration :)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Create a new administrator user.
Artisan::command('make:admin', function () {
    $username = $this->ask("Nombre del usuario [admin]:", "admin");

    $suggestion = str_replace('', trim($username), $username);
    $defaultEmail = "$suggestion@example.com";
    $email = $this->ask("Email [$defaultEmail]:", $defaultEmail);

    $exists = User::where("email", $email)->count() > 0;
    if ($exists) {
        $this->comment("El usuario ya existe");
        return 1;
    }

    /** @var User $user */
    $user = User::create([
        'name' => $username,
        'email' => $email,
        'password' => Hash::make('password'),
    ]);

    $token = $user->createToken("default_token", ['is-admin'])->plainTextToken;

    $this->comment("El token de acceso es: $token");

    return 0;
})->purpose('Create an administrator user.');

// Delete users. Useful for example, if you lost the token.
Artisan::command('destroy:user', function () {
    $email = $this->ask("Email del usuario [admin]:", "admin@example.com");
    $user = User::where('email', $email)->first();

    if(!$user) {
        $this->comment("El usuario no existe.");
        return 1;
    }

    $user->delete();
    $this->comment("Usuario eliminado.");
    return 0;

})->purpose("_Delete users from database");
