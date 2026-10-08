<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $student = $user->student;

        $stats = [
            'total_orders' => Order::where('student_id', $student?->id)->count(),
            'active_orders' => Order::where('student_id', $student?->id)->whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_READY,
            ])->count(),
            'completed_orders' => Order::where('student_id', $student?->id)->where('status', Order::STATUS_COMPLETED)->count(),
        ];

        return view('student.profile', compact('user', 'student', 'stats'));
    }

    public function topup(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:5000', 'max:500000'],
        ], [
            'amount.min' => 'Nominal minimal top up adalah Rp 5.000.',
            'amount.max' => 'Nominal maksimal top up adalah Rp 500.000.',
        ]);

        $student = $request->user()->student;

        if (! $student) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $student->increment('balance', (int) $data['amount']);

        return back()->with('success', 'Top up berhasil! Saldo kamu sekarang '.rupiah($student->fresh()->balance).'.');
    }
}

