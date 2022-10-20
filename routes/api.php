    <?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('posts','Api\PostApiController');
Route::resource('companies','Api\CompanyApiController');
Route::get('/company_data', 'Api\CompanyApiController@company_data');
Route::resource('departments','Api\DepartmentApiController');
Route::get('/department_data', 'Api\DepartmentApiController@department_data');
Route::resource('comments','Api\CommentApiController');