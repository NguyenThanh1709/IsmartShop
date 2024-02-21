<?php

use App\Http\Controllers\AdminBannerController;
use App\Http\Controllers\AdminDashBoardController;
use App\Http\Controllers\AdminEmailRegisterController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ClientBlogController;
use App\Http\Controllers\ClientCartController;
use App\Http\Controllers\ClientCheckoutController;
use App\Http\Controllers\ClientHomeController;
use App\Http\Controllers\ClientPageController;
use App\Http\Controllers\ClientProductController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return redirect('login');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\AdminHomeController::class, 'index'])->name('home');
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
Route::group(
    ['prefix' => 'admin', 'middleware' => ['auth']],
    function () {
        //Dashboar
        Route::get('', [AdminDashBoardController::class, 'show']);
        Route::get('/dashboard', [AdminDashBoardController::class, 'show'])->name('dashboard');
        
        //Order
        Route::post('/order', [AdminOrderController::class, 'order'])->name('order');
        Route::get('/order/list', [AdminOrderController::class, 'show'])->name('order.index');
        Route::get('/order/add', [AdminOrderController::class, 'addOrder'])->name('order.add');
        Route::get('/order/delete/{id}', [AdminOrderController::class, 'deleteOrder'])->name('order.delete');
        Route::post('/order/detail', [AdminOrderController::class, 'detailOrder'])->name('order.detail');
        Route::post('/order/update/', [AdminOrderController::class, 'updateOrder'])->name('order.update');
        Route::post('/order/actionOrder', [AdminOrderController::class, 'actionOrder'])->name('order.actions');
        Route::post('/order/checkInfo', [AdminOrderController::class, 'checkInfo'])->name('order.checkInfo');
        Route::post('/order/search', [AdminOrderController::class, 'search'])->name('order.searchAjax');
        Route::post('/order/selectConfig', [AdminOrderController::class, 'selectConfig'])->name('order.selectConfig');
        Route::post('/order/addCart', [AdminOrderController::class, 'storeCart'])->name('order.addCart');
        Route::post('/order/delete', [AdminOrderController::class, 'deleteCart'])->name('order.deleteCart');
        Route::get('/order/revenue', [AdminOrderController::class, 'listRevenue'])->name('order.revenue');
        Route::get('/order/revenue-on-date', [AdminOrderController::class, 'detailProductSaleOneDate'])->name('order.detailProductSaleOneDate');
        Route::get('/order/getPrice', [AdminOrderController::class, 'getPrice'])->name('order.getPrice');
        Route::get('/order/export', [AdminOrderController::class, 'excelExport'])->name('order.export');
        //Product
        Route::get('/product/add', [AdminProductController::class, 'addProduct'])->name('product.add');
        Route::get('/product/edit/{id}', [AdminProductController::class, 'editProduct'])->name('product.edit');
        Route::get('/product/list', [AdminProductController::class, 'listProduct'])->name('index.product');
        Route::get('/product/delete/{id}', [AdminProductController::class, 'deleteProduct'])->name('product.delete');
        Route::post('/product/storeProduct', [AdminProductController::class, 'storeProduct'])->name('store.product');
        Route::get('/product/actionproduct', [AdminProductController::class, 'actionProduct'])->name('action.product');
        Route::post ('/product/updateProduct/{id}', [AdminProductController::class, 'updateProduct'])->name('update.product');
        //--ProductCats--//
        Route::get('/product/cat/list', [AdminProductController::class, 'listCat'])->name('index.cat.product');
        Route::get('/product/cat/delete/{id}', [AdminProductController::class, 'deleteCat'])->name('cat.delete.product');
        Route::post('/product/cat/edit/{id}', [AdminProductController::class, 'editCat'])->name('cat.edit.product');
        Route::post('/product/cat/update/{id}', [AdminProductController::class, 'updateCat'])->name('cat.update.product');
        Route::post('/product/cat/storeCat', [AdminProductController::class, 'storeCat'])->name('cat.store.product');
        //--ProductColor--//
        Route::get('/product/color/list', [AdminProductController::class, 'listColor'])->name('index.color.product');
        Route::get('/product/color/delete/{id}', [AdminProductController::class, 'deleteColor'])->name('color.delete.product');
        Route::post('/product/color/edit/{id}', [AdminProductController::class, 'editColor'])->name('color.edit.product');
        Route::post('/product/color/update/{id}', [AdminProductController::class, 'updateColor'])->name('color.update.product');
        Route::post('/product/color/storeColor', [AdminProductController::class, 'storeColor'])->name('color.store.product');
        //--ProductBrand--//
        Route::get('/product/brand/list', [AdminProductController::class, 'listBrand'])->name('index.brand.product');
        Route::get('/product/brand/delete/{id}', [AdminProductController::class, 'deleteBrand'])->name('brand.delete.product');
        Route::post('/product/brand/edit/{id}', [AdminProductController::class, 'editBrand'])->name('brand.edit.product');
        Route::post('/product/brand/update/{id}', [AdminProductController::class, 'updateBrand'])->name('brand.update.product');
        Route::post('/product/brand/storeBrand', [AdminProductController::class, 'storeBrand'])->name('brand.store.product');
        //--ProductConfig--//
        Route::get('/product/config/list', [AdminProductController::class, 'listConfig'])->name('index.config.product');
        Route::get('/product/config/delete/{id}', [AdminProductController::class, 'deleteConfig'])->name('config.delete.product');
        Route::post('/product/config/edit/{id}', [AdminProductController::class, 'editConfig'])->name('config.edit.product');
        Route::post('/product/config/update/{id}', [AdminProductController::class, 'updateConfig'])->name('config.update.product');
        Route::post('/product/config/storeConfig', [AdminProductController::class, 'storeConfig'])->name('config.store.product');
        //Slider
        Route::get('/banner/list', [AdminBannerController::class, 'listBanner'])->name('index.banner');
        Route::get('/banner/actionbanner', [AdminBannerController::class, 'actionBanner'])->name('action.banner');
        Route::get('/banner/add', [AdminBannerController::class, 'addBanner'])->name('add.banner');
        Route::get('/banner/edit/{id}', [AdminBannerController::class, 'editBanner'])->name('edit.banner');
        Route::get('/banner/delete/{id}', [AdminBannerController::class, 'deleteBanner'])->name('delete.banner');
        Route::post('/banner/update/{id}', [AdminBannerController::class, 'updateBanner'])->name('update.banner');
        Route::post('/banner/store', [AdminBannerController::class, 'store'])->name('banner.store');
        //Slider
        Route::get('/slider/list', [AdminSliderController::class, 'listSilder'])->name('index.slider');
        Route::get('/slider/actionSlider', [AdminSliderController::class, 'actionSlider'])->name('action.slider');
        Route::get('/slider/add', [AdminSliderController::class, 'addSlider'])->name('add.slider');
        Route::get('/slider/edit/{id}', [AdminSliderController::class, 'editSlider'])->name('edit.slider');
        Route::get('/slider/delete/{id}', [AdminSliderController::class, 'deleteSlider'])->name('delete.slider');
        Route::post('/slider/update/{id}', [AdminSliderController::class, 'updateSlider'])->name('update.slider');
        Route::post('/slider/store', [AdminSliderController::class, 'store'])->name('slider.store');
        //Page
        Route::get('/page/list', [AdminPageController::class, 'listPage'])->name('index.page');
        Route::get('/page/add', [AdminPageController::class, 'addPage'])->name('add.page');
        Route::get('/page/edit/{id}', [AdminPageController::class, 'editPage'])->name('edit.page');
        Route::get('/page/delete/{id}', [AdminPageController::class, 'deletePage'])->name('delete.page');
        Route::post('/page/update/{id}', [AdminPageController::class, 'updatePage'])->name('update.page');
        Route::post('/page/actionPage', [AdminPageController::class, 'actionPage'])->name('action.page');
        Route::post('/page/store', [AdminPageController::class, 'store'])->name('page.store');
        //Post//
        //--PostCat
        Route::get('/post/cat/list', [AdminPostController::class, 'listCat'])->name('cat.list');
        Route::get('/post/cat/delete/{id}', [AdminPostController::class, 'deleteCat'])->name('delete.post.cat');
        Route::post('/post/cat/add', [AdminPostController::class, 'addPostCat']);
        Route::post('/post/cat/edit/{id}', [AdminPostController::class, 'editCat'])->name('edit.post.cat');
        Route::post('/post/cat/update/{id}', [AdminPostController::class, 'updateCat'])->name('update.post.cat');
        //--Post
        Route::get('/post/list', [AdminPostController::class, 'listPost'])->name('index.post');
        Route::get('/post/add', [AdminPostController::class, 'addPost'])->name('add.post');
        Route::get('/post/edit/{id}', [AdminPostController::class, 'editPost'])->name('edit.post');
        Route::get('/post/delete/{id}', [AdminPostController::class, 'deletePost'])->name('delete.post');
        Route::get('/post/edit/{id}', [AdminPostController::class, 'editPost'])->name('edit.post');
        Route::post('/post/update/{id}', [AdminPostController::class, 'updatePost'])->name('update.post');
        Route::post('/post/actionPost', [AdminPostController::class, 'actionPost'])->name('action.post');
        Route::post('/post/store', [AdminPostController::class, 'store']);
        //User
        Route::get('/user/list', [AdminUserController::class, 'list'])->name('user.index');
        Route::get('/user/add', [AdminUserController::class, 'add'])->name('user.add');
        Route::get('/user/delete/{id}', [AdminUserController::class, 'delete'])->name('delete_user');
        Route::get('/user/edit/{user}', [AdminUserController::class, 'edit'])->name('edit_user');
        Route::post('/user/change_password/{id}', [AdminUserController::class, 'change_password'])->name('change_password_user');
        Route::post('/user/update/{user}', [AdminUserController::class, 'update'])->name('update_user');
        Route::post('/user/action', [AdminUserController::class, 'action'])->name('user.action');
        Route::post('/user/store', [AdminUserController::class, 'store']);
        // MODULE PERMISSION
        Route::get('/permission/add', [AdminPermissionController::class, 'add'])->name('permission.add');
        Route::post('/permission/store', [AdminPermissionController::class, 'store'])->name('permission.store');
        Route::post('/permission/edit/{id}', [AdminPermissionController::class, 'edit'])->name('permission.edit');
        Route::post('/permission/update', [AdminPermissionController::class, 'update'])->name('permission.update');
        Route::get('/permission/delete/{id}', [AdminPermissionController::class, 'delete'])->name('permission.delete');
        // MODULE ROLE
        Route::get('/role/list', [AdminRoleController::class, 'show'])->name('role.list');
        Route::get('/role/add', [AdminRoleController::class, 'add'])->name('role.add');
        Route::get('/role/delete/{id}', [AdminRoleController::class, 'delete'])->name('role.delete');
        Route::post('/role/store', [AdminRoleController::class, 'store'])->name('role.store');
        Route::post('/role/deleteMultiple', [AdminRoleController::class, 'deleteMultiple'])->name('role.deleteMultiple');
        Route::get('/role/edit/{role}', [AdminRoleController::class, 'edit'])->name('role.edit');
        Route::post('/role/update/{role}', [AdminRoleController::class, 'update'])->name('role.update');
        // MODULE EMAIL REGISTER
        Route::get('/email-register/list', [AdminEmailRegisterController::class, 'index'])->name('customer.email.list');
        Route::get('/email-register/sendEmail', [AdminEmailRegisterController::class, 'sendEmail'])->name('customer.email.send');
        Route::get('/email-register/delete/{id}', [AdminEmailRegisterController::class, 'delete'])->name('customer.email.delete');
    }
);

