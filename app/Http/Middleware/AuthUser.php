<?php

namespace App\Http\Middleware;

use App\Http\Service\AuthService;
use App\Models\Customer;
use Closure;

class AuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authService = app(AuthService::class);
        if(!$authService->auth()){
            return redirect('/login');
        }

        $customer = Customer::query()
            ->where(Customer::FIELD_ID,$authService->authUser())
            ->select([
                Customer::FIELD_ID,
                Customer::FIELD_NICKNAME,
                Customer::FIELD_PHONE,
                Customer::FIELD_AVATAR
            ])
            ->first();

        $request->offsetSet('user',$customer);

        return $next($request);
    }
}
