<?php
    declare(strict_types=1);

    namespace Checkout; 
    use PHPUnit\Framework\TestCase;

    class AppTest extends TestCase
    {
        /**
          * @covers \App\CalculatePrice
          */
        public function testProductA(): void
        {
          $checkout = new App;

          $checkout->addItem('A', 1);
          $this->assertEquals('0.50',$checkout->getTotal());

          $checkout->addItem('A', 2);
          $this->assertEquals('1.30',$checkout->getTotal());
        
          $checkout->addItem('A', 1);
          $this->assertEquals('1.80',$checkout->getTotal());

        }

        /**
          * @covers \App\CalculatePrice
          */
          public function testProductB(): void
          {
            $checkout = new App;
  
            $checkout->addItem('B', 1);
            $this->assertEquals('0.30',$checkout->getTotal());
  
            $checkout->addItem('B', 1);
            $this->assertEquals('0.45',$checkout->getTotal());
          
            $checkout->addItem('B', 1);
            $this->assertEquals('0.75',$checkout->getTotal());
  
          }

        /**
          * @covers \App\CalculatePrice
          */
          public function testProductD(): void
          {
            $checkout = new App;
  
            // Check standard price
            $checkout->addItem('D', 1);
            $this->assertEquals('0.15',$checkout->getTotal());
  
            // Check special price
            $checkout->addItem('A', 1);
            $this->assertEquals('0.55',$checkout->getTotal());
           
            // Check that special price is only applicable once per paired item
            $checkout->addItem('D', 1);
            $this->assertEquals('0.70',$checkout->getTotal());
  
          }
      }