use App\Http\Controllers\CafeLocationController;
use App\Http\Controllers\ShopStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;

// ... existing code ...

Route::prefix('cafe')->group(function () {
    Route::get('/current-location', [CafeLocationController::class, 'getCurrentLocation']);
    Route::get('/location-history', [CafeLocationController::class, 'getLocationHistory']);
    Route::post('/update-location', [CafeLocationController::class, 'updateLocation'])->middleware('auth:sanctum');
    Route::post('/set-current-location/{id}', [CafeLocationController::class, 'setCurrentLocation'])->middleware('auth:sanctum');
    Route::post('/toggle-status', [CafeLocationController::class, 'toggleShopStatus'])->middleware('auth:sanctum');
});

// Shop Status Routes (public endpoint for getting status)
Route::get('/shop/status', [ShopStatusController::class, 'getStatus']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/shop/toggle-status', [InventoryController::class, 'toggleShopStatus']);
    // ... other protected routes ...
}); 