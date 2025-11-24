<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailViewResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailViewResponses implements VerifyEmailViewResponse
{
    public function toResponse($request): Response
    {
        return response()->view('auth.verify-email');
    }
}
