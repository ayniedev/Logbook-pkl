<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $roleName = optional($user)->role?->name;

        return match ($roleName) {
            'Admin' => redirect()->route('admin.dashboard'),
            'Internship' => redirect()->route('internship.dashboard'),
            'Pembimbing' => redirect()->route('pembimbing.dashboard'),
            default => redirect()->route('login'),
        };
    }
}