<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\FoodCategory;
use App\Models\Food;
use App\Models\FoodTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FoodOrderController extends Controller
{
    public function index(Request $request)
    {
        $categories = FoodCategory::all();
        $selectedCategory = $request->query('category_id');

        $foods = Food::when($selectedCategory, function ($query, $selectedCategory) {
            return $query->where('food_category_id', $selectedCategory);
        })->where('food_status', 'Available')->get();

        $cart = session()->get('cart', []);

        return view('homepage.foodorder', compact('categories', 'foods', 'selectedCategory', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $food = Food::findOrFail($request->food_id);
        $cart = session()->get('cart', []);

        $cart[$food->id] = [
            'id' => $food->id,
            'name' => $food->food_name,
            'price' => $food->food_price,
            'unit' => $food->food_unit,
            'quantity' => ($cart[$food->id]['quantity'] ?? 0) + $request->quantity,
            'picture' => $food->picture,
        ];

        session()->put('cart', $cart);

        return redirect()->route('homepage-foodOrder')->with('success', 'Food added to order!');
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
        ]);

        $cart = session()->get('cart', []);

        foreach ($request->quantities as $foodId => $quantity) {
            if ($quantity == 0) {
                unset($cart[$foodId]);
            } elseif (isset($cart[$foodId])) {
                $cart[$foodId]['quantity'] = $quantity;
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('homepage-foodOrder')->with('success', 'Order updated!');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('homepage-foodOrder')->with('error', 'No items in the order.');
        }
        return view('homepage.checkout', compact('cart'));
    }

    public function storeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('homepage-foodOrder')->with('error', 'No items in the order.');
        }

        $request->validate([
            'reservation_date' => 'required|date',
            'customer_name' => 'required_if:customer_type,Walk-in|string|max:50',
            'customer_number' => 'required_if:customer_type,Walk-in|string|max:20',
            'customer_email' => 'required_if:customer_type,Walk-in|email|max:50',
        ]);

        $transaction_number = 'POS-' . Str::random(10);

        foreach ($cart as $item) {
            $food = Food::findOrFail($item['id']);

            $transaction = new FoodTransaction();
            $transaction->transaction_number = $transaction_number;
            $transaction->food_id = $item['id'];
            $transaction->quantity = $item['quantity'];
            $transaction->total_amount = $food->food_price * $item['quantity'];
            $transaction->reservation_date = $request->reservation_date;
            $transaction->status = 'Pending'; // POS orders are typically confirmed immediately

            $transaction->customer_type = 'Walk-in';
            $transaction->customer_name = $request->customer_name;
            $transaction->customer_number = $request->customer_number;
            $transaction->customer_email = $request->customer_email;

            $transaction->save();
        }

        // return redirect()->route('homepage-foodOrder')->with('success', 'Order processed successfully! Transaction #: ' . $transaction_number);
        return response()->json([
            'transactionNumber' => $transaction_number // Replace with actual value
        ]);

        // session()->forget('cart');
    }
}
