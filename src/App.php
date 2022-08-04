<?php
	
	declare(strict_types=1);

	namespace Checkout;

	class App {
		
		public $items;
  		
		public function __construct() {
			$this->items = [];
  		}

		// Add an item to the checkout
		public function addItem($sku, $quantity): bool {
			if(!isset($this->items[$sku])) {
				$this->items[$sku] = $quantity;
			} else {
				$this->items[$sku] += $quantity;

			}

			return true;
		}

		// Remove an item from the checkout
		public function removeItem($sku, $quantity): bool {
			if(isset($this->items[$sku])) {
				$this->items[$sku] -= $quantity;
			} 
		}

		// Get all current items
		public function getItems(): array {
			return $this->items;
		}

		// Get total of all items at checkout
		public function getTotal(): float {

			$checkoutTotal = 0;

			foreach($this->items as $item => $quantity) {
				$checkoutTotal += $this->calculateItemTotal($item, $quantity);
			}
			
			return $checkoutTotal;
		}

		// Calculate total for particular item
		public function calculateItemTotal($sku, $quantity): float {
			$itemPrice = $this->getItemPrice($sku);
			$itemDiscounts = $this->calculateItemDiscounts($sku, $quantity);

			$itemTotal = $itemPrice * $quantity;
			$itemTotal -= $itemDiscounts;

			return $itemTotal;
		}

		// Get discount for a particular item
		public function calculateItemDiscounts($sku, $quantity): float {

			$discountArray = $this->getDiscounts($sku);
			$itemDiscount = 0;

			// If there are no discounts applicable 
			if(empty($discountArray)) {
				null;
			} elseif(isset($discountArray['eligibleProductPair'])) {
				// If there is an eligable product pair
				// For the quantity of that item, then discount by
				// the specified amount
				
				
				// Get quantity of product pair
				$checkoutItems = $this->getItems();
				
				if(!isset($checkoutItems[$discountArray['eligibleProductPair']])) {
					return $itemDiscount;
				}

				$pairedItems = $checkoutItems[$discountArray['eligibleProductPair']];
				
				for($i = 1; $i <= $quantity; $i++) {
					if($i <= $pairedItems) {
						$itemDiscount += $discountArray['totalDiscount'];
					}
				} 
			// Multiple discounts available 
			} elseif(count($discountArray) > 1 && (isset($discountArray[0]) && is_array($discountArray[0]))) {

				// Sort array in descending order - highest possible discount will be first
				$discountArray = array_reverse($discountArray);

				$tempQty = $quantity;

				foreach($discountArray as $discount) {

					for($i = 0; $i < $quantity; $i++) {
						// Attempt to take discount quantity off
						if($tempQty - $discount['eligibleQuantity'] < 0) {
							continue;
						} else {
							$itemDiscount += $discount['totalDiscount'];
							$tempQty -= $discount['eligibleQuantity'];
						}
					}
				}
			} else {
				// Simple discount - work out how many times to discount the item
				$discountTimes = floor($quantity / $discountArray['eligibleQuantity']);
				$itemDiscount = $discountArray['totalDiscount'] * $discountTimes;
			}

			return $itemDiscount;

		}

		// Set up item prices
		public function getItemPrice($sku): float {
			$itemPrices = [
				'A' => 0.50,
				'B' => 0.30,
				'C' => 0.20,
				'D' => 0.15,
				'E' => 0.05
			];

			return $itemPrices[$sku];
		}

		// Get discounts for item
		public function getDiscounts($sku): array {
			$discounts = [
				'A' => [
					'eligibleQuantity' => 3,
					'totalDiscount' => 0.20
				],
				'B' => [
					'eligibleQuantity' => 2,
					'totalDiscount' => 0.15
				],
				'C' => [
					[
						'eligibleQuantity' => 2,
						'totalDiscount' => 0.02
					], [
						'eligibleQuantity' => 3,
						'totalDiscount' => 0.10
					]
				],
				'D' => [
					'totalDiscount' => 0.10,
					'eligibleProductPair' => 'A'
				],
				'E' => []
			];

			return $discounts[$sku];
		}
}
