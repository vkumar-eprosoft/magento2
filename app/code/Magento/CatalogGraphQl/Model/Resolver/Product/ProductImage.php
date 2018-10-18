<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\CatalogGraphQl\Model\Resolver\Product;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\ImageFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Returns product's image data
 */
class ProductImage implements ResolverInterface
{
    /**
     * Product image factory
     *
     * @var ImageFactory
     */
    private $productImageFactory;

    /**
     * @param ImageFactory $productImageFactory
     */
    public function __construct(
        ImageFactory $productImageFactory
    ) {
        $this->productImageFactory = $productImageFactory;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): array {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }
        /** @var Product $product */
        $product = $value['model'];
        $imageType = $field->getName();

        $imagePath = $product->getData($imageType);
        $imageLabel = $product->getData($imageType . '_' . 'label') ?? $product->getName();

        $image = $this->productImageFactory->create();
        $image->setDestinationSubdir($imageType)
            ->setBaseFile($imagePath);
        $imageUrl = $image->getUrl();

        return [
            'url' => $imageUrl,
            'path' => $imagePath,
            'label' => $imageLabel,
        ];
    }
}
