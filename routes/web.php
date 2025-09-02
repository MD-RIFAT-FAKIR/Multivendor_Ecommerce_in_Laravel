<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\SubcategoryController;
use App\Http\Controllers\Backend\ProductController;

use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Backend\VendorProductController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\BannerController;

use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\User\WishlistConrtoller;
use App\Http\Controllers\User\CompareController;
use App\Http\Controllers\User\CheckoutConroller;
use App\Http\Controllers\User\StripeController;
use App\Http\Controllers\User\CODController;
use App\Http\Controllers\User\AllUserController;
use App\Http\Controllers\User\ReviewController;

use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\ShippingAreaController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\VendorOrderController;
use App\Http\Controllers\Backend\ReturnController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\ActiveUsersController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\SiteSettingController;
use App\Http\Controllers\Backend\RoleController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('frontend.index');
// });
//index route
Route::get('/', [IndexController::class, 'Index']);

//user dashboard
Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard', [UserController::class, 'UserDashboard'])->name('dashboard');
    //user profile store
    Route::post('/user/profile/store' , [UserController::class, 'UserProfileStore'])->name('user.profile.store');
    //user profile logout
    Route::get('/user/profile/logout' , [UserController::class, 'UserProfileLogout'])->name('user.profile.logout');
    //user update password
    Route::post('/user/update/password' , [UserController::class, 'UserUpdatePassword'])->name('user.update.password');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//Admin dash_board
