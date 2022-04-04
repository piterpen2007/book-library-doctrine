<?php

namespace EfTech\BookLibrary\ValueObject;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use EfTech\BookLibrary\Entity\AbstractTextDocument;

/**
 * Закупочная цена
 *
 * @ORM\Table(name="purchase_prices")
 * @ORM\Entity()
 */

final class PurchasePrice
{
    /**
     * Идентификатор страны
     *
     * @var int|null
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="purchase_prices_id_seq")
     */
    private ?int $id = null;

    /**
     * Время, когда была получена информация о закупочной цене
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(name="date", type="datetime_immutable", nullable=false)
     */

    private \DateTimeInterface $date;
    /** Деньги
     * @var Money|null
     */
    private ?Money $money = null;
    /**
     * Значение стоимости закупочной цены
     *
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private int $price;

    /**
     * Валюта закупочной цены
     *
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity=\EfTech\BookLibrary\ValueObject\Currency::class)
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private Currency $currency;

    /**
     * Текстовый документ
     *
     * @var AbstractTextDocument|null
     *
     * @ORM\ManyToOne(
     *     targetEntity=\EfTech\BookLibrary\Entity\AbstractTextDocument::class,
     *     inversedBy="purchasePrices"
     *     )
     * @ORM\JoinColumn(name="text_document_id", referencedColumnName="id")
     */
    private ?AbstractTextDocument $textDocument = null;


    /**
     * @param \DateTimeInterface $date Время когда была получена информация о закупочной цене
     * @param Money $money Деньги
     */
    public function __construct(\DateTimeInterface $date, Money $money)
    {
        $this->date = $date;
        $this->money = $money;
        $this->price = $money->getAmount();
        $this->currency = $money->getCurrency();
    }

    /** Время когда была получена информация о закупочной цене
     * @return \DateTimeInterface
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    /** Деньги
     * @return Money
     */
    public function getMoney(): Money
    {
        if (null === $this->money) {
            $this->money = new Money($this->price, $this->currency);
        }

        return $this->money;
    }
}
