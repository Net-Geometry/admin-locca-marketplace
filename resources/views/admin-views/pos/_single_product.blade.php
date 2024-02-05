<div class="pos-product-card product-card card quick-View h-100" data-id="{{$product->id}}" data-item-count="12">
    <div class="inline_product clickable p-0 initial--31">
        <div class="d-flex align-items-center justify-content-center h-100 d-block w-100 ">
            <img
            src="{{ \App\CentralLogics\Helpers::onerror_image_helper(
                $product['image'] ?? '',
                asset('storage/app/public/product').'/'.$product['image'] ?? '',
                asset('public/assets/admin/img/160x160/img2.jpg'),
                'product/'
            ) }}" 
            data-onerror-image="{{asset('public/assets/admin/img/160x160/img2.jpg')}}"
                class="w-100 h-100 object-cover onerror-image" alt="image">
        </div>
    </div>

    <div class="card-body inline_product text-center p-1 clickable">
        <div class="product-title text-dark text-capitalize max-text-2-line">
            {{-- {{ Str::limit($product['name'], 32,'...') }} --}}
            {{$product['name']}}
        </div>
        <div class="product-price text-center mt-2">
            <span class="text-primary font-weight-bold">
                {{\App\CentralLogics\Helpers::format_currency($product['price']-\App\CentralLogics\Helpers::product_discount_calculate($product, $product['price'], $store_data)['discount_amount'])}}
            </span>
        </div>
    </div>
</div>
<script src="{{asset('public/assets/admin')}}/js/view-pages/common.js"></script>