Route::middleware(['auth','role:admin'])->group(function() {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    //admin logout
    Route::get('/admin/logout', [AdminController::class, 'AdminDestroy'])->name('admin.logout');
    //admin profile
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    //admin profile save changes
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileSaveChange'])->name('admin.profile.store');
    //admin change password
    Route::get('admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    //admin update password
    Route::post('admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('admin.update.password');
});
//admin login 
Route::get('/admin/login', [AdminController::class,'AdminLogin'])->middleware(RedirectIfAuthenticated::class);

//Vendor dash_board
Route::middleware(['auth','role:vendor'])->group(function() {
    //vendor dashboard
    Route::get('/vendor/dashboard', [VendorController::class, 'VendorDashboard'])->name('vendor.dashboard');
    //vendor logout
    Route::get('/vendor/logout', [VendorController::class, 'VendorDestroy'])->name('vendor.logout');
    //vendor profile
    Route::get('/vendor/profile', [VendorController::class, 'VendorProfie'])->name('vendor.profile');
    //vendor profile store
    Route::post('vendor/profile/store', [VendorController::class, 'VendorProfileStore'])->name('vendor.profile.store');
    //vendor change password
    Route::get('vendor/change/password', [VendorController::class, 'VendorChangePassword'])->name('vendor.change.password');
    //vendor update password
    Route::post('vendor/update/password', [VendorController::class, 'VendorUpdatePassword'])->name('vendor.update.password');

    //vendor product all route
    Route::controller(VendorProductController::class)->group(function() {
        //vendor all product 
        Route::get('vendor/all/product', 'VendorAllProduct')->name('vendor.all.product');
        //vendor add product 
        Route::get('vendor/add/product', 'VendorAddProduct')->name('vendor.add.product');
        //vendor store product in database
        Route::post('vendor/store/product' , 'VendorStoreProduct')->name('vendor.store.product');
        //vendor edit product
        Route::get('vendor/edit/product/{id}' , 'VendorEditProduct')->name('vendor.edit.product');
        //vendor update product
        Route::post('vendor/update/product', 'VendorUpdateProduct')->name('vendor.update.product');
        //vendor update product thambnail
        Route::post('vendor/update/product/thambnail', 'VendorUpdateProductThambnail')->name('vendor.update.product.thambnail');
        //vendor update product multi images
        Route::post('vendor/update/product/multiimg', 'VendorUpdateProductMultiImage')->name('vendor.update.product.multiimg');
        //vendor delete product multi images
        Route::get('vendor/delete/product/multiimg/{id}', 'VendorDeleteProductMultiImage')->name('vendor.delete.product.multiimg');
        //vendor product active to inactive
        Route::get('vendor/product/inactive/{id}', 'VendorProductInactive')->name('vendor.product.inactive');
        //vendor product inactive to active
        Route::get('vendor/product/active/{id}', 'VendorProductActive')->name('vendor.product.active');
        //vendor product delete
        Route::get('vendor/delete/product/{id}', 'VendorDeleteProduct')->name('vendor.delete.product');



         // subcategory automatically load in admin add product page,
        // when category is selected 
        Route::get('/vendor/subcategory/ajax/{category_id}', 'VendorGetSubcategory');
    });
});
//vendor login
Route::get('/vendor/login', [VendorController::class, 'VendorLogin'])->name('vendor.login')->middleware(RedirectIfAuthenticated::class);
//become a vendor
Route::get('become/vendor', [VendorController::class, 'BecomeVendor'])->name('become.vendor');
//vendor register
Route::post('vendor/register', [VendorController::class, 'VendorRegister'])->name('vendor.register');


Route::middleware(['auth','role:admin'])->group(function() {
    //Backend all brand
    Route::controller(BrandController::class)->group(function() {
        //all brand 
        Route::get('all/brand', 'AllBrand')->name('all.brand');
        //brand add
        Route::get('add/brand', 'AddBrand')->name('add.brand');
        //save brand
        Route::post('store/brand', 'StoreBrand')->name('store.brand');
        // edit brand
        Route::get('edit/brand/{id}', 'EditBrand')->name('edit.brand');
        //update brand
        Route::post('update/brand', 'UpdateBrand')->name('update.brand');
        // delete brand
        Route::get('delete/brand/{id}', 'DeleteBrand')->name('delete.brand');
    });

});//End Backend all brand

//admin category
Route::middleware(['auth', 'role:admin'])->group(function() {
    Route::controller(CategoryController::class)->group(function() {
        //all catecory
        Route::get('all/category', 'AllCategory')->name('all.category');
        //add category
        Route::get('add/category', 'AddCategory')->name('add.category');
        //store category
        Route::post('store/category', 'StoreCategory')->name('store.category');
        //edit category
        Route::get('edit/category/{id}', 'EditCategory')->name('edit.category');
        //pudate category
        Route::post('update/category', 'UpdateCategory')->name('update.category');
        //delete category
        Route::get('delete/category/{id}', 'DeleteCategory')->name('delete.category');

    });
});//End admin category

//admin subcategory
Route::middleware(['auth', 'role:admin'])->group(function() {
    
    Route::controller(SubcategoryController::class)->group(function() {
        //all subcategories
        Route::get('all/subcategory', 'AllSubcategory')->name('all.subcategory');
        //add subcategories
        Route::get('add/subcategory' , 'AddSubcategory')->name('add.subcategory');
        //store subcategory
        Route::post('store/subcategory' , 'StoreSubcategory')->name('store.subcategory');
        //edit subcategory
        Route::get('edit/subcategory/{id}', 'EditSubcategory')->name('edit.subcategory');
        //update subcategory
        Route::post('update/subcategory', 'UpdateSubcategory')->name('update.subcategory');
        //delete subcategory
        Route::get('delete/subcategory/{id}', 'DeleteSubcategory')->name('delete.subcategory');
        // subcategory automatically load in admin add product page,
        // when category is selected 
        Route::get('/subcategory/ajax/{category_id}', 'GetSubcategory');

    });
});//End admin subcategory


//Vendor inactive and active all route
Route::controller(AdminController::class)->group(function() {
    //inactive vendor
    Route::get('inactive/vendor' , 'InactiveVendor')->name('inactive.vendor');
    //active vendor
    Route::get('active/vendor' , 'ActiveVendor')->name('active.vendor');
    //inactive vendor details
    Route::get('inactibe/vendor/details/{id}', 'InactiveVendorDetails')->name('inactibe.vendor.details');
    //inactive vendor approve
    Route::post('inactive/vendor/approve' , 'InactiveVendorApprove')->name('inactive.vendor.approve');
    //active vendor details page
    Route::get('active/vendor/details/{id}', 'ActiveVendorDetails')->name('active.vendor.details');
    //active vendor disapprove
    Route::post('active/vendor/disapprove' , 'ActiveVendorDisapprove')->name('active.vendor.disapprove');

});//End Vendor inactive and active all route

//Admin product all route
Route::middleware(['auth', 'role:admin'])->group(function() {

    Route::controller(ProductController::class)->group(function() {
        //all product
        Route::get('all/product', 'AllProduct')->name('all.product');
        //add product
        Route::get('add/product', 'AddProduct')->name('add.product');
        //store product
        Route::post('store/product', 'StoreProduct')->name('store.product');
        //edit product
        Route::get('edit/product/{id}', 'EditProduct')->name('edit.product');
        //update product
        Route::post('/update/product', 'UpdateProduct')->name('update.product');
        //update product main thambnail
        Route::post('update/product/thambnail', 'UpdateProductThambnail')->name('update.product.thambnail');
        //update product multi imgae
        Route::post('update/product/multiimg', 'UpdateProductMultiImg')->name('update.product.multiimg');
        //delete product multi image
        Route::get('delete/product/multiimg/{id}', 'DeleteProductMultiImg')->name('delete.product.multiimg');
        //product status active to inactive
        Route::get('product/inactive/{id}', 'ProductInactive')->name('product.inactive');
        //product status inactive to active
        Route::get('product/active/{id}', 'ProductActive')->name('product.active');
        //delete admin poduct
        Route::get('delete/product/{id}', 'DeleteProduct')->name('delete.product');

        //stock product
        Route::get('product/stock', 'ProductStock')->name('product.stock');
        //end stock product


    });// end Admin product all route

    //Slider all route
    Route::controller(SliderController::class)->group(function() {
        //all slider
        Route::get('all/slider', 'AllSlider')->name('all.slider');
        //add slider
        Route::get('add/slider', 'AddSlider')->name('add.slider');
        //store slider
        Route::post('store/slider', 'StoreSlider')->name('store.slider');
        //edit slider
        Route::get('edit/slider/{id}', 'EditSlider')->name('edit.slider');
        //update slider
        Route::post('update/slider', 'UpdateSlider')->name('update.slider');
        //delete category
        Route::get('delete/slider/{id}', 'DeleteSlider')->name('delete.slider');

    });//End Slider all route


    //banner all route
    Route::controller(BannerController::class)->group(function() {
        //all banner
        Route::get('all/banner', 'AllBanner')->name('all.banner');
        //add banner
        Route::get('add/banner', 'AddBanner')->name('add.banner');
        //store banner
        Route::post('store/banner', 'StoreBanner')->name('store.banner');
        //edit banner
        Route::get('edit/banner/{id}', 'EditBanner')->name('edit.banner');
        //update banner
        Route::post('update/banner', 'UpdateBanner')->name('update.banner');
        //delete banner
        Route::get('delete/banner/{id}', 'DeleteBanner')->name('delete.banner');

    });//End banner all route

});


//frontend product details all route
Route::get('/product/details/{id}/{slug}', [IndexController::class, 'ProductDetails']);

//frontend vendor details all route
Route::get('/vendor/details/{id}', [IndexController::class, 'VendorDetails'])->name('vendor.details');

//frontend all vendor list
Route::get('vendor/all', [IndexController::class, 'VendorAll'])->name('vendor.all');

//frontend categorywise product display
Route::get('product/category/{id}/{slug}', [IndexController::class, 'CatwiseProduct']);

//frontend subcategory wise product display
Route::get('product/subcategory/{id}/{slug}', [IndexController::class, 'SubCatwiseProduct']);

//product quick view modal 
Route::get('/product/view/modal/{id}' , [IndexController::class, 'productViewAjax']);

//cart data store uinsg ajax
Route::post('/cart/data/store/{id}', [CartController::class, 'addToCart']);

//product add to mini cart
Route::get('/product/mini/cart' , [CartController::class, 'AddMiniCart']);

//remove product mini cart 
Route::get('/minicart/product/remove/{rowId}' , [CartController::class, 'RemoveMiniCart']);

//add to cart from details page
Route::post('/dcart/data/store/{id}', [CartController::class, 'AddToCartDetails']);

//add to wishlist
Route::post('/add-to-wishlist/{product_id}', [WishlistConrtoller::class, 'addToWishlist']);
/// Add to Compare 
Route::post('/add-to-compare/{product_id}', [CompareController::class, 'AddToCompare']);
/// Frontend Coupon Option
Route::post('/coupon-apply', [CartController::class, 'CouponApply']);
//coupon calculation
Route::get('/coupon-calculation', [CartController::class, 'CouponCalculation']);
//coupon remove
Route::get('/coupon-remove', [CartController::class, 'CouponRemove']);
// Checkout Page Route 
Route::get('/checkout', [CartController::class, 'CheckoutCreate'])->name('checkout');


//cart all route
Route::controller(CartController::class)->group(function() {
    Route::get('/mycart', 'MyCart')->name('mycart');
    Route::get('/get-cart-product', 'GetMyCart');
    Route::get('/cart-remove/{id}', 'CartRemove');
    //decrement product quantiry
    Route::get('/decrement-cart/{rowId}', 'DecrementCart');
    //increment product quantiry
    Route::get('/increment-cart/{rowId}', 'IncrementCart');
});










    //wishlist all route
    Route::middleware(['auth','role:user'])->group(function() {
        Route::controller(WishlistConrtoller::class)->group(function() {
            Route::get('/wishlist', 'AllWishlist')->name('wishlist');
            //get product
            Route::get('/get-wishlist-product', 'GetWishlistProduct');
            //remove product
            Route::get('/wishlist-remove/{id}', 'WishlistProductRemove');
        });
    });

    //Compare all route
    Route::middleware(['auth','role:user'])->group(function() {
        Route::controller(CompareController::class)->group(function() {
            Route::get('/compare', 'AllCompare')->name('compare');
            //get product
            Route::get('/get-compare-product', 'GetCompareProduct');
            //remove compare product
            Route::get('/compare-remove/{id}', 'CompareProductRemove');
        });
    });



//coupn system all route
Route::middleware(['auth', 'role:admin'])->group(function() {
    
    Route::controller(CouponController::class)->group(function() {
        //all coupon
        Route::get('all/coupon', 'AllCoupon')->name('all.coupon');
        //add coupon
        Route::get('add/coupon' , 'AddCoupon')->name('add.coupon');
        //store coupon
        Route::post('store/coupon' , 'StoreCoupon')->name('store.coupon');
        //edit coupon
        Route::get('edit/coupon/{id}', 'EditCoupon')->name('edit.coupon');
        //update coupon
        Route::post('update/coupon', 'UpdateCoupon')->name('update.coupon');
        //delete subcategory
        Route::get('delete/coupon/{id}', 'DeleteCoupon')->name('delete.coupon');
    });
});//End acoupn system


//Shipping Area all route
Route::middleware(['auth', 'role:admin'])->group(function() {
    //division 
    Route::controller(ShippingAreaController::class)->group(function() {
        //all division
        Route::get('all/division', 'AllDivision')->name('all.division');
        //add division
        Route::get('add/division' , 'AddDivision')->name('add.division');
        //store division
        Route::post('store/division' , 'StoreDivision')->name('store.division');
        //edit division
        Route::get('edit/division/{id}', 'EditDivision')->name('edit.division');
        //update division
        Route::post('update/division', 'UpdateDivision')->name('update.division');
        //delete division
        Route::get('delete/division/{id}', 'DeleteDivision')->name('delete.division');
    });//end division


    //district 
    Route::controller(ShippingAreaController::class)->group(function() {
        //all district
        Route::get('all/district', 'AllDistrict')->name('all.district');
        //add district
        Route::get('add/district' , 'AddDistrict')->name('add.district');
        //store district
        Route::post('store/district' , 'StoreDistrict')->name('store.district');
        //edit district
        Route::get('edit/district/{id}', 'EditDistrict')->name('edit.district');
        //update division
        Route::post('update/district', 'UpdateDistrict')->name('update.district');
        //delete division
        Route::get('delete/district/{id}', 'DeleteDistrict')->name('delete.district');
    });//end district


    //state
    Route::controller(ShippingAreaController::class)->group(function() {
        //all state
        Route::get('all/state', 'AllState')->name('all.state');
        //add state
        Route::get('add/state' , 'AddState')->name('add.state');
        //store state
        Route::post('/store/state' , 'StoreState')->name('store.state');
        //edit state
        Route::get('/edit/state/{id}' , 'EditState')->name('edit.state');
        //update state
        Route::post('/update/state' , 'UpdateState')->name('update.state');
        //delete state
        Route::get('/delete/state/{id}' , 'DeleteState')->name('delete.state');
        //get district using ajax
        Route::get('/district/ajax/{division_id}' , 'GetDistrict');
    });//end district


});//Shipping Area system

Route::controller(CheckoutConroller::class)->group(function(){
    //selected division related all district will get
    Route::get('/district-get/ajax/{division_id}' , 'DistrictGetAjax');
    //selected district related all State will get
    Route::get('/state-get/ajax/{district_id}' , 'StateGetAjax');
    Route::post('/checkout/store' , 'CheckoutStore')->name('checkout.store');
  

}); 

 // Stripe payment All Route 
Route::controller(StripeController::class)->group(function(){
    Route::post('/stripe/order' , 'StripeOrder')->name('stripe.order');
}); 

 // cash payment All Route 
Route::controller(CODController::class)->group(function(){
    Route::post('/cash/order' , 'CashOrder')->name('cash.order');
}); 

//admin order manage all route
    Route::middleware(['auth','role:admin'])->group(function() {
        Route::controller(OrderController::class)->group(function() {
            //admin dashboards pending orders 
            Route::get('/pending/orders', 'PendingOrder')->name('pending.orders');
            //admin dashboards confirmed orders 
            Route::get('/confirmed/orders', 'ConfirmedOrder')->name('confirmed.orders');
            //admin dashboards processing orders 
            Route::get('/processing/orders', 'ProcessingOrder')->name('processing.orders');
            //admin dashboards delivered orders 
            Route::get('/delivered/orders', 'DeliveredOrder')->name('delivered.orders');
            //admin order details page
            Route::get('/admin/order/details/{order_id}', 'OrderDetails')->name('admin.order.details');
            //order status pending to confirm
            Route::get('/pending/confirm/{order_id}', 'PendingToConfirm')->name('pending-confirm');
            //order status confirm to processing
            Route::get('/confirm/processing/{order_id}', 'ConfirmToProcessing')->name('confirm-processing');
            //order status processing to delivered
            Route::get('/processing/delivered/{order_id}', 'ProcessingToDelivered')->name('processing-delivered');
            //admin invoice download
            Route::get('/admin/invoice/download/{order_id}', 'AdminInvoiceDownload')->name('admin.invoice.download');
        });
    });
//admin order manage all route

//vendor order manage all route
Route::middleware(['auth','role:vendor'])->group(function() {
    //vendor order all route
    Route::controller(VendorOrderController::class)->group(function() {
        Route::get('/vendor/orders', 'VendorOrder')->name('vendor.orders');
        //vendor return orders
        Route::get('/vendor/return/orders', 'VendorReturnOrder')->name('vendor.return.orders');
        //vendor return orders
        Route::get('/vendor/complete/return/orders', 'VendorCompleteReturnOrder')->name('vendor.complete.return.orders');
        //vendor order details
        Route::get('/vendor/order/details/{order_id}', 'VendorOrderDetails')->name('vendor.order.details');
    });
    //vendor order all route

    //product review all route
    Route::controller(ReviewController::class)->group(function() {
        Route::get('vendor/all/review', 'VendorAllReview')->name('vendor.all.review');
    });
    //end product review all route



});
//vendor order manage all route

//user dashboard all route
    Route::middleware(['auth','role:user'])->group(function() {
        Route::controller(AllUserController::class)->group(function() {
            //user acount details page
            Route::get('/user/acount/page', 'UserAcount')->name('user.acount.page');
            //user change password page
            Route::get('/user/change/password', 'UserChangePassword')->name('user.change.password');
            //user order page
            Route::get('/user/order/page', 'UserOrderPage')->name('user.order.page');
            //view user order details
            Route::get('/user/order_details/{order_id}', 'UserOrderDetails');
            //user invoice download
            Route::get('user/order_invoice/{order_id}', 'UserOrderInvoice');
            //user return order
            Route::post('user/return/order/{order_id}', 'ReturnOrder')->name('return.order');
            //user return order view page
            Route::get('user/return/order', 'ReturnOrderPage')->name('user.return.order');
            //user order track page
            Route::get('user/track/order', 'TrackOrder')->name('user.track.order');
            //user track order by invioce
            Route::post('track/by/invoice', 'TrackOrderInvoice')->name('track.by.invioce.no');
        });
    });
//end user dashboard all route


//order return request manage admin
Route::middleware(['auth', 'role:admin'])->group(function() {
    //division 
    Route::controller(ReturnController::class)->group(function() {
        //all retun request 
        Route::get('return/request', 'ReturnRequest')->name('return.request');
        //retun request approved
        Route::get('return/request/approved/{order_id}', 'ReturnRequestApproved')->name('return.request.approved');
        //complete retun request view page
        Route::get('complete/return/request', 'CompleteReturnRequest')->name('complete.return.request');
        
    });//end order return request manage admin
});

//admin middleware
Route::middleware(['auth', 'role:admin'])->group(function() {
    
    //ecommerce report all route
    Route::controller(ReportController::class)->group(function() {
        //view report
        Route::get('report.view', 'ReportView')->name('report.view');
        //report search by date
        Route::post('search/by/date', 'ReporSearchByDate')->name('search.by.date');
        //report search by month
        Route::post('search/by/month', 'ReporSearchByMonth')->name('search.by.month');
        //report search by year
        Route::post('search/by/year', 'ReporSearchByYear')->name('search.by.year');

    });//End ecommerce report all route
    

    //all users
    Route::controller(ActiveUsersController::class)->group(function() {
        //all user 
        Route::get('all/users', 'AllUsers')->name('all.users');
        //all user 
        Route::get('all/vendors', 'AllVendors')->name('all.vendors');

    });    
    //end all user

    //all blog category route
    Route::controller(BlogController::class)->group(function() {
        //add blog 
        Route::get('all/blog/category', 'BlogCategory')->name('all.blog.category');
        //add blog 
        Route::get('add/blog/category', 'AddBlogCategory')->name('add.blog.category');
        //store blog 
        Route::post('store/blog/category', 'StoreBlogCategory')->name('store.blog.category');
        //edit blog 
        Route::get('edit/blog/category/{id}', 'EditBlogCategory')->name('edit.blog.category');
        //update blog 
        Route::post('update/blog/category/{id}', 'UpdateBlogCategory')->name('update.blog.category');
        //delete blog 
        Route::get('delete/blog/category/{id}', 'DeleteBlogCategory')->name('delete.blog.category');
    });    
    //end all blog category route

     //all blog post route
    Route::controller(BlogController::class)->group(function() {
        //add blog 
        Route::get('all/blog/post', 'BlogPost')->name('all.blog.post');
        //add blog post
        Route::get('add/blog/post', 'AddBlogPost')->name('add.blog.post');
        //store blog post
        Route::post('store/blog/post', 'StoreBlogPost')->name('store.blog.post');
        //edit blog post
        Route::get('edit/blog/post/{id}', 'EditBlogPost')->name('edit.blog.post');
        //update blog post
        Route::post('update/blog/post/{id}', 'UpdateBlogPost')->name('update.blog.post');
        //delete blog post
        Route::get('delete/blog/post/{id}', 'DeleteBlogPost')->name('delete.blog.post');
    });    
    //end all blog posts route

    //admin product review all route
    Route::controller(ReviewController::class)->group(function() {
        //admin pending review page
        Route::get('pending/review', 'PendingReview')->name('pending.review');
        //admin approve review 
        Route::get('approve/review/{id}', 'ApproveReview')->name('approve.review');
        //admin published review page
        Route::get('published/review', 'PublishedReview')->name('published.review');
        //admin delete review page
        Route::get('delete/review/{id}', 'DeleteReview')->name('delete.review');
    });
    //end admin product review all route

    //admin site settings all route
    Route::controller(SiteSettingController::class)->group(function() {
        //admin site settings page
        Route::get('site/settings', 'SiteSettings')->name('site.settings');
        //update site settings 
        Route::post('update/site/settings', 'UpdateSiteSettings')->name('site.setting.update');


        ////////seo settins/////////
        //admin seo settings page
        Route::get('seo/settings', 'SeoSettings')->name('seo.settings');
         //update site settings 
        Route::post('update/seo/settings', 'UpdateSeoSettings')->name('seo.setting.update');
        ///////end seo settins//////
    });
    //end admin site settings all route


    //admin permission all route
    Route::controller(RoleController::class)->group(function() {
        //all permission
        Route::get('all/permission', 'AllPermission')->name('all.permission');
        //add permission
        Route::get('add/permission', 'AddPermission')->name('add.permission');
        //store permission
        Route::post('store/permission', 'StorePermission')->name('store.premission');
        //edit permission
        Route::get('edit/permission/{id}', 'EditPermission')->name('edit.permission');
        //update permission
        Route::post('update/permission', 'UpdatePermission')->name('update.permission');
        //delete permission
        Route::get('delete/permission/{id}', 'DeletePermission')->name('delete.permission');
        
    });//end admin permission all route

    //admin roles all route
    Route::controller(RoleController::class)->group(function() {
        //all roles
        Route::get('all/roles', 'AllRoles')->name('all.roles');
        //add roles
        Route::get('add/role', 'AddRole')->name('add.role');
        //store roles
        Route::post('store/role', 'StoreRole')->name('store.role');
        //edit roles
        Route::get('edit/role/{id}', 'EditRole')->name('edit.role');
        //update roles
        Route::post('update/role', 'UpdateRole')->name('update.role');
        //delete roles
        Route::get('delete/role/{id}', 'DeleteRole')->name('delete.role');


        ////////role in permission/////////
        //role in permission
        Route::get('role/in/permission', 'RolesInPermission')->name('role.in.permission');
        //store role permission
        Route::post('store/role/permission', 'StoreRolesPermission')->name('store.role.permission');
        //all role permission
        Route::get('all/role/permission', 'AllRolePermission')->name('all.role.permission');
        //admin edit role
        Route::get('admin/edit/role/{id}', 'EditRolePermission')->name('admin.edit.role');
        ///////end role in permission//////
        
    });//end admin roles all route

});
//end admin middleware


//forntend blog post all route
    Route::controller(BlogController::class)->group(function() {
        //home blog 
        Route::get('/blog', 'HomeBlogPost')->name('home.blog');
        //post details page
        Route::get('post/details/{id}/{post_slug}', 'BlogDetails');
        //post category 
        Route::get('post/category/{id}/{post_slug}', 'BlogCategoryPost');
    });    
//end forntend blog post all route

//product review all route
Route::controller(ReviewController::class)->group(function() {
    //store review to database
    Route::post('store/review', 'StoreReview')->name('store.review');
});
//end product review all route

//search product item all route
Route::controller(IndexController::class)->group(function() {
    //search item
    Route::post('/search', 'SearchProduct')->name('search.product');
    //search recomnend using ajax 
    Route::post('/search-product', 'SearchRecomnend');
});
//end search product item all route
