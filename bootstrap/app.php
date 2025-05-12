<?php

use App\Classes\GlobalClass;
use App\Http\Middleware\AccessToModel;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectTo(function (){
            if (!request()->expectsJson()):
                return GlobalClass::unauthorized();
            endif;
        });
        $middleware->alias([
            'access_to_model'=>AccessToModel::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (AuthenticationException $e, $request) {
            return GlobalClass::unauthorized(message: "کاربر لاگین نکرده");
        });
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {

            return GlobalClass::forbidden(message: "شما مجاز نیستید");
        });

        $exceptions->renderable(function (\Exception $e, $request) {
            return GlobalClass::badRequest(message: $e->getMessage());
        });
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            return GlobalClass::notFound(message: "صفحه پیدا نشد");
        });

        $exceptions->renderable(function (\Throwable $e, $request) {
            return GlobalClass::serverError(message: 'An internal server error has occurred.',error: $e->getMessage());
        });
    })->create();