// MODULE HOME
Route::get('/', [ClientHomeController::class, 'show'])->name('home.index');
Route::post('/register', [ClientHomeController::class, 'register'])->name('home.register');

// MODULE PRODUCT
Route::get('/san-pham', [ClientProductController::class, 'show'])->name('product.list');
Route::get('/san-pham/sap-xep', [ClientProductController::class, 'arrangeAjax'])->name('product.arrange.ajax');
Route::get('/san-pham/{slug}.html', [ClientProductController::class, 'detail'])->name('product.detail');
Route::get('/san-pham/{slug}', [ClientProductController::class, 'searchCat'])->name('product.searchCat');
Route::get('/get-data', [ClientProductController::class, 'getData'])->name('getData');
Route::get('/get-data-color', [ClientProductController::class, 'getDataColor'])->name('getDataColor');
Route::get('/get-data-price', [ClientProductController::class, 'getDataPrice'])->name('getDataPrice');
Route::get('/search', [ClientProductController::class, 'searchHeader'])->name('prodcut.search');
Route::post('/searchAjax', [ClientProductController::class, 'searchAjax'])->name('prodcut.searchAjax');

// MOUDULE CARD
Route::get('/order/addCart', [ClientCartController::class, 'addProductToCart'])->name('addCart');
Route::get('/gio-hang.html', [ClientCartController::class, 'listCart'])->name('cart');
Route::post('/cart/update', [ClientCartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/delete', [ClientCartController::class, 'deleteCart'])->name('cart.delete');

// MOUDULE BLOG
Route::get('/bai-viet', [ClientBlogController::class, 'showBlogs'])->name('blogs.list');
Route::get('/bai-viet/{slug}.html', [ClientBlogController::class, 'detailBlogs'])->name('blogs.detail');

// MODULE PAGE
Route::get('/gioi-thieu.html', [ClientPageController::class, 'showIntroduce'])->name('introduce.index');
Route::get('/lien-he.html', [ClientPageController::class, 'showContact'])->name('contact.index');

// MODULE CHECKOUT
Route::get('/thanh-toan.html', [ClientCheckoutController::class, 'index'])->name('checkout.index');
Route::post('/thanh-toan-thanh-cong.html', [ClientCheckoutController::class, 'order'])->name('checkout.order');
Route::post('/district', [ClientCheckoutController::class, 'getDistrict'])->name('getDistrict');
Route::post('/commune', [ClientCheckoutController::class, 'getCommune'])->name('getCommune');
Route::get('/order-success', [ClientCheckoutController::class, 'orderSuccess'])->name('orderSuccess');

// MODULE VNPAY
// CV
Route::get('/vothanhdevcv', function () {
    return view('vothanhdev.vothanhdev');
});


