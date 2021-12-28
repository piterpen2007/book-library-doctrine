<?php

namespace EfTech\BookLibrary\ValueObject;

/**
 * Закупочная цена
 */
final class PurchasePrice
{
    /** Время когда была получена информация о закупочной цене
     * @var \DateTimeInterface
     */
    private \DateTimeInterface $date;
    /** Деньги
     * @var Money
     */
    private Money $money;

    /**
     * @param \DateTimeInterface $date Время когда была получена информация о закупочной цене
     * @param Money $money Деньги
     */
    public function __construct(\DateTimeInterface $date, Money $money)
    {
        $this->date = $date;
        $this->money = $money;
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
        return $this->money;
    }



}