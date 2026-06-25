<?php

declare(strict_types=1);

namespace App\Domain\VOs;

use InvalidArgumentException;

/**
 * Value Object para valores monetários
 * 
 * DESIGN:
 * - Armazena valores em CENTAVOS (int) para precisão absoluta
 * - Operações aritméticas sem float (evita 0.1 + 0.2 = 0.30000000000004)
 * - Imutável (readonly) - toda operação retorna novo objeto
 * - Type-safe - força validações
 * 
 * EXEMPLOS:
 * ```php
 * $saldo = MoneyVO::fromCents(15900);        // R$ 159,00
 * $compra = MoneyVO::fromReais(100.50);      // R$ 100,50
 * 
 * $restante = $saldo->subtract($compra);     // R$ 58,50
 * 
 * if ($saldo->greaterThanOrEqual($compra)) {
 *     // Tem saldo suficiente
 * }
 * 
 * echo $saldo->format();                      // "R$ 159,00"
 * $apiResponse = $saldo->toCents();           // 15900
 * ```
 */
final readonly class MoneyVO
{
    /**
     * Valor em centavos (int para precisão total)
     */
    private int $cents;

    /**
     * @param int $cents Valor em centavos (ex: 15900 = R$ 159,00)
     * @throws InvalidArgumentException Se valor for negativo
     */
    private function __construct(int $cents)
    {
        if ($cents < 0) {
            throw new InvalidArgumentException("MoneyVO não aceita valores negativos: {$cents}");
        }

        $this->cents = $cents;
    }

    // ==================== FACTORY METHODS ====================

    /**
     * Cria MoneyVO a partir de centavos
     * 
     * @param int $cents Valor em centavos (ex: 15900 = R$ 159,00)
     */
    public static function fromCents(int $cents): self
    {
        return new self($cents);
    }

    /**
     * Cria MoneyVO a partir de reais
     * 
     * @param float|int $reais Valor em reais (ex: 159.00 ou 159)
     */
    public static function fromReais(float|int $reais): self
    {
        return new self((int) round($reais * 100));
    }

    /**
     * Cria MoneyVO zerado
     */
    public static function zero(): self
    {
        return new self(0);
    }

    // ==================== OPERAÇÕES ARITMÉTICAS ====================

    /**
     * Adiciona valor
     */
    public function add(self $other): self
    {
        return new self($this->cents + $other->cents);
    }

    /**
     * Subtrai valor
     * 
     * @throws InvalidArgumentException Se resultado for negativo
     */
    public function subtract(self $other): self
    {
        return new self($this->cents - $other->cents);
    }

    /**
     * Multiplica por quantidade (sem criar valores negativos)
     * 
     * Útil para: quantidade de itens, taxas em porcentagem
     * Exemplo: R$ 100,00 * 3 = R$ 300,00
     */
    public function multiply(int|float $factor): self
    {
        if ($factor < 0) {
            throw new InvalidArgumentException("Fator de multiplicação não pode ser negativo");
        }

        return new self((int) round($this->cents * $factor));
    }

    /**
     * Divide por quantidade
     * 
     * Útil para: rateio, divisão de valores
     * Exemplo: R$ 100,00 / 3 = R$ 33,33
     */
    public function divide(int|float $divisor): self
    {
        if ($divisor <= 0) {
            throw new InvalidArgumentException("Divisor deve ser maior que zero");
        }

        return new self((int) round($this->cents / $divisor));
    }

    // ==================== COMPARAÇÕES ====================

    /**
     * Verifica se é maior que outro valor
     */
    public function greaterThan(self $other): bool
    {
        return $this->cents > $other->cents;
    }

    /**
     * Verifica se é maior ou igual a outro valor
     */
    public function greaterThanOrEqual(self $other): bool
    {
        return $this->cents >= $other->cents;
    }

    /**
     * Verifica se é menor que outro valor
     */
    public function lessThan(self $other): bool
    {
        return $this->cents < $other->cents;
    }

    /**
     * Verifica se é menor ou igual a outro valor
     */
    public function lessThanOrEqual(self $other): bool
    {
        return $this->cents <= $other->cents;
    }

    /**
     * Verifica se é igual a outro valor
     */
    public function equals(self $other): bool
    {
        return $this->cents === $other->cents;
    }

    /**
     * Verifica se é zero
     */
    public function isZero(): bool
    {
        return $this->cents === 0;
    }

    /**
     * Verifica se é positivo (maior que zero)
     */
    public function isPositive(): bool
    {
        return $this->cents > 0;
    }

    // ==================== CONVERSÕES ====================

    /**
     * Retorna valor em centavos (para API, banco de dados)
     * 
     * @return int Valor em centavos (ex: 15900)
     */
    public function toCents(): int
    {
        return $this->cents;
    }

    /**
     * Retorna valor em reais (para cálculos, exibição)
     * 
     * @return float Valor em reais (ex: 159.00)
     */
    public function toReais(): float
    {
        return $this->cents / 100;
    }

    /**
     * Alias de toReais() para compatibilidade com DecimalVO
     */
    public function toFloat(): float
    {
        return $this->toReais();
    }

    // ==================== FORMATAÇÃO ====================

    /**
     * Formata para exibição com símbolo
     * 
     * @return string Exemplo: "R$ 159,00"
     */
    public function format(): string
    {
        return "R$ " . number_format($this->toReais(), 2, ',', '.');
    }

    /**
     * Formata para exibição sem símbolo
     * 
     * @return string Exemplo: "159,00"
     */
    public function formatWithoutSymbol(): string
    {
        return number_format($this->toReais(), 2, ',', '.');
    }

    /**
     * Conversão para string automática
     */
    public function __toString(): string
    {
        return $this->format();
    }
}
