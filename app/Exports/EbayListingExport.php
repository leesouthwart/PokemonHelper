<?php

namespace App\Exports;

use App\Models\Batch;
use App\Models\EbayListing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EbayListingExport implements FromCollection, WithMapping, WithHeadings
{
    protected $batch;
    protected $service;

    public function __construct(Batch $batch)
    {
        $this->batch = $batch;
        $this->service = new \App\Services\PsaService();
    }

    public function collection()
    {
        return EbayListing::where('batch_id', $this->batch->id)->get();
    }

    public function map($ebayListing): array
    {
        return [
            'Draft', // action SiteID
            '183454', // Category ID
            $ebayListing->title, // Title
            $ebayListing->price, // Price
            $ebayListing->quantity, // Quantity
            $ebayListing->image_1 . '|' . $ebayListing->image_2, // Item Photo URL
            'Graded', // Condition ID
            $this->service->formatBodyDescription($ebayListing->image_1, $ebayListing->image_2, $ebayListing->title), // Description
            'FixedPrice', // Format
            ''
        ];
    }

    public function headings(): array
    {
        return [
            ['#INFO', 'Version=0.0.2', 'Template= eBay-draft-listings-template_GB'],
            ['#INFO Action and Category ID are required fields. 1) Set Action to Draft 2) Please find the category ID for your listings here: https://pages.ebay.com/sellerinformation/news/categorychanges.html'],
            ["#INFO After you've successfully uploaded your draft from the Seller Hub Reports tab, complete your drafts to active listings here: https://www.ebay.co.uk/sh/lst/drafts"],
            ['#INFO'],
            [
                'Action(SiteID=UK|Country=GB|Currency=GBP|Version=1193|CC=UTF-8)Action(SiteID=UK|Country=GB|Currency=GBP|Version=1193|CC=UTF-8)',
                'Category ID',
                'Title',
                'Price',
                'Quantity',
                'Item photo URL',
                'Condition ID',
                'Description',
                'Format',
                'Custom label (SKU)'
            ]
        ];

    }
}
