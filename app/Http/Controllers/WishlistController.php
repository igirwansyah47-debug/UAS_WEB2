<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        $wishlists = Wishlist::with('property')->where('tenant_id', Auth::id())->latest()->get();

        return view('wishlist.index', [
            'title' => 'Kos Favorit (Wishlist)',
            'wishlists' => $wishlists,
        ]);
    }

    public function toggle(Request $request)
    {
        if (Auth::user()->role !== 'tenant') {
            return redirect()->back()->with('error', 'Hanya tenant yang dapat menyimpan ke wishlist.');
        }

        $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $wishlist = Wishlist::where('tenant_id', Auth::id())
            ->where('property_id', $request->property_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return redirect()->back()->with('success', 'Dihapus dari wishlist.');
        } else {
            Wishlist::create([
                'tenant_id' => Auth::id(),
                'property_id' => $request->property_id,
            ]);
            return redirect()->back()->with('success', 'Ditambahkan ke wishlist.');
        }
    }
}
