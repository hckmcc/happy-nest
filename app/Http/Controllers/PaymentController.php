<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Models\Ad;
use App\Models\PromotionType;
use App\Models\Transaction;
use App\Services\YukassaService;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use YooKassa\Common\Exceptions\ApiConnectionException;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\AuthorizeException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\Payment\PaymentStatus;

class PaymentController extends Controller
{
    private $yukassaService;

    public function __construct(YukassaService $yukassaService)
    {
        $this->yukassaService = $yukassaService;
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector|void
     * @throws ApiConnectionException
     * @throws ApiException
     * @throws AuthorizeException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function create(Request $request, Ad $ad)
    {
        $validated = $request->validate([
            'promotion_id' => 'required|exists:promotion_types,id'
        ]);

        $amount = PromotionType::where('id', $validated['promotion_id'])->value('price');

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'amount' => $amount,
            'description'=> "Оплата заказа",
        ]);

        if($transaction){
            $payment = $this->yukassaService->createPayment(
                $amount,
                "Оплата продвижения объявления",
                $transaction->id,
                $ad->id,
                $validated['promotion_id']
            );
            return redirect($payment->getConfirmation()->getConfirmationUrl());
        }
    }

    public function callback(Request $request)
    {
        $requestBody = $request->json()->all();

        $notification = ($requestBody['event'] === NotificationEventType::PAYMENT_SUCCEEDED)
            ? new NotificationSucceeded($requestBody)
            : new NotificationWaitingForCapture($requestBody);

        $payment = $notification->getObject();

        try {
            DB::transaction(function() use ($payment) {
                $transactionId = $payment->getMetadata()->transaction_id;
                $transaction = Transaction::findOrFail($transactionId);
                $transaction->status = PaymentStatusEnum::CONFIRMED;
                $transaction->save();

                $adId = $payment->getMetadata()->ad_id;
                $promotionId = $payment->getMetadata()->promotion_id;
                $ad = Ad::findOrFail($adId);
                $ad->promotions()->attach($promotionId);
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal error'], 500);
        }
    }
}
