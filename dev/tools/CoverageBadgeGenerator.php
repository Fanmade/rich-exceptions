<?php

declare(strict_types=1);

namespace Fanmade\Tools;

use SimpleXMLElement;

class CoverageBadgeGenerator
{
    private string $svgContent = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="99" height="20">
    <linearGradient id="b" x2="0" y2="100%">
        <stop offset="0" stop-color="#bbb" stop-opacity=".1"/>
        <stop offset="1" stop-opacity=".1"/>
    </linearGradient>
    <mask id="a">
        <rect width="99" height="20" rx="3" fill="#fff"/>
    </mask>
    <g mask="url(#a)">
        <path fill="#555" d="M0 0h63v20H0z"/>
        <path fill="{{ color }}" d="M63 0h36v20H63z"/>
        <path fill="url(#b)" d="M0 0h99v20H0z"/>
    </g>
    <g fill="#fff" text-anchor="middle" font-family="DejaVu Sans,Verdana,Geneva,sans-serif" font-size="11">
        <text x="31.5" y="15" fill="#010101" fill-opacity=".3">coverage</text>
        <text x="31.5" y="14">coverage</text>
        <text x="80" y="15" fill="#010101" fill-opacity=".3">{{ total }}%</text>
        <text x="80" y="14">{{ total }}%</text>
    </g>
</svg>';

    public static function generateBadge(): void
    {
        $generator = new self();
        $generator->generate();
    }

    public function generate(): void
    {
        $coverage = $this->getCoverage();
        $color = $this->getColor($coverage);
        $this->svgContent = str_replace('{{ total }}', (string) $coverage, $this->svgContent);
        $this->svgContent = str_replace('{{ color }}', $color, $this->svgContent);

        file_put_contents(__DIR__ . '/../../.github/badges/coverage.svg', trim($this->svgContent));
    }

    private function getCoverage(): float
    {
        $xml = new SimpleXMLElement(file_get_contents(__DIR__ . '/../../coverage.xml'));
        $metrics = $xml->xpath('//metrics');
        $totalElements = 0;
        $coveredElements = 0;

        foreach ($metrics as $metric) {
            $totalElements += (int) $metric['elements'];
            $coveredElements += (int) $metric['coveredelements'];
        }

        if ($totalElements <= 0) {
            return 0.0;
        }

        return round($coveredElements / $totalElements * 100);
    }

    private function getColor(float $coverage): string
    {
        return match (true) {
            $coverage < 40 => '#e05d44',      // Red
            $coverage < 60 => '#fe7d37',      // Orange
            $coverage < 75 => '#dfb317',      // Yellow
            $coverage < 90 => '#a4a61d',      // Yellow-Green
            $coverage < 95 => '#97CA00',      // Green
            $coverage <= 100 => '#4c1',       // Bright Green
            default => '#4f4e4e'              // Default Gray
        };
    }
}
