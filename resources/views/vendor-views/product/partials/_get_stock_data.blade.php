
<h3 class="modal-title fs-20 mb-4">{{$product->name}}</h3>
<input name="product_id" value="{{$product->id}}" type="hidden" class="initial-hidden">
<div id="quantity" class="form-group">
    <label for="total_qty" class="input-label" >
        {{translate('Total_Quantity')}}
    </label>
    <input type="number" min="1" class="form-control" id="total_qty" name="current_stock" value="{{$product->stock}}" id="quantity" {{count(json_decode($product['variations'],true)) > 0 ? 'readonly' : ""}}>
</div>

@if (count(json_decode($product['variations'],true)) > 0)

<div class="table-responsive mb-5">
    <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle mb-0">
        <thead class="bg-E5F5F6">
            <tr>
                <th class="text--title fs-20">{{ translate('SL') }}</th>
                <th class="text--title fs-20">{{ translate('Variant') }}</th>
                <th class="text--title fs-20 text-center">{{ translate('Stock') }}</th>
            </tr>
        </thead>
        <tbody id="set-rows">
            @foreach (json_decode($product['variations'],true) ?? [] as $key => $combination)
                <tr>
                    <td class="">{{ $key + 1 }}</td>
                    <td class="">
                        {{ $combination['type'] }}
                        <input value="{{ $combination['type'] }}" name="type[]" type="hidden">
                        <input type="number" name="price_{{ $combination['type'] }}"
                        value="{{$combination['price'] ?? 0}}" min="0"
                        step="0.01"
                        class="form-control" hidden>
                    </td>
                    <td class="w-200">
                        <input type="number" name="stock_{{ $combination['type'] }}"
                            value="{{ $combination['stock'] ?? 0 }}" min="1" max="999999999" class="form-control update_qty"
                            required>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<script>

    $('.update_qty').on('keyup', function () {
            update_qty();
        });

</script>
