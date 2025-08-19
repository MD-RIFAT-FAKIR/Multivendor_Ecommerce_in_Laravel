<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <title>Easy Online Shop</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <!-- meta for ajaxSetup -->
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:title" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('frontend/assets/imgs/theme/favicon.svg')}}" />
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('frontend/assets/css/plugins/animate.min.css')}}" />
    <link rel="stylesheet" href="{{asset('frontend/assets/css/main.css?v=5.3')}}" />
</head>

<body>
    <!-- Modal -->

    <!-- Quick view -->
        @include('frontend.body.quickview')
    <!--end quick view -->
    <!-- Header  -->
        @include('frontend.body.header')
    <!--End header-->

    <main class="main">
        @yield('main')
    </main>

    <!--footer start-->
        @include('frontend.body.footer')
    <!--footer end-->


    <!-- Preloader Start -->
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="text-center">
                    <img src="{{asset('frontend/assets/imgs/theme/loading.gif')}}" alt="" />
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor JS-->
    <script src="{{asset('frontend/assets/js/vendor/modernizr-3.6.0.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/vendor/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/vendor/jquery-migrate-3.3.0.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/vendor/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/slick.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/jquery.syotimer.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/waypoints.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/wow.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/magnific-popup.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/select2.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/counterup.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/jquery.countdown.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/images-loaded.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/isotope.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/scrollup.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/jquery.vticker-min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/jquery.theia.sticky.js')}}"></script>
    <script src="{{asset('frontend/assets/js/plugins/jquery.elevatezoom.js')}}"></script>
    <!-- Template  JS -->
    <script src="{{asset('frontend/assets/js/main.js?v=5.3')}}"></script>
    <script src="{{asset('frontend/assets/js/shop.js?v=5.3')}}"></script>
    <!-- cart sweet alart cdn -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ajax setup for product quickview modal -->
    <!-- Change "javascritp" to "javascript" -->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                // Also fix "crsf-token" to "csrf-token" here
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function productView(id) {
            $.ajax({
                type: 'GET',
                url: '/product/view/modal/' + id,
                dataType: 'json',
                success: function(data) {
                    $('#pname').text(data.product.product_name);
                    $('#pprice').text(data.product.selling_price);
                    $('#pbrand').text(data.product.brand.brand_name);
                    $('#pcategory').text(data.product.category.category_name);
                    $('#pcode').text(data.product.product_code);
                    $('#pimage').attr('src' , '/' + data.product.product_thambnail);
                    $('#product_id').val(id);
                    $('#qty').val(1);

                    //product price
                    if(data.product.discount_price == null) {
                        $('#pprice').text('');
                        $('#oldprice').text('');
                        $('#pprice').text(data.product.selling_price);

                    }else{
                        $('#pprice').text(data.product.discount_price);
                        $('#oldprice').text(data.product.selling_price);
                    }//end if

                    //product quantity
                    if(data.product.product_qty > 0) {
                        $('#available').text('');
                        $('#stockout').text('');
                        $('#available').text('Available');
                    }else{
                        $('#available').text('');
                        $('#stockout').text('');
                        $('#stockout').text('Stockout');
                    }//end if

                    //product color
                    $('select[name="color"]').empty();
                    $.each(data.color,function(key,value){
                        $('select[name="color"]').append('<option value="'+value+' ">'+value+'  </option')
                        if (data.color == "") {
                            $('#colorArea').hide();
                        }else{
                            $('#colorArea').show();
                        }
                    })//end color

                    //product size
                    $('select[name="size"]').empty();
                    $.each(data.size,function(key,value){
                        $('select[name="size"]').append('<option value="'+value+' ">'+value+'  </option')
                        if (data.size == "") {
                            $('#sizeArea').hide();
                        }else{
                            $('#sizeArea').show();
                        }
                    })//end size
                    
                }
            });
        }//end product view

        //start product add to cart
        function addToCart() {
            var product_name = $('#pname').text();
            var id = $('#product_id').val();
            var color = $('#color option:selected').text();
            var size = $('#size option:selected').text();
            var quantity = $('#qty').val();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data:{
                    product_name:product_name, color:color, size:size, quantity:quantity
                },
                url:"/cart/data/store/"+ id,
                success:function(data) {
                    miniCart();
                    $('#closeModal').click();

                    //sweet alart
                    const Toast = swal.mixin({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    })

                    if($.isEmptyObject(data.error)) {
                        Toast.fire({
                            type: 'success',
                            title: data.success
                        });
                    }else{
                        Toast.fire({
                            type: 'error',
                            title: data.error
                        })
                    }
                }
            });
        }//end product add to cart


        //start product add to cart from product details page
        function addToCartDetails() {
            var product_name = $('#dpname').text();
            var id = $('#dproduct_id').val();
            var color = $('#dcolor option:selected').text();
            var size = $('#dsize option:selected').text();
            var quantity = $('#dqty').val();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data:{
                    product_name:product_name, color:color, size:size, quantity:quantity
                },
                url:"/dcart/data/store/"+ id,
                success:function(data) {
                    miniCart();

                    //sweet alart
                    const Toast = swal.mixin({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    })

                    if($.isEmptyObject(data.error)) {
                        Toast.fire({
                            type: 'success',
                            title: data.success
                        });
                    }else{
                        Toast.fire({
                            type: 'error',
                            title: data.error
                        })
                    }
                }
            });
        }//end product add to cart from details page

    </script>

    <script type="text/javascript">
        function miniCart() {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/product/mini/cart',
                success: function(response) {
                    $('#cartQty').text(response.cartQty);
                    $('#cartSubTotal').text(response.cartTotal);

                    var miniCart = "";

                    $.each(response.carts, function(key, value) {
                        miniCart += `
                        <ul>
                            <li>
                                <div class="shopping-cart-img">
                                    <a href="shop-product-right.html"><img alt="Nest" src="/${value.options.image} " style="width:50px;height:50px;" /></a>
                                </div>
                                <div class="shopping-cart-title" style="margin: -73px 74px 14px; width" 146px;>
                                    <h4><a href="shop-product-right.html"> ${value.name} </a></h4>
                                    <h4><span>${value.qty} × </span>${value.price}</h4>
                                </div>
                                <div class="shopping-cart-delete" style="margin: -85px 1px 0px;">
                                    <a type="submit" id="${ value.rowId }" onclick="miniCartRemove(this.id)"><i class="fi-rs-cross-small"></i></a>
                                </div>
                            </li> 
                        </ul>
                        <hr><br>

                        `
                    });

                    $('#miniCart').html(miniCart);
                }
            });
        }
        miniCart();

        //mini cart remove
        function miniCartRemove(rowId) {
            $.ajax({
                type:'GET',
                url: '/minicart/product/remove/' + rowId,
                dataType: 'json',
                success: function(data) {
                    miniCart();
                    //sweet alart
                    const Toast = swal.mixin({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    })

                    if($.isEmptyObject(data.error)) {
                        Toast.fire({
                            type: 'success',
                            title: data.success
                        });
                    }else{
                        Toast.fire({
                            type: 'error',
                            title: data.error
                        })
                    }
                }
            });
        }
    </script>

    <!-- product add to wishlist -->
        <script type="text/javascript">

            function addToWishlist(product_id) {
                $.ajax({
                    type: 'POST',
                    url: '/add-to-wishlist/' + product_id,
                    dataType: 'json',
                    success: function(data) {
                        Wishlist()
                        //sweet alart
                        const Toast = swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000
                        })

                        if($.isEmptyObject(data.error)) {
                            Toast.fire({
                                type: 'success',
                                icon: "success",
                                title: data.success
                            });
                        }else{
                            Toast.fire({
                                type: 'error',
                                icon: "error",
                                title: data.error
                            })
                        }
                    }
                });
            }
        </script>
    <!-- end product add to wishlist -->

    <!-- load wishlist  data-->
        <script type="text/javascript">

            function Wishlist() {
                $.ajax({
                    type: 'GET',
                    url: '/get-wishlist-product/',
                    dataType: 'json',
                    success: function(response) {
                        $('#wishlistQty').text(response.wishlistQty);
                        var rows = "";

                        $.each(response.wishlist, function(key, value){
                            rows += `
                                <tr class="pt-30">
                                    <td class="custome-checkbox pl-30">
                                    </td>
                                    <td class="image product-thumbnail pt-40"><img src="/${value.product.product_thambnail}" alt="#" /></td>
                                    <td class="product-des product-name">
                                        <h6><a class="product-name mb-10" href="shop-product-right.html">${value.product.product_name}</a></h6>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 90%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (4.0)</span>
                                        </div>
                                    </td>
                                    <td class="price" data-title="Price">
                                    ${value.product.discount_price == null 
                                    ?`<h3 class="text-brand">$${value.product.selling_price}</h3>`

                                    :`<h3 class="text-brand">$${value.product.discount_price}</h3>`

                                    }
                                        
                                    </td>
                                    <td class="text-center detail-info" data-title="Stock">
                                    ${value.product.product_qty > 0 
                                    ?`<span class="stock-status in-stock mb-0"> In Stock </span>`

                                    :`<span class="stock-status out-stock mb-0">Stock Out</span>`

                                    }
                                        
                                    </td>
                                    <td class="action text-center" data-title="Remove">
                                        <a id="${value.id}" class="text-body" onclick="wishlistRemove(this.id)"><i class="fi-rs-trash"></i></a>
                                    </td>
                                </tr> 
                            `
                        });
                        $('#wishlist').html(rows);
                    }
                });
            }

            Wishlist();
            // <-- end load wishlist  data-->

            //wishlist remove

             function wishlistRemove(id) {
                $.ajax({
                    type: 'GET',
                    url: '/wishlist-remove/' + id,
                    dataType: 'json',
                    success: function(data) {
                        Wishlist() //update list
                        //sweet alart
                        const Toast = swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000
                        })

                        if($.isEmptyObject(data.error)) {
                            Toast.fire({
                                type: 'success',
                                icon: "success",
                                title: data.success
                            });
                        }else{
                            Toast.fire({
                                type: 'error',
                                icon: "error",
                                title: data.error
                            })
                        }
                    }
                });
            }

        //wishlist remove end
        </script>


        <!-- product add to wishlist -->
        <script type="text/javascript">

            function addToCompare(product_id) {
                $.ajax({
                    type: 'POST',
                    url: '/add-to-compare/' + product_id,
                    dataType: 'json',
                    success: function(data) {
                        //sweet alart
                        const Toast = swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000
                        })

                        if($.isEmptyObject(data.error)) {
                            Toast.fire({
                                type: 'success',
                                icon: "success",
                                title: data.success
                            });
                        }else{
                            Toast.fire({
                                type: 'error',
                                icon: "error",
                                title: data.error
                            })
                        }
                    }
                });
            }
        </script>
    <!-- end product add to wishlist -->

    <!-- load compare  data-->
    <script type="text/javascript">

        function compare() {
            $.ajax({
                type: 'GET',
                url: '/get-compare-product/',
                dataType: 'json',
                success: function(response) {
                    var rows = "";

                    $.each(response, function(key, value){
                        rows += `
                            <tr class="pr_image">
                                    <td class="text-muted font-sm fw-600 font-heading mw-200">Preview</td>
                                    <td class="row_img"><img src="${value.product.product_thambnail}" style="width:300px; height:300px;" alt="compare-img" /></td>
                                <tr class="pr_title">
                                    <td class="text-muted font-sm fw-600 font-heading">Name</td>
                                    <td class="product_name">
                                        <h6><a href="shop-product-full.html" class="text-heading">${value.product.product_name}</a></h6>
                                    </td>
                                </tr>
                                <tr class="pr_price">
                                    <td class="text-muted font-sm fw-600 font-heading">Price</td>
                                    <td class="product_price">
                                        ${value.product.discount_price == null 
                                    ?`<h4 class="text-brand">$${value.product.selling_price}</h4>`

                                    :`<h4 class="text-brand">$${value.product.discount_price}</h4>`
                                    }
                                    </td>
                                </tr>
                                <tr class="description">
                                    <td class="text-muted font-sm fw-600 font-heading">Description</td>
                                    <td class="row_text font-xs">
                                        <p class="font-sm text-muted">${value.product.short_descp}</p>
                                    </td>
                                </tr>
                                <tr class="pr_stock">
                                    <td class="text-muted font-sm fw-600 font-heading">Stock status</td>
                                    <td class="product_stock">
                                    ${value.product.product_qty > 0 
                                    ?`<span class="stock-status in-stock mb-0"> In Stock </span>`

                                    :`<span class="stock-status out-stock mb-0">Stock Out</span>`

                                    }
                                    </td>
                                </tr>
                                <tr class="pr_remove text-muted">
                                    <td class="text-muted font-md fw-600"></td>
                                    <td class="row_remove">
                                        <a type="submit" id=${value.id} class="text-muted" onclick="compareRemove(this.id)"><i class="fi-rs-trash mr-5"></i><span>Remove</span> </a>
                                    </td>
                                </tr>
                        `
                    });
                    $('#compare').html(rows);
                }
            });
        }

        compare();
        //end load campare data

        //comparelist remove

             function compareRemove(id) {
                $.ajax({
                    type: 'GET',
                    url: '/compare-remove/' + id,
                    dataType: 'json',
                    success: function(data) {
                        compare() //update list
                        //sweet alart
                        const Toast = swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000
                        })

                        if($.isEmptyObject(data.error)) {
                            Toast.fire({
                                type: 'success',
                                icon: "success",
                                title: data.success
                            });
                        }else{
                            Toast.fire({
                                type: 'error',
                                icon: "error",
                                title: data.error
                            })
                        }
                    }
                });
            }

        //comparelist remove end

        </script>
        

    <script type="text/javascript">
        function cart() {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/get-cart-product',
                success: function(response) {

                    var rows = "";

                    $.each(response.carts, function(key, value) {
                        rows += `<tr class="pt-30">
                                    <td class="image product-thumbnail pt-40"><img src="/${value.options.image}" alt="#"></td>
                                    <td class="product-des product-name">
                                        <h6 class="mb-5"><a class="product-name mb-10 text-heading" href="shop-product-right.html">${value.name}</a></h6>
                                    </td>
                                    <td class="price" data-title="Price">
                                        <h4 class="text-body">$${value.price}</h4>
                                    </td>
                                    <td class="price" data-title="Price">
                                        ${value.options.color == null 
                                            ?`<span>....</span>`
                                            :`<h5 class="text-body">${value.options.color}</h5>`
                                        }                                     
                                    </td>
                                    <td class="price" data-title="Price">
                                        ${value.options.size == null 
                                            ?`<span>....</span>`
                                            :`<h5 class="text-body">${value.options.size}</h5>`
                                        } 
                                    </td>
                                    <td class="text-center detail-info" data-title="Stock">
                                        <div class="detail-extralink mr-15">
                                            <div class="detail-qty border radius">
                                                <a type="submit" class="qty-down" id="${value.rowId}" onclick="cartDecrement(this.id)"><i class="fi-rs-angle-small-down"></i></a>
                                                <input type="text" name="quantity" class="qty-val" value="${value.qty}" min="1">
                                                <a type="submit" class="qty-up" id="${value.rowId}" onclick="cartIncrement(this.id)"><i class="fi-rs-angle-small-up"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="price" data-title="Price">
                                        <h4 class="text-brand">$${value.subtotal}</h4>
                                    </td>
                                    <td class="action text-center" data-title="Remove"><a 
                                    type="submit" id="${value.rowId}" class="text-body" onclick="cartRemove(this.id)"><i class="fi-rs-trash"></i></a></td>
                                </tr>`
                    });

                    $('#cartpage').html(rows);
                }
            });
        }
        cart();
    </script>
    <!-- end load my cart data -->


    <!-- remove product from mycart page -->
     <script type="text/javascript">

            function cartRemove(id) {
                $.ajax({
                    type: 'GET',
                    url: '/cart-remove/' + id,
                    dataType: 'json',
                    success: function(data) {
                        cart();
                        miniCart();
                        //coupon discount price will update if remove cart
                        couponCalculation();
                        //sweet alart
                        const Toast = swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000
                        })

                        if($.isEmptyObject(data.error)) {
                            Toast.fire({
                                type: 'success',
                                icon: "success",
                                title: data.success
                            });
                        }else{
                            Toast.fire({
                                type: 'error',
                                icon: "error",
                                title: data.error
                            })
                        }
                    }
                });
            }

            // Decrement cart quantity
            function cartDecrement(rowId) {
                $.ajax({
                    type: 'GET',
                    url: '/decrement-cart/' + rowId,
                    dataType: 'json',
                    success: function(data) {
                        cart();
                        miniCart();
                        //coupon discount price will update if Decrement cart
                        couponCalculation();
                    }
                });
            }
            //End Decrement cart quantity

            // Increment cart quantity
            function cartIncrement(rowId) {
                $.ajax({
                    type: 'GET',
                    url: '/increment-cart/' + rowId,
                    dataType: 'json',
                    success: function(data) {
                        cart();
                        miniCart();
                        //coupon discount price will update if Increment cart
                        couponCalculation();
                    }
                });
            }
            //End Increment cart quantity

        </script>

    <!-- End remove product from mycart page -->

    <script type="text/javascript">


  function applyCoupon(id){
    var coupon_name = $('#coupon_name').val();
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {coupon_name:coupon_name},

                url: "/coupon-apply",

                success:function(data){
                    couponCalculation();
                   
                    if (data.validity == true) {
                        $('#couponField').hide();
                    }

                     // Start Message 

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  
                  showConfirmButton: false,
                  timer: 3000 
            })
            if ($.isEmptyObject(data.error)) {
                    
                    Toast.fire({
                    type: 'success',
                    icon: 'success', 
                    title: data.success, 
                    })

            }else{
               
           Toast.fire({
                    type: 'error',
                    icon: 'error', 
                    title: data.error, 
                    })
                }

              // End Message  

                }
            })
        }//end apply coupon


        // Start CouponCalculation Method   
        function couponCalculation(){
            $.ajax({
                type: 'GET',
                url: "/coupon-calculation",
                dataType: 'json',
                success:function(data){
                    if (data.total) {
                    $('#couponCalField').html(
                        ` <tr>
                        <td class="cart_total_label">
                            <h6 class="text-muted">Subtotal</h6>
                        </td>
                        <td class="cart_total_amount">
                            <h4 class="text-brand text-end">$${data.total}</h4>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="cart_total_label">
                            <h6 class="text-muted">Grand Total</h6>
                        </td>
                        <td class="cart_total_amount">
                            <h4 class="text-brand text-end">$${data.total}</h4>
                        </td>
                    </tr>
                    ` ) 
                }else{
                    $('#couponCalField').html(
                        `<tr>
                        <td class="cart_total_label">
                            <h6 class="text-muted">Subtotal</h6>
                        </td>
                        <td class="cart_total_amount">
                            <h4 class="text-brand text-end">$${data.subtotal}</h4>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="cart_total_label">
                            <h6 class="text-muted">Coupon </h6>
                        </td>
                        <td class="cart_total_amount">
    <h6 class="text-brand text-end">${data.coupon_name} <a type="submit" onclick="couponRemove()"><i class="fi-rs-trash"></i> </a> </h6>
                        </td>
                    </tr>

                    <tr>
                        <td class="cart_total_label">
                            <h6 class="text-muted">Discount Amount  </h6>
                        </td>
                        <td class="cart_total_amount">
        <h4 class="text-brand text-end">$${data.discount_amount}</h4>
                        </td>
                    </tr>


                    <tr>
                        <td class="cart_total_label">
                            <h6 class="text-muted">Grand Total </h6>
                        </td>
                        <td class="cart_total_amount">
            <h4 class="text-brand text-end">$${data.total_amount}</h4>
                        </td>
                    </tr> `
                        ) 
                } 
                    
                }
            });

        } 
        couponCalculation();
        
        //ene coupon calculation


</script>


<script type="text/javascript">
    // Coupon Remove Start 
  function couponRemove(){
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "/coupon-remove",

                success:function(data){
                   couponCalculation();
                   $('#couponField').show();
                     // Start Message 

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  
                  showConfirmButton: false,
                  timer: 3000 
            })
            if ($.isEmptyObject(data.error)) {
                    
                    Toast.fire({
                    type: 'success',
                    icon: 'success', 
                    title: data.success, 
                    })

            }else{
               
           Toast.fire({
                    type: 'error',
                    icon: 'error', 
                    title: data.error, 
                    })
                }

              // End Message  


                }
            })
        }
// Coupon Remove End 

</script>

   <!--  ////////////// End Apply Coupon ////////////// -->


</body>

</html>