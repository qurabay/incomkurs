<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
class UserAuth
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
        $employee = User::where('token', $request->bearerToken())->first();
        if (!$employee) {
            $result = $this->errorMessage(trans('User not found!'));
            return response()->json($result, $result['statusCode']);
        }

        $request['user'] = $employee;
        return $next($request);
    }

    public function errorMessage($message = null)
    {
        return [
            'statusCode' => 401,
            'message'    => $message,
            'data'       => null
        ];
    }
}
