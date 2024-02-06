{{-- <div class="card-header border-0">
    <h4 class="text-center">{{ translate('messages.stock_Update') }}</h4>
    <input name="product_id" value="{{$product['id']}}" class="initial-hidden">
</div> --}}
<div class="card-body">
    <h3 class="text-center mb-4">{{ translate('messages.stock_Update') }}</h3>

    <div class="d-flex align-items-center gap-2 flex-column mb-3">
        <img width="50" height="50" class="rounded" src="{{asset('storage/app/public/product')}}/{{$product['image']}}" alt="">
        <p class="mb-0">Nestle Every Day Full Cream with honey</p>
        <div class="d-flex gap-2 align-items-center">
            <span>Current Stock </span>: 
            <span class="font-semibold text-dark">10</span>
        </div>
    </div>
    <div class="form-group">
        <div class="mb-4">
            <div class="variant_combination" id="variant_combination">
                @include('admin-views.product.partials._edit-combinations',['combinations'=>json_decode($product['variations'],true),'stock'=>config('module.'.$product->module->module_type)['stock']])
            </div>
            <div id="quantity">
                <label class="control-label"></label>
                <label class="input-label" for="total_stock">{{translate('messages.total_stock')}}</label>
                <input type="number" min="0" class="form-control" name="current_stock" value="{{$product->stock}}" id="quantity" {{count(json_decode($product['variations'],true)) > 0 ? 'readonly' : ""}}>
            </div>
        </div>
    </div>
</div>
