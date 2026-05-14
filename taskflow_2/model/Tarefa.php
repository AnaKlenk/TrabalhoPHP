<?php
namespace App\Model;

class Tarefa {
    private ?int $id;
    private string $titulo;
    private string $descricao;
    private ?string $dataLimite;
    private string $status;
    private int $criadoPor;
    private int $responsavelId;

    private function __construct() {}

    public static function criar(
        ?int $id,
        string $titulo,
        string $descricao,
        ?string $dataLimite,
        string $status,
        int $criadoPor,
        int $responsavelId
    ): static {
        $t = new static();
        $t->id = $id;
        $t->setTitulo($titulo);
        $t->setDescricao($descricao);
        $t->dataLimite = ($dataLimite !== '' && $dataLimite !== null) ? $dataLimite : null;
        $t->setStatus($status);
        $t->criadoPor = $criadoPor;
        $t->responsavelId = $responsavelId;
        return $t;
    }

    public function getId(): ?int { return $this->id; }
    public function getTitulo(): string { return $this->titulo; }
    public function getDescricao(): string { return $this->descricao; }
    public function getDataLimite(): ?string { return $this->dataLimite; }
    public function getStatus(): string { return $this->status; }
    public function getCriadoPor(): int { return $this->criadoPor; }
    public function getResponsavelId(): int { return $this->responsavelId; }

    public function setTitulo(string $titulo): void {
        if (trim($titulo) === '') {
            throw new \InvalidArgumentException("O título é obrigatório.");
        }
        $this->titulo = $titulo;
    }

    public function setDescricao(string $descricao): void {
        $this->descricao = $descricao;
    }

    public function setStatus(string $status): void {
        $validos = ['pendente', 'em_andamento', 'concluida'];
        if (!in_array($status, $validos)) {
            throw new \InvalidArgumentException("Status inválido.");
        }
        $this->status = $status;
    }
}
