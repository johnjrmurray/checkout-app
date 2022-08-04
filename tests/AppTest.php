<?php

declare(strict_types=1);

namespace Checkout;

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
	public function testProductA(): void
	{
		$checkout = new App;

		// Check standard price
		$checkout->addItem('A', 1);
		$this->assertEquals('0.50', $checkout->getTotal());

		// Check discounted price
		$checkout->addItem('A', 2);
		$this->assertEquals('1.30', $checkout->getTotal());

		// Check adding another item to ensure standard price is added
		$checkout->addItem('A', 1);
		$this->assertEquals('1.80', $checkout->getTotal());
	}

	public function testProductB(): void
	{
		$checkout = new App;

		// Check standard price
		$checkout->addItem('B', 1);
		$this->assertEquals('0.30', $checkout->getTotal());

		// Check discounted price
		$checkout->addItem('B', 1);
		$this->assertEquals('0.45', $checkout->getTotal());

		// Check adding a third item
		$checkout->addItem('B', 1);
		$this->assertEquals('0.75', $checkout->getTotal());
	}

	public function testProductC(): void
	{
		$checkout = new App;

		// Check standard price
		$checkout->addItem('C', 1);
		$this->assertEquals('0.20', $checkout->getTotal());

		// Check first possible special price
		$checkout->addItem('C', 1);
		$this->assertEquals('0.38', $checkout->getTotal());

		// check second possible special price
		$checkout->addItem('C', 1);
		$this->assertEquals('0.50', $checkout->getTotal());

		// Check that lowest possible special price is applied
		$checkout->addItem('C', 1);
		$this->assertEquals('0.70', $checkout->getTotal());

		// check another combo of special prices
		$checkout->addItem('C', 1);
		$this->assertEquals('0.88', $checkout->getTotal());

		// check another combo of special prices
		$checkout->addItem('C', 1);
		$this->assertEquals('1.00', $checkout->getTotal());
	}

	/**
	 * @covers \App\addItem
	 * @covers \App\getTotal
	 */
	public function testProductD(): void
	{
		$checkout = new App;

		// Check standard price
		$checkout->addItem('D', 1);
		$this->assertEquals('0.15', $checkout->getTotal());

		// Check special price
		$checkout->addItem('A', 1);
		$this->assertEquals('0.55', $checkout->getTotal());

		// Check that special price is only applicable once per paired item
		$checkout->addItem('D', 1);
		$this->assertEquals('0.70', $checkout->getTotal());
	}

	/**
	 * @covers \App\addItem
	 * @covers \App\getTotal
	 */
	public function testProductE(): void
	{
		$checkout = new App;

		// Check standard price
		$checkout->addItem('E', 1);
		$this->assertEquals('0.05', $checkout->getTotal());

		// Check no discounts are applied
		$checkout->addItem('E', 1);
		$this->assertEquals('0.10', $checkout->getTotal());
	}
}
