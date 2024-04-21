<div>
    <div class="bg-gray-800 flex justify-center align-center">
        <p>Sidebar Header</p>
    </div>

    <div class="bg-gray-600">
        @if($card)
            @foreach($ebayData[$card->id] as $listingData)
                <div class="p-4">
                    <div class="w-100 flex align-center">
                        <div class="listing-image-container">
                            <img class="rounded" height="auto" width="100%" src="{{ $listingData['image'] }}" alt="{{ $listingData['title'] }}">
                        </div>

                    </div>

                    <div class="flex mb-0">
                        <p class="mb-0">star</p>
                        <p class="flex mb-0">{{$listingData['seller']['feedbackScore']}} ({{$listingData['seller']['feedbackPercentage']}}%)</p>
                    </div>

                    <div>
                        <p>{{$listingData['seller']['username']}}</p>
                    </div>

                    <div>
                        <p>{{$listingData['title']}}</p>
                        <p>{{$listingData['price']}}</p>
                    </div>

                    <div>
                        <a target="_blank" href="{{$listingData['url']}}">View on Ebay</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
