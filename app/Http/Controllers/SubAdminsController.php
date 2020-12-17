<?php
namespace Crater\Http\Controllers;

use Crater\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Crater\Conversation;
use Crater\Group;
use Crater\Http\Requests;
use Crater\Notifications\CustomerAdded;
use Crater\User;
use Illuminate\Support\Facades\Hash;
use Crater\Currency;
use Crater\CompanySetting;
use Crater\Address;
use Illuminate\Support\Facades\DB;

class SubAdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;

        $subAdmins = User::subAdmin()
            ->applyFilters($request->only([
                'search',
                'contact_name',
                'display_name',
                'phone',
                'orderByField',
                'orderBy'
            ]))
            /*->whereCompany($request->header('company'))
            ->select('users.*',
                DB::raw('sum(invoices.due_amount) as due_amount')
            )
            ->groupBy('users.id')
            ->leftJoin('invoices', 'users.id', '=', 'invoices.user_id')*/
            ->paginate($limit);

        $siteData = [
            'subAdmins' => $subAdmins
        ];

        return response()->json($siteData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\CustomerRequest $request)
    {
        $verifyEmail = User::where('email', $request->email)->first();
        //dd($request->all());

        $customer = new User();
        $customer->name = $request->name;
        $customer->customer_type_id = $request->customer_type_id;
        $customer->company_type_id = $request->removelines;
        $customer->currency_id = $request->currency_id;
        $customer->email = $request->email;
        $customer->password = bcrypt($request->password);
        $customer->phone = $request->phone;
        $customer->company_name = $request->company_name;
        $customer->contact_name = $request->contact_name;
        $customer->website = $request->website;
        $customer->enable_portal = $request->enable_portal;
        $customer->role = 'subadmin';
        $customer->password = Hash::make($request->password);
        $customer->save();

        if ($request->addresses) {
            foreach ($request->addresses as $address) {
                $newAddress = new Address();
                $newAddress->name = $address["name"];
                $newAddress->address_street_1 = $address["address_street_1"];
                $newAddress->address_street_2 = $address["address_street_2"];
                $newAddress->city = $address["city"];
                $newAddress->state = $address["state"];
                $newAddress->country_id = $address["country_id"];
                $newAddress->zip = $address["zip"];
                $newAddress->phone = $address["phone"];
                $newAddress->type = $address["type"];
                $newAddress->user_id = $customer->id;
                $newAddress->save();
                $customer->addresses()->save($newAddress);
            }
        }

        /*save company info*/

        $company = $customer->company;

        if (!$company) {
            $company = new Company();
        }

        $company->name = $request->company_name;
        $company->unique_hash = str_random(60);
        $company->save();

        /*associate user with company info*/
        $customer->company()->associate($company);
        $customer->save();
        /*save company info*/

        /*save company settings*/
        CompanySetting::setSetting(
            'notification_email',
            $customer->email,
            $company->id
        );

        $sets = [
            'currency' => $request->currency_id,
            'time_zone' => 'Asia/Karachi',
            'language' => 'en',
            'carbon_date_format' => 'd M Y',
            'moment_date_format' => 'DD MMM YYYY',
            'fiscal_year' => '7-6'
        ];

        foreach ($sets as $key => $value) {
            CompanySetting::setSetting(
                $key,
                $value,
                $customer->company_id
            );
        }

        $invoices = [
            'invoice_auto_generate' => 'YES',
            'invoice_prefix' => 'INV'
        ];

        foreach ($invoices as $key => $value) {
            CompanySetting::setSetting(
                $key,
                $value,
                $customer->company_id
            );
        }

        $estimates = [
            'estimate_prefix' => 'EST',
            'estimate_auto_generate' => 'YES'
        ];

        foreach ($estimates as $key => $value) {
            CompanySetting::setSetting(
                $key,
                $value,
                $customer->company_id
            );
        }

        $payments = [
            'payment_prefix' => 'PAY',
            'payment_auto_generate' => 'YES'
        ];

        foreach ($payments as $key => $value) {
            CompanySetting::setSetting(
                $key,
                $value,
                $customer->company_id
            );
        }

        $colors = [
            'primary_text_color' => '#5851D8',
            'heading_text_color' => '#595959',
            'section_heading_text_color' => '#040405',
            'border_color' => '#EAF1FB',
            'body_text_color' => '#595959',
            'footer_text_color' => '#595959',
            'footer_total_color' => '#5851D8',
            'footer_bg_color' => '#F9FBFF',
            'date_text_color' => '#A5ACC1',
            'invoice_primary_color' => '#5851D8',
            'invoice_column_heading' => '#55547A',
            'invoice_field_label' => '#55547A',
            'invoice_field_value' => '#040405',
            'invoice_body_text' => '#040405',
            'invoice_description_text' => '#595959',
            'invoice_border_color' => '#EAF1FB'
        ];
        foreach ($colors as $key => $value) {
            CompanySetting::setSetting(
                $key,
                $value,
                $customer->company_id
            );
        }

        $notification = [
            'discount_per_item' => 'NO',
            'notify_invoice_viewed' => 'NO',
            'tax_per_item' => 'NO',
            'notify_estimate_viewed' => 'NO'
        ];

        foreach ($notification as $key => $value) {
            CompanySetting::setSetting(
                $key,
                $value,
                $customer->company_id
            );
        }
        /*save company settings*/

        $customer = User::with('billingAddress')->find($customer->id);

        return response()->json([
            'customer' => $customer,
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $customer = User::with([
            'billingAddress',
            'shippingAddress',
            'billingAddress.country',
            'shippingAddress.country',
        ])->find($id);

        return response()->json([
            'customer' => $customer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $customer = User::with('billingAddress')->findOrFail($id);
        $currency = $customer->currency;
        $currencies = Currency::all();
        // dd($customer);
        return response()->json([
            'subAdmin' => $customer,
            'currencies' => $currencies,
            'currency' => $currency
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Requests\CustomerRequest $request)
    {
        $customer = User::find($id);

        if ($request->email != null) {
            $verifyEmail = User::where('email', $request->email)->first();

            if ($verifyEmail) {
                if ($verifyEmail->id !== $customer->id) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Email already in use'
                    ]);
                }
            }
        }

        if ($request->has('password')) {
            $customer->password = Hash::make($request->password);
        }

        $customer->name = $request->name;
        $customer->customer_type_id = $request->customer_type_id;
        $customer->company_type_id = $request->removelines;
        $customer->currency_id = $request->currency_id;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->company_name = $request->company_name;
        $customer->contact_name = $request->contact_name;
        $customer->website = $request->website;
        $customer->enable_portal = $request->enable_portal;
        $customer->save();

        $customer->addresses()->delete();
        if ($request->addresses) {
            foreach ($request->addresses as $address) {
                $newAddress = $customer->addresses()->firstOrNew(['type' => $address["type"]]);
                $newAddress->name = $address["name"];
                $newAddress->address_street_1 = $address["address_street_1"];
                $newAddress->address_street_2 = $address["address_street_2"];
                $newAddress->city = $address["city"];
                $newAddress->state = $address["state"];
                $newAddress->country_id = $address["country_id"];
                $newAddress->zip = $address["zip"];
                $newAddress->phone = $address["phone"];
                $newAddress->type = $address["type"];
                $newAddress->user_id = $customer->id;
                $newAddress->save();
            }
        }

        $customer = User::with('billingAddress', 'shippingAddress')->find($customer->id);

        return response()->json([
            'customer' => $customer,
            'success' => true
        ]);
    }

    /**
     * Remove the specified Customer along side all his/her resources (ie. Estimates, Invoices, Payments and Addresses)
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        User::deleteCustomer($id);

        return response()->json([
            'success' => true
        ]);
    }


    /**
     * Remove a list of Customers along side all their resources (ie. Estimates, Invoices, Payments and Addresses)
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        foreach ($request->id as $id) {
            User::deleteCustomer($id);
        }

        return response()->json([
            'success' => true
        ]);
    }
}
