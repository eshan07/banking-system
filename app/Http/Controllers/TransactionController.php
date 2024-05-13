<?php

namespace App\Http\Controllers;



use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function depositIndex()
    {
        $transactions = auth()->user()->transactions()
            ->where('transaction_type', 'deposit')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('deposit.index', compact('transactions'));
    }

    public function depositStore(Request $request)
    {
        try {
            $validated = $this->validateTransaction($request);

            DB::transaction(function () use ($validated, $request) {
                $this->processTransaction('deposit', $validated, $request);
            });

            return redirect()->route('deposits.index');
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred during the deposit. Please try again later.');
        }
    }

    public function withdrawalIndex()
    {
        $transactions = auth()->user()->transactions()
            ->where('transaction_type', 'withdrawal')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('withdrawal.index', compact('transactions'));
    }

    public function withdrawalStore(Request $request)
    {
        try {
            $validated = $this->validateTransaction($request);

            DB::transaction(function () use ($validated, $request) {
                $this->processTransaction('withdrawal', $validated, $request);
            });

            return redirect()->route('withdrawal.index');
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred during the withdrawal. Please try again later.');
        }
    }

    protected function validateTransaction(Request $request)
    {
        return $request->validate([
            'amount' => ['required', 'numeric'],
        ]);
    }

    protected function processTransaction($type, $validated, $request)
    {
        $user = $request->user();
        $transactionType = $type === 'deposit' ? 'deposit' : 'withdrawal';

        $withdrawalFee = $user->account_type === 'individual' ? 0.025 : 0.015;

        if ($user->account_type === 'individual' && $type === 'withdrawal') {
            $today = now()->format('l');
            $monthlyWithdrawals = $user->transactions()
                ->where('transaction_type', 'withdrawal')
                ->whereMonth('created_at', now()->month)
                ->sum('amount');

            if ($today == 'Friday' || $validated['amount'] <= 1000 || $monthlyWithdrawals <= 5000) {
                $withdrawalFee = 0;
            }
        }

        if ($user->account_type === 'business' && $user->total_withdrawal >= 50000) {
            $withdrawalFee = 0.015;
        }

        $feeAmount = $validated['amount'] * $withdrawalFee;

        if ($type === 'withdrawal' && $user->balance < $validated['amount'] + $feeAmount) {
            throw new \Exception('Insufficient balance');
        }

        $user->transactions()->create([
            'transaction_type' => $transactionType,
            'amount' => $validated['amount'],
            'fee' => $feeAmount,
        ]);


        $user->balance -= ($validated['amount'] + $feeAmount);
        $user->save();
    }

    protected function handleValidationException(ValidationException $e)
    {
        return back()->withErrors($e->validator->errors());
    }
}
