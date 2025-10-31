<!-- Start Single Product -->
<div class="single-product">
    <div class="product-image">
        <img src="{{ $product->image_url }}" alt="#">
        @if ($product->discount)
            <span class="sale-tag">-{{ $product->discount }}%</span>
        @endif
        <div class="button">
            <a href="{{ route('products.show', $product->slug) }}" class="btn"><i class="lni lni-cart"></i> Add to
                Cart</a>
        </div>
    </div>
    <div class="product-info">
        <span class="category">{{ $product->category->name }}</span>
        <h4 class="title">
            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
        </h4>
        <ul class="review">
            @php
                $review = floor($product->rating);

                $reviewMinusFull = 5 - $review;
            @endphp
            @for ($i = 1; $i <= $review; $i++)
                <li><i class="lni lni-star-filled"></i></li>
            @endfor
            @for ($i = 1; $i <= $reviewMinusFull; $i++)
                <li><i class="lni lni-star"></i></li>
            @endfor
            <li><span>{{ $product->rating }}Review(s)</span></li>
        </ul>
        <div class="price">
            <span> {{ App\Helpers\Currency::format($product->price) }}</span>
            @if ($product->compare_price)
                <span class="discount-price"> {{ App\Helpers\Currency::format($product->compare_price) }}</span>
            @endif
        </div>
    </div>
</div>
<!-- End Single Product -->
