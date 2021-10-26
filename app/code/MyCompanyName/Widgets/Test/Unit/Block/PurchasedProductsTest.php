<?php

namespace MyCompanyName\Widgets\Test\Unit\Block;

use Magento\Framework\App\Http\Context;
use Magento\Widget\Block\BlockInterface;
use MyCompanyName\Widgets\Block\PurchasedProducts;
use PHPUnit\Framework\TestCase;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class PurchasedProductsTest extends TestCase
{
    /**
     * @var PurchasedProducts
     */
    private $object;

    protected function setUp(): void
    {
        $contextMock = $this->getMockBuilder(\Magento\Catalog\Block\Product\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $httpContextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $orderCollectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $orderRepositoryMock = $this->getMockBuilder(OrderRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productCollectionFactoryMock = $this->getMockBuilder(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->object = new PurchasedProducts($contextMock, $httpContextMock, $orderCollectionFactoryMock, $orderRepositoryMock, $productCollectionFactoryMock);
    }

    public function testPurchasedProductsInstance()
    {
        $this->assertInstanceOf(PurchasedProducts::class, $this->object);
    }

    public function testImplementsBlockInterface()
    {
        $this->assertInstanceOf(BlockInterface::class, $this->object);
    }
}
