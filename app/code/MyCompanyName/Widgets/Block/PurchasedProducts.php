<?php

namespace MyCompanyName\Widgets\Block;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Framework\App\Http\Context;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Widget\Block\BlockInterface;

class PurchasedProducts extends AbstractProduct implements BlockInterface
{
    /**
     * default value for products that will be shown
     */
    const DEFAULT_PRODUCTS_COUNT = 12;

    protected $_template = "widget/purchased_products.phtml";

    protected $httpContext;

    protected $orderCollectionFactory;

    protected $orderRepository;

    protected $productCollectionFactory;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        Context $httpContext,
        CollectionFactory $orderCollectionFactory,
        OrderRepositoryInterface $orderRepository,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->httpContext = $httpContext;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getTitle()
    {
        return $this->getData('title');
    }

    public function displayPage()
    {
        return $this->getData('display_page');
    }

    public function getCustomerId()
    {
        return $this->httpContext->getValue('customer_id');
    }

    public function getOrderCollectionIds()
    {
        $customerOrder = $this->orderCollectionFactory->create()
            ->addFieldToFilter('customer_id', $this->getCustomerId());
        $customerOrder->addFieldToFilter('status', ['in' => ['complete']]);
        return $customerOrder->getAllIds();
    }

    public function getProductIds()
    {
        $ids = [];
        $orderCollectionsIds = $this->getOrderCollectionIds();
        foreach ($orderCollectionsIds as $orderId) {
            $order = $this->orderRepository->get($orderId);
            foreach ($order->getItems() as $item) {
                if (!in_array($item->getProductId(), $ids)) {
                    $ids[] = $item->getProductId();
                }
            }
        }
        return $ids;
    }

    public function getItemsCount()
    {
        $productIds = $this->getProductIds();
        return count($productIds);
    }

    public function getLoadedProductCollection()
    {
        $collection = $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', $this->getProductIds());
        $collection->addAttributeToFilter('type_id', ['eq' => 'simple']);
        return $collection;
    }
}
