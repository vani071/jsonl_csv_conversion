<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\ProcessingService;


class ProcessingServiceTest extends WebTestCase
{
    public function testDiscountNonType(): void
    {
        $data = [
            ["value"=>5,"type"=>"NOMINAL"]
        ];
        $totalOrder = 561.112;
        $processingService  = new ProcessingService();

        $this->assertEquals( $processingService->discountCalc($data,$totalOrder),0);

    }

    public function testDiscountMixType(): void
    {
        $data = [
            ["value"=>5,"type"=>"PERCENTAGE"],
            ["value"=>5,"type"=>"DOLLAR"],
        ];
        $totalOrder = 10.00;
        $processingService  = new ProcessingService();

        $this->assertEquals( $processingService->discountCalc($data,$totalOrder),5.5);
    }

    public function testEmptyRow(): void
    {
        $data = '';
        $processingService  = new ProcessingService();

        $this->assertEquals( $processingService->generateRowData($data),[]);
    }

    public function testSkipZeroTotal(): void
    {
        $data = '{
            "order_id": 1001,
            "order_date": "Fri, 08 Mar 2019 12:13:29 +0000",
            "customer": {
              "customer_id": 7970214,
              "first_name": "Edwardo",
              "last_name": "Rowe",
              "email": "edwardo.rowe@example.org",
              "phone": "(08) 7167 1968",
              "shipping_address": {
                "street": "FLAT 2 896 NEPEAN HWY",
                "postcode": "3931",
                "suburb": "MORNINGTON",
                "state": "VICTORIA"
              }
            },
            "items": [
              {
                "quantity": 4,
                "unit_price": 39.95,
                "product": {
                  "product_id": 3793908,
                  "title": "Cellsafe Radi Chip Universal",
                  "subtitle": null,
                  "image": "https://s.catch.com.au/images/product/0018/18663/5c986e257eb06332953424.jpg",
                  "thumbnail": "https://s.catch.com.au/images/product/0018/18663/5c986e257eb06332953424_w200.jpg",
                  "category": [
                    "ELECTRONICS",
                    "PHONES",
                    "MOBILE PHONE ACCESSORIES",
                    "MOBILE PHONE CASE"
                  ],
                  "url": "https://www.catch.com.au/product/cellsafe-radi-chip-universal-3793908",
                  "upc": "680569511577",
                  "gtin14": null,
                  "created_at": "2019-03-12 17:44:17",
                  "brand": {
                    "id": 4757,
                    "name": "Cellsafe"
                  }
                }
              },
              {
                "quantity": 2,
                "unit_price": 99.99,
                "product": {
                  "product_id": 3879592,
                  "title": "Pulsar Mens 41mm Day Stainless Steel Watch - Gold",
                  "subtitle": null,
                  "image": "https://s.catch.com.au/images/product/0019/19430/5cb846fcd383e747048710.jpg",
                  "thumbnail": "https://s.catch.com.au/images/product/0019/19430/5cb846fcd383e747048710_w200.jpg",
                  "category": [
                    "FASHION ACCESSORIES",
                    "WATCHES",
                    "WATCHES - MENS",
                    "STEEL BAND WATCH"
                  ],
                  "url": "https://www.catch.com.au/product/pulsar-mens-41mm-day-stainless-steel-watch-gold-3879592",
                  "upc": "4894138037443",
                  "gtin14": null,
                  "created_at": "2019-03-28 15:01:12",
                  "brand": {
                    "id": 3024,
                    "name": "Pulsar"
                  }
                }
              }
            ],
            "discounts": [
                {
                    "type":"DOLLAR",
                    "value": 359.78
                }
            ],
            "shipping_price": 6.99
          }';
        $processingService  = new ProcessingService();

        $this->assertEquals( $processingService->generateRowData($data),[]);
    }
}