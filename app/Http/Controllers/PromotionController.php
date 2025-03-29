<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index() {
        $promotions = Promotion::all();
        return view('promotions', compact('promotions'));
    }

    public function store(Request $request) {
        $promo = new Promotion();
        $promo->code_name = $request->code_name;
        $promo->discount = $request->discount;
        $promo->expiration_date = $request->expiration_date;
        $promo->status = $request->status;
        $promo->save();
        return response()->json(['message' => 'Promotion added successfully!']);
    }

    public function update(Request $request, $id) {
        $promo = Promotion::findOrFail($id);
        $promo->code_name = $request->code_name;
        $promo->discount = $request->discount;
        $promo->expiration_date = $request->expiration_date;
        $promo->status = $request->status;
        $promo->save();
        return response()->json(['message' => 'Promotion updated successfully!']);
    }

    public function destroy($id) {
        Promotion::findOrFail($id)->delete();
        return response()->json(['message' => 'Promotion deleted successfully!']);
    }
    public function checkPromo(Request $request)
{
    $promo = Promotion::where('code_name', $request->code)->where('status', 'Active')->first();

    if ($promo) {
        return response()->json([
            'valid' => true,
            'discount' => $promo->discount
        ]);
    } else {
        return response()->json(['valid' => false]);
    }
}

}
