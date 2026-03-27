<?php

namespace App\ValueObject;

use DateTimeImmutable;

readonly class Period
{
    private function __construct(
        public ?DateTimeImmutable $startDate = null,
        public ?DateTimeImmutable $endDate = null,
    ){}

    /**
     * @throws \DateMalformedStringException
     */
    public static function forMonth(int $year, int $month): self
    {
        $start = new DateTimeImmutable("$year-$month-01");
        $end = $start->modify('last day of this month')->setTime(23, 59, 59);

        return new self($start, $end);
    }

    public static function forYear(int $year): self
    {
        $start = new DateTimeImmutable("$year-01-01");
        $end = new DateTimeImmutable("$year-12-31 23:59:59");

        return new self($start, $end);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function forWeek(): self
    {
        $start = new DateTimeImmutable('monday this week');
        $end = $start->modify('sunday this week')->setTime(23, 59, 59);

        return new self($start, $end);
    }

    public static function forDay(): self
    {
        $start = new DateTimeImmutable('today 00:00:00');
        $end = new DateTimeImmutable('today 23:59:59');

        return new self($start, $end);
    }

    public static function allTime(): self
    {
        return new self();
    }

    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }
}
