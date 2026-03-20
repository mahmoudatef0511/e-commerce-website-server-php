<?php

namespace App\Entities;

class ProductEntity implements \JsonSerializable
{
    private int $product_id;
    private ?string $id = null;
    private string $name;
    private ?string $category = null;
    private bool $inStock = false;
    private ?string $brand = null;
    private ?string $description = null;

    /** @var AttributeEntity[] */
    private array $attributes = [];

    /** @var string[] */
    private array $gallery = [];

    /** @var PriceEntity[] */
    private array $prices = [];

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->category = $data['category'] ?? null;
        $this->inStock = isset($data['inStock']) ? (bool)$data['inStock'] : ($data['in_stock'] ?? false);
        $this->brand = $data['brand'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->initAttributes($data['attributes'] ?? []);
        $this->initGallery($data['gallery'] ?? []);
        $this->initPrices($data['prices'] ?? []);
    }

    protected function initAttributes(array $attributesData): void
    {

        // Merge with DB attributes
        foreach ($attributesData as $attrRaw) {
            $attr = new AttributeEntity();
            $attr->setId((int)($attrRaw['id'] ?? 0));
            $attr->setName($attrRaw['name']);
            $attr->setType($attrRaw['type']);

            foreach ($attrRaw['items'] ?? [] as $itemRaw) {
                $item = new AttributeItemEntity();
                $item->setId((int)$itemRaw['id']);
                $item->setValue($itemRaw['value']);
                $item->setDisplayValue($itemRaw['displayValue']);
                $attr->addItem($item);
            }

            $this->addAttribute($attr);
        }
    }

    // ------------------------------
    // Gallery
    // ------------------------------
    protected function initGallery(array $galleryData): void
    {
        foreach ($galleryData as $img) {
            $this->gallery[] = $img;
        }
    }

    public function getGallery(): array
    {
        return $this->gallery;
    }

    // ------------------------------
    // Prices
    // ------------------------------
    protected function initPrices(array $pricesData): void
    {
        foreach ($pricesData as $priceRaw) {
            $price = new PriceEntity();
            $price->setAmount((float)$priceRaw['amount']);
            $price->setCurrency($priceRaw['currency']);
            $this->prices[] = $price;
        }
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    // ------------------------------
    // Attributes
    // ------------------------------
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    protected function addAttribute(AttributeEntity $attribute): void
    {
        $this->attributes[] = $attribute;
    }

    // ------------------------------
    // Getters / Setters for core properties
    // ------------------------------
    public function getProductId(): int
    {
        return $this->product_id;
    }
    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getCategory(): ?string
    {
        return $this->category;
    }
    public function isInStock(): bool
    {
        return $this->inStock;
    }
    public function getBrand(): ?string
    {
        return $this->brand;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function jsonSerialize(): array
    {
        return [
            'product_id'  => $this->getProductId(),
            'id'          => $this->getId(),
            'name'        => $this->getName(),
            'category'    => $this->getCategory(),
            'brand'       => $this->getBrand(),
            'description' => $this->getDescription(),
            'inStock'     => $this->isInStock(),
            'gallery'     => $this->getGallery(),
            'attributes'  => array_map(fn($attr) => $attr->jsonSerialize(), $this->getAttributes()),
            'prices'      => array_map(fn($price) => $price->jsonSerialize(), $this->getPrices()),
        ];
    }
}
