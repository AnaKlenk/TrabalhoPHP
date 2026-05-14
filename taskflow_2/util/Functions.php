<?php
namespace App\Util;

class Functions {
    public static function preparaTexto(string $texto): string {
        return trim(htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'));
    }

    public static function formatarData(string $data): string {
        if (empty($data)) return '-';
        $d = \DateTime::createFromFormat('Y-m-d', $data);
        return $d ? $d->format('d/m/Y') : $data;
    }

    public static function formatarDataHora(string $dataHora): string {
        if (empty($dataHora)) return '-';
        $d = \DateTime::createFromFormat('Y-m-d H:i:s', $dataHora);
        return $d ? $d->format('d/m/Y \à\s H:i') : $dataHora;
    }

    public static function statusLabel(string $status): string {
        return match($status) {
            'pendente'     => 'Pendente',
            'em_andamento' => 'Em Andamento',
            'concluida'    => 'Concluída',
            default        => ucfirst($status),

        };
    }

    public static function statusClass(string $status): string {
        return match($status) {
            'pendente'     => 'badge--pending',
            'em_andamento' => 'badge--progress',
            'concluida'    => 'badge--done',
            default        => '',
        };
    }

    public static function dataVencida(string $dataLimite): bool {
        if (empty($dataLimite)) return false;
        return strtotime($dataLimite) < strtotime(date('Y-m-d'));
    }
}
