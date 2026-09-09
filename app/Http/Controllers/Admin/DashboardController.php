<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleName = optional($user)->role?->name;

        if ($roleName !== 'Admin') {
            return redirect()->route($this->getDashboardRoute($roleName));
        }

        return view('admin.dashboard');
    }

    private function getDashboardRoute(?string $roleName): string
    {
        return match ($roleName) {
            'Internship' => 'internship.dashboard',
            'Pembimbing' => 'pembimbing.dashboard',
            default => 'login',
        };
    }
}