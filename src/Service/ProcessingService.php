<?php
namespace App\Service;


class ProcessingService 
{
    public function generateRowData(string $row){
        
        if(empty($row)) return [];
        $result = [];
        $totalOrderValue = 0;
        $totalDiscount = 0;
        $totalUnitPrice = 0;
        $totalQty = 0;
        
        $data = json_decode($row,true);
        $orderItem = count($data['items']);

        $result[] = $data['order_id']; // order_id | The numeric order id
        $datetime = new \DateTime($data['order_date']);
        $result[] = $datetime->format('c'); // order_datetime  | The datetime the order was placed in ISO 8601 format in the UTC timezone.
        
        
        if( $orderItem == 0) return $result;

        foreach ($data['items'] as $dataItem) {
            $totalOrderValue = $totalOrderValue + ($dataItem['unit_price'] * $dataItem['quantity']);
            $totalQty = $totalQty + $dataItem['quantity'];
            $totalUnitPrice = $totalUnitPrice + $dataItem['unit_price'];
        }

        $totalDiscount = $this->discountCalc($data['discounts'],$totalOrderValue);

        if($totalOrderValue - $totalDiscount == 0) return [];

        $result[] = $this->dollarConvertion($totalOrderValue - $totalDiscount); //  total_order_value   | The dollar sum of all line items in the order, *excluding* shipping, with all discounts applied. Note, discounts do not apply to shipping.
        
        $result[] = $this->dollarConvertion($totalUnitPrice / $orderItem); // average_unit_price  | The average price of each unit in the order, in dollars.
        $result[] = $orderItem; // distinct_unit_count | The count of unique units contained in the order.
        $result[] = $totalQty; // total_units_count   | The total number of units in the order
        $result[] = $data['customer']['shipping_address']['state']; // customer_state      | The state code from the customer’s shipping address, e.g. "Victoria"
        
        return $result;
    }

    public function dollarConvertion(float $value){
        return floor($value * 100) / 100;
    }

    public function discountCalc(array $arrDicount,float $totalOrderValue){
        $totalDiscount = 0;
        if(count($arrDicount) > 0){
            foreach ($arrDicount as $dataDiscount) {

                switch ($dataDiscount['type']) {
                    case 'DOLLAR':
                        $totalDiscount = $totalDiscount + $dataDiscount['value'];
                        break;
                    
                    case 'PERCENTAGE':
                        $discountValue = $dataDiscount['value']/100;
                        $totalDiscount = $totalDiscount + ($totalOrderValue * $discountValue);
                        break;
                    
                    default:
                        break;
                }
            }   
        }
        return $totalDiscount;
    }
}