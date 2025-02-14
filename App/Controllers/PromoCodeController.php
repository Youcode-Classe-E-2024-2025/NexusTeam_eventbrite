<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Validator;
use App\Models\PromoCode;
use App\Core\Views;
use App\Core\Session;

class PromoCodeController
{
    public function index(): void
    {
        $promoCodes = (new PromoCode())->getAll();
        Views::render('PromoCode/index', ['promoCodes' => $promoCodes]);
    }

    public function show(Request $request): void
    {
        $promoCode = new PromoCode();
        $promoCode->setId($request->get('id'));

        $promoCode = $promoCode->getById();
        if (!$promoCode) {
            Session::set('message', 'Promo code not found');
            Views::redirect('/organizer/PromoCode');
            return;
        }

        if ($request->getUri() === "/organizer/PromoCode/update/" . $request->get('id')) {
            Views::render('PromoCode/edit', ['promocode' => $promoCode]);
        }

        Views::render('PromoCode/show', ['promocode' => $promoCode]);
    }

    public function store(Request $request): void
    {
        Validator::make($request->all(), [
            'code' => 'required|alphanumeric|min:3|max:50|unique:promo_codes,code',
            'discount' => 'required|numeric|min:0|max:100',
            'expiration' => 'required|date',
            'event_id' => 'nullable|integer|exists:events,id'
        ]);

        if (!empty(Validator::errors())) {
            Session::set('message', Validator::errors()[0]);
            Views::redirect('/organizer/PromoCode');
            return;
        }

        $promoCode = new PromoCode();
        $promoCode->fill($request->all());

        if ($promoCode->create()) {
            Session::set('message', 'Promo code created successfully');
        } else {
            Session::set('message', 'Promo code not created, try again');
        }

        Views::redirect('/organizer/PromoCode');
    }

    public function update(Request $request): void
    {
        Validator::make($request->all(), [
            'code' => 'required|alphanumeric|min:3|max:50|unique:promo_codes,code,' . $request->get('id'),
            'discount' => 'required|numeric|min:0|max:100',
            'expiration' => 'required|date',
            'event_id' => 'nullable|integer|exists:events,id'
        ]);

        if (!empty(Validator::errors())) {
            Session::set('message', Validator::errors()[0]);
            Views::redirect('/organizer/PromoCode/update/' . $request->get('id'));
            return;
        }

        $promoCode = new PromoCode();
        $promoCode->fill($request->all());

        if ($promoCode->update()) {
            Session::set('message', 'Promo code updated successfully');
        } else {
            Session::set('message', 'Promo code not updated, try again');
        }

        Views::redirect('/organizer/PromoCode');
    }

    public function destroy(Request $request): void
    {
        $promoCode = new PromoCode();
        $promoCode->setId($request->get('id'));

        if ($promoCode->delete()) {
            Session::set('message', 'Promo code deleted successfully');
        } else {
            Session::set('message', 'Promo code not deleted, try again');
        }

        Views::redirect('/organizer/PromoCode');
    }
}